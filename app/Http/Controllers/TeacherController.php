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
            ->get()
            ->keyBy('user_id');

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

        foreach ($request->grades as $studentId => $evals) {
            // Check if any evaluation has a value
            if (!empty(array_filter($evals, fn($v) => $v !== null && $v !== ''))) {
                Grade::updateOrCreate(
                    ['user_id' => $studentId, 'course_id' => $course->id],
                    [
                        'eval1' => $evals['eval1'] ?? 0,
                        'eval2' => $evals['eval2'] ?? 0,
                        'eval3' => $evals['eval3'] ?? 0,
                        'eval4' => $evals['eval4'] ?? 0,
                        'period' => $request->period
                    ]
                );
            }
        }

        return back()->with('success', 'Calificaciones de "' . $course->name . '" actualizadas correctamente.');
    }

    /**
     * Export a mass grading CSV template for a specific course.
     */
    public function exportTemplate(Request $request)
    {
        $courseId = $request->query('course_id');
        $course = Course::where('id', $courseId)->where('teacher_id', Auth::id())->firstOrFail();
        $students = $course->students()->get();

        $filename = "Plantilla_Notas_" . str_replace(' ', '_', $course->name) . ".csv";
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            // Byte Order Mark for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header: ID, Name, Eval1, Eval2, Eval3, Eval4
            fputcsv($file, ['ID_Estudiante', 'Nombre', 'Corte_1_(0-100)', 'Corte_2_(0-100)', 'Corte_3_(0-100)', 'Corte_4_(0-100)'], ';');

            foreach ($students as $student) {
                // Fetch current grades if they exist
                $grade = Grade::where('user_id', $student->id)->where('course_id', $student->pivot->course_id)->first();
                fputcsv($file, [
                    $student->id,
                    $student->name,
                    $grade->eval1 ?? '',
                    $grade->eval2 ?? '',
                    $grade->eval3 ?? '',
                    $grade->eval4 ?? '',
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import mass grades from a CSV file.
     */
    public function importGrades(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'file' => 'required|file|mimes:csv,txt',
            'period' => 'required|string'
        ]);

        $course = Course::where('id', $request->course_id)->where('teacher_id', Auth::id())->firstOrFail();
        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');
        
        // Skip BOM if present
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        // Skip header
        fgetcsv($handle, 0, ';');

        $count = 0;
        while (($data = fgetcsv($handle, 0, ';')) !== FALSE) {
            if (count($data) < 6) continue;

            $studentId = $data[0];
            $e1 = str_replace(',', '.', $data[2]);
            $e2 = str_replace(',', '.', $data[3]);
            $e3 = str_replace(',', '.', $data[4]);
            $e4 = str_replace(',', '.', $data[5]);

            // Ensure the student is actually enrolled in this course
            if ($course->students()->where('users.id', $studentId)->exists()) {
                Grade::updateOrCreate(
                    ['user_id' => $studentId, 'course_id' => $course->id],
                    [
                        'eval1' => is_numeric($e1) ? $e1 : 0,
                        'eval2' => is_numeric($e2) ? $e2 : 0,
                        'eval3' => is_numeric($e3) ? $e3 : 0,
                        'eval4' => is_numeric($e4) ? $e4 : 0,
                        'period' => $request->period
                    ]
                );
                $count++;
            }
        }
        fclose($handle);

        return back()->with('success', "Se han importado correctamente las notas de $count estudiantes.");
    }
}
