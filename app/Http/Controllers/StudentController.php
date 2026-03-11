<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Grade;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentController extends Controller
{
    /**
     * View current grades.
     */
    public function grades()
    {
        $grades = Grade::where('user_id', Auth::id())
            ->with(['course.teacher'])
            ->get();

        return view('student.grades', compact('grades'));
    }

    /**
     * View daily schedule.
     */
    public function schedule()
    {
        // For demonstration, returning a sample schedule array
        $schedule = [
            ['time' => '08:00 AM - 10:00 AM', 'subject' => 'Redes de Computadoras', 'room' => 'LAB L-204'],
            ['time' => '10:30 AM - 12:30 PM', 'subject' => 'Bases de Datos II', 'room' => 'AULA 03'],
            ['time' => '02:00 PM - 04:00 PM', 'subject' => 'Sistemas Operativos', 'room' => 'AUDITORIO'],
        ];

        return view('student.schedule', compact('schedule'));
    }

    /**
     * Export schedule as PDF.
     */
    public function exportSchedule()
    {
        $schedule = [
            ['time' => '08:00 AM - 10:00 AM', 'subject' => 'Redes de Computadoras', 'room' => 'LAB L-204'],
            ['time' => '10:30 AM - 12:30 PM', 'subject' => 'Bases de Datos II', 'room' => 'AULA 03'],
            ['time' => '02:00 PM - 04:00 PM', 'subject' => 'Sistemas Operativos', 'room' => 'AUDITORIO'],
        ];

        $pdf = Pdf::loadView('student.pdf-schedule', compact('schedule'));
        return $pdf->download('mi_horario_' . now()->format('Ymd') . '.pdf');
    }

    /**
     * View documents and requests page.
     */
    public function documents()
    {
        $requests = DB::table('document_requests')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('student.documents', compact('requests'));
    }

    /**
     * Store new document request.
     */
    public function storeDocument(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string|max:255',
        ]);

        DB::table('document_requests')->insert([
            'user_id' => Auth::id(),
            'document_type' => $request->document_type,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Solicitud de ' . $request->document_type . ' creada exitosamente.');
    }

    /**
     * Cancel a pending document request.
     */
    public function cancelDocument($id)
    {
        $request = DB::table('document_requests')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$request) {
            return back()->with('error', 'Solicitud no encontrada.');
        }

        if ($request->status !== 'pending') {
            return back()->with('error', 'Solo puedes cancelar solicitudes pendientes.');
        }

        DB::table('document_requests')->where('id', $id)->delete();

        return back()->with('success', 'Solicitud de ' . $request->document_type . ' cancelada.');
    }

    /**
     * View enrollment page.
     */
    public function enrollment()
    {
        return view('student.enrollment');
    }
}
