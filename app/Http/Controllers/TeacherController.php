<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    /**
     * Display teacher dashboard.
     */
    public function dashboard()
    {
        $totalStudents = DB::table('users')->where('role', 'student')->count();
        $sections = 4; // Mock
        return view('teacher.dashboard', compact('totalStudents', 'sections'));
    }

    /**
     * View assigned courses/sections.
     */
    public function courses()
    {
        $courses = DB::table('courses')->get(); // In reality, filter by teacher
        return view('teacher.courses', compact('courses'));
    }

    /**
     * View grading interface for specific sections.
     */
    public function grading()
    {
        $students = DB::table('users')->where('role', 'student')->get();
        // Optimizamos cargando las notas de una vez (Simulando Eager Loading)
        $grades = DB::table('grades')
            ->where('course_id', 1)
            ->whereIn('user_id', $students->pluck('id'))
            ->pluck('grade', 'user_id');

        return view('teacher.grading', compact('students', 'grades'));
    }

    /**
     * View academic agenda/calendar.
     */
    public function agenda()
    {
        return view('teacher.agenda');
    }

    /**
     * Store student grades.
     */
    public function storeGrades(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'grades' => 'required|array',
        ]);

        foreach ($request->grades as $studentId => $grade) {
            DB::table('grades')->updateOrInsert(
                ['user_id' => $studentId, 'course_id' => $request->course_id],
                ['grade' => $grade, 'period' => '2026-I', 'updated_at' => now()]
            );
        }

        return back()->with('success', 'Calificaciones actualizadas correctamente.');
    }
}
