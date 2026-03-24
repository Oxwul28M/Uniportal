<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        // Cache stats for 5 minutes since these are expensive to count every time
        $stats = Cache::remember('admin_dashboard_stats', 300, function () {
            return [
                'totalStudents' => User::where('role', 'student')->count(),
                'totalTeachers' => User::where('role', 'teacher')->count(),
                'totalManagers' => User::where('role', 'manager')->count(),
                'totalEarnings' => DB::table('payments')->where('status', 'approved')->sum('amount_usd'),
                'requests' => User::where('status', 'pending')->count(),
            ];
        });

        $totalStudents = $stats['totalStudents'];
        $totalTeachers = $stats['totalTeachers'];
        $totalManagers = $stats['totalManagers'];
        $totalEarnings = $stats['totalEarnings'];

        $usersList = User::latest()->take(10)->get();
        $posts = Post::with('author')->latest()->take(5)->get();
        $recentPosts = $posts; // Use the same for now to fix the view

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalTeachers',
            'totalManagers',
            'totalEarnings',
            'usersList',
            'posts',
            'recentPosts',
            'stats'
        ));
    }

    /**
     * View all users (Supports search).
     */
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        $users = $query->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    /**
     * View all posts (News/Events).
     */
    public function posts()
    {
        $posts = Post::with('author')->latest()->paginate(15);
        return view('admin.posts', compact('posts'));
    }

    /**
     * View security logs/activity.
     */
    public function security()
    {
        return view('admin.security');
    }

    /**
     * Store a new user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|string|in:admin,teacher,manager,student',
            'password' => 'required|string|min:8',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = 'active'; // Admins create active users directly

        $user = User::create($validated);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Usuario ' . $user->name . ' creado exitosamente.',
                'user' => $user
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * View pending registration requests.
     */
    public function registrationRequests()
    {
        $requests = User::where('status', 'pending')->latest()->paginate(20);
        return view('admin.requests.index', compact('requests'));
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

    /**
     * Update user details.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,manager,teacher,student',
            'status' => 'required|in:active,pending,suspended,rejected',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
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
     * Suspend/Deactivate user.
     */
    public function suspend($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'suspended';
        $user->save();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Usuario ' . $user->name . ' ha sido suspendido.',
                'user' => $user
            ]);
        }

        return back()->with('info', 'Usuario ' . $user->name . ' ha sido suspendido del sistema.');
    }

    /**
     * Toggle user status between active and suspended.
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes cambiar tu propio estatus.');
        }

        $user->status = ($user->status === 'active') ? 'suspended' : 'active';
        $user->save();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Estado de {$user->name} cambiado a {$user->status}.",
                'user' => $user
            ]);
        }

        return back()->with('success', "Estado de {$user->name} actualizado correctamente.");
    }

    /**
     * Soft-block user (Replaces permanent delete).
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent self-suspension
        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes suspender tu propia cuenta.');
        }

        $user->status = 'suspended';
        $user->save();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Usuario {$user->name} suspendido correctamente (Los datos se conservan).",
                'user' => $user,
                'user_id' => $id
            ]);
        }

        return back()->with('success', "Usuario {$user->name} suspendido correctamente.");
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

        // Can share the exact same blade view
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
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $columns, ';');
            foreach ($payments as $payment) {
                $row = [
                    $payment->created_at,
                    $payment->reference,
                    $payment->student,
                    $payment->concept ?? 'Pago Genérico',
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

        // Chunk inserts to prevent massive memory usage if thousands of students
        foreach (array_chunk($debtsToInsert, 500) as $chunk) {
            DB::table('debts')->insert($chunk);
        }

        return back()->with('success', count($activeStudents) . ' facturas de "' . $fee->name . '" asignadas correctamente a los estudiantes activos.');
    }
}
