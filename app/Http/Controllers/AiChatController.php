<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AiChatController extends Controller
{
    /**
     * Handle an incoming chat message from the student and return an AI reply.
     *
     * POST /ai/chat
     * Returns: { reply: string } | { error: string }
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        $message = trim($request->input('message'));

        // ── Build context from the student's own data ──────────────────────
        $grades = DB::table('grades')
            ->join('courses', 'grades.course_id', '=', 'courses.id')
            ->where('grades.user_id', $user->id)
            ->select('courses.name as course', 'grades.grade', 'grades.period')
            ->get();

        $pendingBalance = DB::table('debts')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->sum('amount_usd');

        $enrolledCourses = DB::table('enrollments')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->where('enrollments.student_id', $user->id)
            ->select('courses.name', 'courses.code')
            ->get();

        $coursesList = $enrolledCourses->isEmpty() ? 'Ninguno registrado' : $enrolledCourses->pluck('name')->join(', ');
        $gradesText = $this->formatGrades($grades);
        $balanceClean = number_format((float) $pendingBalance, 2);
        $studentName = $user->name;
        $studentId = $user->id;

        // ── Lógica del Chatbot Interno Estático Avanzado ────────────────────────────
        $msg = strtolower($message);

        // Mapeo exhaustivo de intenciones con jerga venezolana y errores comunes
        $intents = [
            'saludos' => [
                'keywords' => ['hola', 'buenas', 'saludos', 'qe tal', 'q tal', 'epale', 'epa', 'activo', 'wenas', 'holis', 'alo', 'buen dia', 'buenas tardes'],
                'reply' => "¡Hola, {$studentName}! 👋 Soy tu asistente interno de UniPortal. Puedo ayudarte con tus pagos, notas, inscripciones y soporte. ¿En qué te puedo orientar?"
            ],
            'finanzas' => [
                'keywords' => [
                    'pago',
                    'pagar',
                    'transferencia',
                    'pago movil',
                    'dolares',
                    'divisas',
                    'bs',
                    'bolivares',
                    'tasa',
                    'bcv',
                    'cuota',
                    'deuda',
                    'comprobante',
                    'capture',
                    'verificar pago',
                    'no me sale el pago',
                    'plata',
                    'reales',
                    'pague',
                    'cobro',
                    'mensualidad',
                    'pagos',
                    'depositos',
                    'ref',
                    'saldo',
                    'factura',
                    'pag',
                    'pagó',
                    'dollar',
                    'dólar',
                    'zel',
                    'zelle',
                    'zelle'
                ],
                // Generamos una respuesta dinámica
                'reply' => ($balanceClean > 0)
                    ? "💰 Tienes un saldo pendiente de **{$balanceClean} REF**.\n\nPara reportar un nuevo pago o transferencia:\n1. Ve al menú lateral izquierdo y entra en **'Pagos'**.\n2. La plataforma te mostrará la tasa BCV del día para calcular los Bolívares.\n3. Sube tu capture/comprobante de tu pago móvil o transferencia.\n¡Si ya pagaste, espera a que el equipo de Administración lo apruebe!"
                    : "¡Excelente noticia! 🎉 Actualmente no tienes ninguna deuda pendiente en el sistema. Tu saldo es de 0 REF. Si deseas reportar un pago adelantado, dirígete a la sección lateral de **'Pagos'**."
            ],
            'academico_notas' => [
                'keywords' => ['notas', 'calificaciones', 'record', 'examen', 'parcial', 'reparacion', 'intensivo', 'notass', 'promedio', 'evaluacion', 'aprobe', 'calificasion', 'raspado'],
                'reply' => ($gradesText == 'Ninguna')
                    ? "🎓 En este momento no tienes calificaciones consolidadas en el sistema."
                    : "🎓 *Aquí tienes un resumen de tus notas actuales:*\n\n{$gradesText}\n\nPuedes ver el detalle completo dirigiéndote al menú superior de Académico > 'Calificaciones'."
            ],
            'academico_horario' => [
                'keywords' => ['materias', 'horario', 'seccion', 'profesor', 'profe', 'clase', 'aula', 'virtual', 'inscrito', 'horaryo', 'clases', 'turno', 'aula virtual'],
                'reply' => ($coursesList == 'Ninguno')
                    ? "📚 Por el momento, no tienes materias inscritas."
                    : "📚 *Tus materias inscritas actualmente son:*\n{$coursesList}\n\nPara ver fechas específicas o información del profesor, revisa tu sección de Horario."
            ],
            'soporte_tecnico' => [
                'keywords' => ['contraseña', 'clave', 'olvide', 'perfil', 'foto', 'correo', 'no entra', 'error', '419', 'lento', 'bug', 'celular', 'app', 'ayudaaa', 'osea', 'no carga', 'se cayo', 'sistema', 'ayuda', 'help'],
                'reply' => "🛠 *Soporte Técnico*\nSi presentas errores en el portal, bloqueos de 419, lentitud o problemas con tu contraseña:\n1. Refresca la página o limpia la caché de tu navegador.\n2. Si necesitas ayuda con tu usuario o recuperar clave, contacta directamente con el departamento de TI de la universidad a soporte@uniportal.edu.ve."
            ],
            'procesos_admin' => [
                'keywords' => ['carnet', 'constancia', 'estudio', 'egresado', 'grado', 'solicitud', 'retiro', 'congelar', 'semestre', 'trimestre', 'inscripcion', 'inscricion', 'documentos', 'papeles', 'titulos'],
                'reply' => "📄 *Trámites Administrativos*\nPara gestionar constancias de estudio, congelar el semestre, pedir carnet o realizar solicitudes de grado, por favor acude presencialmente al departamento de Control de Estudios o verifica las opciones de tu panel de 'Usuario'."
            ]
        ];

        $reply = "";
        $matched = false;

        // Limpiar el mensaje de acentos comunes para facilitar la coincidencia
        $unwanted_array = array('á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U', 'ñ' => 'n', 'Ñ' => 'N');
        $msgClean = strtr($msg, $unwanted_array);

        // Recorrer el array buscando la mejor intención
        foreach ($intents as $intent => $data) {
            foreach ($data['keywords'] as $keyword) {
                if (str_contains($msgClean, $keyword)) {
                    $reply = $data['reply'];
                    $matched = true;
                    // Romper los dos bucles apenas encaje la primera intención (prioridad)
                    break 2;
                }
            }
        }

        // Si no encontró nada, respuesta genérica indicando menús
        if (!$matched) {
            \Illuminate\Support\Facades\Log::info("Chatbot Unmatched Message from Student ID {$studentId}: '{$message}'");
            $reply = "Lo siento, soy un asistente virtual programado con funciones predefinidas 😊. \nPor favor intente usar otras palabras clave. \n\nPuedo orientarte con precisión si me preguntas sobre:\n- Tus *pagos*, deudas, transferencias o tasa BCV 💰\n- Tus *notas* o record académico 🎓\n- Tu *horario* o materias inscritas 📚\n- Fallas del sistema o *soporte técnico* 🛠️\n- Opciones de *trámites (constancias, carnet)* 📄\n\n¿En qué deseas enfocarte?";
        }

        return response()->json(['reply' => trim($reply)]);
    }

    /**
     * Format grades into a readable string for the AI prompt.
     */
    private function formatGrades($grades): string
    {
        if ($grades->isEmpty()) {
            return '  (Sin calificaciones registradas)';
        }

        $lines = [];
        foreach ($grades as $g) {
            $lines[] = "  - {$g->course}: {$g->grade} ({$g->period})";
        }

        return implode("\n", $lines);
    }
}
