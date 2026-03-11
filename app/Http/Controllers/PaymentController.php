<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource (Payment History).
     */
    public function index()
    {
        $user = Auth::user();

        $payments = DB::table('payments')
            ->leftJoin('fees', 'payments.fee_id', '=', 'fees.id')
            ->where('payments.user_id', $user->id)
            ->select('payments.*', 'fees.name as fee_name')
            ->latest()
            ->get();

        return view('student.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource (Report Payment).
     */
    public function create()
    {
        // Fetch only the fees the student actually owes (debts)
        $debts = DB::table('debts')
            ->join('fees', 'debts.fee_id', '=', 'fees.id')
            ->select('debts.id as debt_id', 'fees.id as fee_id', 'fees.name', 'debts.amount_usd as price_usd')
            ->where('debts.user_id', Auth::id())
            ->where('debts.status', 'pending')
            ->get();

        $exchangeRate = DB::table('exchange_rates')->latest('fetched_at')->first();

        // Pass debts instead of all fees
        return view('student.payments.create', ['fees' => $debts, 'exchangeRate' => $exchangeRate]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'reference' => 'required|unique:payments',
            'amount_bs' => 'required|numeric|min:0',
            // Notice: fee_id from form is now actually debt_id
            'fee_id' => 'required|exists:debts,id', 
        ]);

        $debt = DB::table('debts')->where('id', $request->fee_id)->first();
        if (!$debt || $debt->user_id !== Auth::id() || $debt->status !== 'pending') {
            return back()->with('error', 'Deuda inválida o ya procesada.');
        }

        $exchangeRate = DB::table('exchange_rates')->latest('fetched_at')->first();
        $rate = $exchangeRate ? $exchangeRate->rate : 1;

        $amountUsd = round($request->amount_bs / $rate, 2);

        // Validation: If amount doesn't cover exact debt
        if ($amountUsd < $debt->amount_usd) {
            return back()->withInput()->with('error_monto', 'MONTO INSUFICIENTE');
        }

        DB::beginTransaction();
        try {
            // Register Payment
            DB::table('payments')->insert([
                'user_id' => Auth::id(),
                'fee_id' => $debt->fee_id,
                'reference' => $request->reference,
                'amount_bs' => $request->amount_bs,
                'amount_usd' => $amountUsd,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Set Debt status to in_review so it doesn't show up again while pending
            DB::table('debts')->where('id', $debt->id)->update(['status' => 'in_review', 'updated_at' => now()]);

            DB::commit();
            return redirect()->route('student.payments.index')->with('success', 'Pago reportado correctamente y en revisión.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error reportando el pago. Intente nuevamente.');
        }
    }

    /**
     * Manager actions: Approve Payment
     */
    public function approve($id)
    {
        DB::beginTransaction();
        try {
            $payment = DB::table('payments')->where('id', $id)->first();
            
            // Mark Payment as Approved
            DB::table('payments')->where('id', $id)->update(['status' => 'approved', 'updated_at' => now()]);
            
            // Mark corresponding Debt as Paid
            DB::table('debts')
                ->where('user_id', $payment->user_id)
                ->where('fee_id', $payment->fee_id)
                ->where('status', 'in_review')
                ->update(['status' => 'paid', 'updated_at' => now()]);

            DB::commit();
            return back()->with('success', 'Pago aprobado y deuda solventada.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ocurrió un error al aprobar.');
        }
    }

    /**
     * Manager actions: Reject Payment
     */
    public function reject($id)
    {
        DB::beginTransaction();
        try {
            $payment = DB::table('payments')->where('id', $id)->first();
            
            // Mark Payment as Rejected
            DB::table('payments')->where('id', $id)->update(['status' => 'rejected', 'updated_at' => now()]);
            
            // Return corresponding Debt from in_review back to pending
            DB::table('debts')
                ->where('user_id', $payment->user_id)
                ->where('fee_id', $payment->fee_id)
                ->where('status', 'in_review')
                ->update(['status' => 'pending', 'updated_at' => now()]);

            DB::commit();
            return back()->with('info', 'Pago rechazado. La deuda vuelve a estar pendiente para el estudiante.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ocurrió un error al rechazar.');
        }
    }

    /**
     * Real API Update for BCV Exchange Rate
     */
    public function updateRateFromApi()
    {
        try {
            $response = \Illuminate\Support\Facades\Http::withoutVerifying()->withHeaders([
                'x-dolarvzla-key' => '2320db1bc8b81274ea5552c7d0158512b39cecd259f05eb477a71ea6231d26d1',
            ])->get('https://api.dolarvzla.com/public/bcv/exchange-rate');

            // Optionally, handle their /list endpoint if that's what we get
            $data = $response->json();
            
            if ($response->successful() && isset($data['current']['usd'])) {
                $newRate = (float) $data['current']['usd'];

                DB::table('exchange_rates')->insert([
                    'rate' => $newRate,
                    'fetched_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return response()->json(['success' => true, 'new_rate' => $newRate]);
            }
            
            return response()->json(['success' => false, 'message' => 'Respuesta no válida del BCV: ' . substr($response->body(), 0, 100)]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error API: ' . $e->getMessage()]);
        }
    }
    /**
     * Store new BCV rate from manager
     */
    public function updateRate(Request $request)
    {
        $request->validate([
            'rate' => 'required|numeric|min:0.01'
        ]);

        DB::table('exchange_rates')->insert([
            'rate' => $request->rate,
            'fetched_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Store a new payment fee for students.
     */
    public function storeFee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price_usd' => 'required|numeric|min:0.01'
        ]);

        DB::table('fees')->insert([
            'name' => $request->name,
            'price_usd' => $request->price_usd,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Nuevo arancel añadido correctamente.');
    }
}
