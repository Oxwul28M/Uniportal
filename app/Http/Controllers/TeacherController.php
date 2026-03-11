<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Grade;
use App\Models\User;

class TeacherController extends Controller
{
    /**
     * Display teacher dashboard.
     */
    public function dashboard()
    {
        $teacherId = Auth::id();
        
        $courses = Course::where('teacher_id', $teacherId)->withCount('students')->get();
        $totalStudents = $courses->sum('students_count');
        $sections = $courses->count();
        
        // Find recent grades updated by this teacher
        $recentGrades = Grade::whereIn('course_id', $courses->pluck('id'))
            ->with(['student', 'course'])
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('teacher.dashboard', compact('totalStudents', 'sections', 'courses', 'recentGrades'));
    }

    /**
     * View assigned courses/sections.
     */
    public function courses()
    {
        $courses = Course::where('teacher_id', Auth::id())
            ->withCount('students')
            ->latest()
            ->get();

        return view('teacher.courses', compact('courses'));
    }

    /**
     * View grading interface for specific sections.
     */
    public function grading(Request $request)
    {
        $courseId = $request->query('course_id');
        
        // Ensure the teacher owns this course
        $course = Course::where('teacher_id', Auth::id());
        
        if ($courseId) {
            $course = $course->where('id', $courseId);
        }
        
        $course = $course->firstOrFail();

        $students = $course->students()->get();
        $grades = Grade::where('course_id', $course->id)
            ->whereIn('user_id', $students->pluck('id'))
            ->pluck('grade', 'user_id');

        return view('teacher.grading', compact('course', 'students', 'grades'));
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
            'course_id' => 'required|exists:courses,id',
            'grades' => 'required|array',
            'period' => 'required|string'
        ]);

        // Authorization: Check if teacher owns the course
        $course = Course::where('id', $request->course_id)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        foreach ($request->grades as $studentId => $gradeValue) {
            // Only save if a value is provided
            if ($gradeValue !== null && $gradeValue !== '') {
                Grade::updateOrCreate(
                    ['user_id' => $studentId, 'course_id' => $course->id],
                    ['grade' => $gradeValue, 'period' => $request->period]
                );
            }
        }

        return back()->with('success', 'Calificaciones de "' . $course->name . '" actualizadas correctamente.');
    }
}
