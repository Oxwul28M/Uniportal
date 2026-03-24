<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Post;
use App\Models\AgendaItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        $user = Auth::user();
        $results = [
            'users' => collect(),
            'courses' => collect(),
            'posts' => collect(),
            'agenda' => collect(),
        ];

        if (!$query) {
            return view('search.results', compact('results', 'query'));
        }

        // Common search for Posts (News/Events)
        $results['posts'] = Post::published()
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->latest()
            ->take(5)
            ->get();

        if ($user->role === 'admin' || $user->role === 'manager') {
            // Admins and Managers can search Users
            $results['users'] = User::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->take(10)
                ->get();

            // And all Courses
            $results['courses'] = Course::where('name', 'like', "%{$query}%")
                ->orWhere('code', 'like', "%{$query}%")
                ->take(10)
                ->get();
        } elseif ($user->role === 'teacher') {
            // Teachers can search their own Courses
            $results['courses'] = Course::where('teacher_id', $user->id)
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('code', 'like', "%{$query}%");
                })
                ->take(10)
                ->get();

            // And their Agenda Items
            $results['agenda'] = AgendaItem::where('teacher_id', $user->id)
                ->where('title', 'like', "%{$query}%")
                ->take(10)
                ->get();
        } elseif ($user->role === 'student') {
            // Students can search their enrolled Courses
            $results['courses'] = $user->enrolledCourses()
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('code', 'like', "%{$query}%");
                })
                ->take(10)
                ->get();

            // And upcoming Agenda Items for their courses
            $results['agenda'] = AgendaItem::whereHas('courses', function($q) use ($user) {
                    $q->whereIn('courses.id', $user->enrolledCourses->pluck('id'));
                })
                ->where('title', 'like', "%{$query}%")
                ->take(10)
                ->get();
        }

        return view('search.results', compact('results', 'query'));
    }
}
