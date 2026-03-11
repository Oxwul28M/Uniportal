<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Post;
use App\Models\User;

class ManagerController extends Controller
{
    /**
     * Display manager dashboard.
     */
    public function dashboard()
    {
        $latestRate = Cache::remember('latest_exchange_rate', 300, function() {
            return DB::table('exchange_rates')->latest('fetched_at')->first();
        });

        $stats = Cache::remember('manager_dashboard_stats', 300, function() {
            return [
                'pendingCount' => DB::table('payments')->where('status', 'pending')->count(),
                'totalUsd' => DB::table('payments')->where('status', 'approved')->sum('amount_usd'),
            ];
        });

        $pendingCount = $stats['pendingCount'];
        $totalUsd = $stats['totalUsd'];
        
        $myPosts = Post::where('user_id', Auth::id())->latest()->get();

        return view('manager.dashboard', [
            'latestRate' => $latestRate,
            'pendingCount' => $pendingCount,
            'totalUsd' => $totalUsd,
            'myPosts' => $myPosts,
        ]);
    }

    /**
     * View all pending payments.
     */
    public function payments()
    {
        $payments = DB::table('payments')
            ->join('users', 'payments.user_id', '=', 'users.id')
            ->leftJoin('fees', 'payments.fee_id', '=', 'fees.id')
            ->select('payments.*', 'users.name as student_name', 'fees.name as fee_name')
            ->where('payments.status', 'pending')
            ->latest()
            ->paginate(15);

        return view('manager.payments', compact('payments'));
    }

    /**
     * View financial reports.
     */
    public function reports()
    {
        // Usamos DATE_PART para Postgres (Supabase)
        $monthlyEarnings = DB::table('payments')
            ->where('status', 'approved')
            ->select(
                DB::raw('SUM(amount_usd) as total'),
                DB::raw("CAST(EXTRACT(MONTH FROM created_at) AS INTEGER) as month")
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('manager.reports', compact('monthlyEarnings'));
    }

    /**
     * Export earnings report to CSV.
     */
    public function export()
    {
        $payments = DB::table('payments')
            ->join('users', 'payments.user_id', '=', 'users.id')
            ->leftJoin('fees', 'payments.fee_id', '=', 'fees.id')
            ->select('payments.reference', 'users.name as student', 'fees.name as concept', 'payments.amount_bs', 'payments.amount_usd', 'payments.created_at')
            ->where('payments.status', 'approved')
            ->get();

        $fileName = 'recaudacion_uniportal_' . now()->format('Y-m-d') . '.csv';
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Fecha', 'Referencia', 'Estudiante', 'Concepto', 'Monto Bs', 'Monto USD'];

        $callback = function () use ($payments, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 support
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
            // Format headers explicitly with ; to fix Spanish Excel
            fputcsv($file, $columns, ';');

            foreach ($payments as $payment) {
                // Ensure numbers use comma for decimals if needed, or leave as dot
                $row = [
                    $payment->created_at,
                    $payment->reference,
                    $payment->student,
                    $payment->concept,
                    number_format($payment->amount_bs, 2, ',', ''),
                    number_format($payment->amount_usd, 2, ',', ''),
                ];
                fputcsv($file, $row, ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * View and manage personal news/events as a manager.
     */
    public function posts()
    {
        $posts = Post::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('manager.posts', compact('posts'));
    }

    /**
     * View all students and teachers (for manager, supports search).
     */
    public function users(Request $request)
    {
        $query = User::whereIn('role', ['teacher', 'student']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $users = $query->latest()->paginate(20);
        return view('manager.users', compact('users'));
    }

    /**
     * Update user details (Manager version).
     */
    public function update(Request $request, $id)
    {
        $user = User::whereIn('role', ['teacher', 'student'])->findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:teacher,student',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => \Illuminate\Support\Facades\Hash::make($request->password)]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Usuario ' . $user->name . ' actualizado correctamente.',
                'user' => $user
            ]);
        }

        return back()->with('success', 'Usuario ' . $user->name . ' actualizado correctamente.');
    }

    /**
     * Store a new user (Manager version).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:teacher,student',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'status' => 'active', // Managers create active users directly
        ]);

        return back()->with('success', 'Usuario creado exitosamente por el Manager.');
    }

    /**
     * Mass assign debt to all active students (Facturar Semestre)
     */
    public function assignDebts(Request $request)
    {
        $request->validate([
            'fee_id' => 'required|exists:fees,id'
        ]);

        $fee = DB::table('fees')->find($request->fee_id);
        $activeStudents = User::where('role', 'student')->where('status', 'active')->get();

        $debtsToInsert = [];
        $now = now();

        foreach ($activeStudents as $student) {
            $debtsToInsert[] = [
                'user_id' => $student->id,
                'fee_id' => $fee->id,
                'amount_usd' => $fee->price_usd,
                'status' => 'pending',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach(array_chunk($debtsToInsert, 500) as $chunk) {
            DB::table('debts')->insert($chunk);
        }

        return back()->with('success', count($activeStudents) . ' facturas de "' . $fee->name . '" asignadas correctamente a los estudiantes activos.');
    }
}
