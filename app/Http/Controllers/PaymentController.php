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

        // Get historical rates mapping: Date => Rate (for the calculator)
        $historicalRates = DB::table('exchange_rates')
            ->select(DB::raw('DATE(fetched_at) as date'), 'rate')
            ->orderBy('fetched_at', 'desc')
            ->get()
            ->keyBy('date')
            ->map(fn($item) => $item->rate)
            ->toArray();

        // fallback to latest
        $latestRateRec = DB::table('exchange_rates')->latest('fetched_at')->first();
        $latestRate = $latestRateRec ? $latestRateRec->rate : 1;

        return view('student.payments.create', [
            'fees' => $debts,
            'historicalRates' => $historicalRates,
            'latestRate' => $latestRate
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'reference' => 'required|digits:8|unique:payments',
            'amount_bs' => 'required|numeric|min:0.01',
            'fee_id' => 'required|exists:debts,id',
            'payment_date' => 'required|date|before_or_equal:today|after_or_equal:-1 month',
            'receipt' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'observations' => 'nullable|string|max:1000'
        ]);

        $debt = DB::table('debts')->where('id', $request->fee_id)->first();
        if (!$debt || $debt->user_id !== Auth::id() || $debt->status !== 'pending') {
            return back()->with('error', 'Deuda inválida o ya procesada.');
        }

        // Get the historical rate for the selected date
        $historicalRate = DB::table('exchange_rates')
            ->whereDate('fetched_at', '<=', $request->payment_date)
            ->orderBy('fetched_at', 'desc')
            ->value('rate');

        $rate = $historicalRate ?? 1;

        // Calculate equivalence automatically based on historic rate
        $amountUsd = round($request->amount_bs / $rate, 2);

        // Validation: If amount doesn't cover exact debt
        if ($amountUsd < $debt->amount_usd) {
            return back()->withInput()->with('error_monto', 'MONTO INSUFICIENTE (Enviaste REF ' . $amountUsd . ' pero la deuda es REF ' . $debt->amount_usd . ')');
        }

        $receiptPath = $request->file('receipt')->store('receipts', 'public');

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
                'receipt_path' => $receiptPath,
                'observations' => $request->observations,
                'payment_date' => $request->payment_date,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Set Debt status to in_review
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
     * Consul BCV rate from free public API — NO saves to DB.
     * The frontend shows the preview and the user clicks "Guardar Tasa" to persist.
     */
    public function updateRateFromApi()
    {
        try {
            // API pública BCV — sin key, sin costo
            $response = \Illuminate\Support\Facades\Http::withoutVerifying()
                ->timeout(10)
                ->get('https://bcv-api.rafnixg.dev/rates/');

            $data = $response->json();

            if ($response->successful() && isset($data['dollar']) && $data['dollar'] > 0) {
                return response()->json([
                    'success' => true,
                    'new_rate' => (float) $data['dollar'],
                    'date' => $data['date'] ?? now()->toDateString(),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Respuesta inesperada de la API BCV.',
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Store BCV rate explicitly (called by "Guardar Tasa" button).
     * Avoids duplicate entries for the same day.
     */
    public function updateRate(Request $request)
    {
        $request->validate([
            'rate' => 'required|numeric|min:0.01'
        ]);

        // Avoid saving the same day twice (upsert by date)
        $exists = DB::table('exchange_rates')
            ->whereDate('fetched_at', today())
            ->exists();

        if ($exists) {
            // Update today's record instead of inserting a duplicate
            DB::table('exchange_rates')
                ->whereDate('fetched_at', today())
                ->update([
                    'rate' => $request->rate,
                    'fetched_at' => now(),
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('exchange_rates')->insert([
                'rate' => $request->rate,
                'fetched_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

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
