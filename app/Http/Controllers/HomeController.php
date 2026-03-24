<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\AgendaItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the landing page.
     */
    public function index()
    {
        $news = Post::with('author')->published()->noticias()->latest()->take(3)->get();
        $events = Post::with('author')->published()->eventos()->latest()->take(3)->get();
        return view('welcome', compact('news', 'events'));
    }

    /**
     * Dashboard redirection logic.
     */
    public function dashboard()
    {
        $user = Auth::user();

        if ($user->role === 'student') {
            $posts = Post::with('author')->published()->latest()->take(6)->get();
            $pendingBalance = \Illuminate\Support\Facades\DB::table('debts')
                ->where('user_id', $user->id)
                ->where('status', 'pending')
                ->sum('amount_usd');

            // Fetch upcoming agenda items for enrolled courses
            $agendaItems = AgendaItem::whereHas('courses', function($query) use ($user) {
                    $query->whereIn('courses.id', $user->enrolledCourses->pluck('id'));
                })
                ->where('event_date', '>=', now())
                ->with(['courses'])
                ->orderBy('event_date', 'asc')
                ->take(5)
                ->get();

            return view('student.dashboard', compact('posts', 'pendingBalance', 'agendaItems'));
        }

        if ($user->role === 'teacher') {
            return app(TeacherController::class)->dashboard();
        }

        if ($user->role === 'manager') {
            return app(ManagerController::class)->dashboard();
        }

        if ($user->role === 'admin') {
            return app(AdminController::class)->dashboard();
        }

        return view('dashboard');
    }
}
