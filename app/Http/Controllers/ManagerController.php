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
        $latestRate = Cache::remember('latest_exchange_rate', 300, function () {
            return DB::table('exchange_rates')->latest('fetched_at')->first();
        });

        $stats = Cache::remember('manager_dashboard_stats', 300, function () {
            return [
                'pendingCount' => DB::table('payments')->where('status', 'pending')->count(),
                'totalUsd' => DB::table('payments')->where('status', 'approved')->sum('amount_usd'),
            ];
        });

        $pendingCount = $stats['pendingCount'];
        $totalUsd = $stats['totalUsd'];

        $myPosts = Post::where('user_id', Auth::id())->latest()->get();

        $courses = DB::table('courses')->select('id', 'name', 'code')->orderBy('name')->get();
        $students = User::where('role', 'student')->where('status', 'active')->select('id', 'name', 'email')->orderBy('name')->get();

        return view('manager.dashboard', [
            'latestRate' => $latestRate,
            'pendingCount' => $pendingCount,
            'totalUsd' => $totalUsd,
            'myPosts' => $myPosts,
            'courses' => $courses,
            'students' => $students,
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

        $courses = DB::table('courses')->select('id', 'name', 'code')->orderBy('name')->get();
        $students = User::where('role', 'student')->where('status', 'active')->select('id', 'name', 'email')->orderBy('name')->get();

        return view('manager.payments', compact('payments', 'courses', 'students'));
    }

    /**
     * View financial reports / Approved Payments Log.
     */
    public function reports()
    {
        $payments = DB::table('payments')
            ->join('users', 'payments.user_id', '=', 'users.id')
            ->leftJoin('fees', 'payments.fee_id', '=', 'fees.id')
            ->select('payments.*', 'users.name as student_name', 'fees.name as concept_name')
            ->where('payments.status', 'approved')
            ->orderBy('payments.created_at', 'desc')
            ->paginate(20);

        return view('manager.reports', compact('payments'));
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
            $query->where(function ($q) use ($search) {
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
     * Toggle user status (Manager version).
     */
    public function toggleStatus($id)
    {
        $user = User::whereIn('role', ['teacher', 'student'])->findOrFail($id);
        $user->status = ($user->status === 'active') ? 'suspended' : 'active';
        $user->save();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Estado de {$user->name} cambiado a {$user->status}.",
                'user' => $user
            ]);
        }

        return back()->with('success', "Estado de {$user->name} actualizado.");
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
     * Assign new custom debt to specific target (all, course, student).
     */
    public function assignDebts(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price_usd' => 'required|numeric|min:0.01',
            'target_type' => 'required|in:all,course,student',
            'course_id' => 'required_if:target_type,course',
            'student_id' => 'required_if:target_type,student',
        ]);

        // Crea el nuevo "fee" (concepto de pago) sobre la marcha para identificar la deuda de forma coherente.
        $feeId = DB::table('fees')->insertGetId([
            'name' => $request->title,
            'price_usd' => $request->price_usd,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $students = collect();

        if ($request->target_type === 'all') {
            $students = User::where('role', 'student')->where('status', 'active')->get();
        } elseif ($request->target_type === 'course') {
            $studentIds = DB::table('enrollments')
                ->where('course_id', $request->course_id)
                ->pluck('user_id');
            $students = User::whereIn('id', $studentIds)->where('status', 'active')->get();
        } elseif ($request->target_type === 'student') {
            $student = User::where('role', 'student')->where('status', 'active')->find($request->student_id);
            if ($student) {
                $students->push($student);
            }
        }

        if ($students->isEmpty()) {
            return back()->with('error', 'No se encontraron estudiantes válidos/activos para asignar esta deuda.');
        }

        $debtsToInsert = [];
        $now = now();

        foreach ($students as $student) {
            $debtsToInsert[] = [
                'user_id' => $student->id,
                'fee_id' => $feeId,
                'amount_usd' => $request->price_usd,
                'status' => 'pending',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Chunk insert para evitar sobrecarga de la DB
        foreach (array_chunk($debtsToInsert, 500) as $chunk) {
            DB::table('debts')->insert($chunk);
        }

        return back()->with('success', count($students) . " facturas de '{$request->title}' (REF {$request->price_usd}) asignadas exitosamente.");
    }

    /**
     * View pending registration requests.
     */
    public function registrationRequests()
    {
        $requests = User::where('status', 'pending')->latest()->paginate(20);
        return view('manager.requests.index', compact('requests'));
    }

    /**
     * Approve a registration request.
     */
    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'active';
        $user->save();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Solicitud de ' . $user->name . ' aprobada.',
                'user_id' => $id
            ]);
        }

        return back()->with('success', 'La solicitud de ' . $user->name . ' ha sido aprobada.');
    }

    /**
     * Reject a registration request.
     */
    public function reject($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'rejected';
        $user->save();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Solicitud de ' . $user->name . ' rechazada.',
                'user_id' => $id
            ]);
        }

        return back()->with('error', 'La solicitud de ' . $user->name . ' ha sido rechazada.');
    }
}
