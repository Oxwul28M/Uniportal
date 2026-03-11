# Documentación del Proyecto: Portal Universitario

## 1. Visión General
Desarrollo de un Portal Universitario funcional y moderno que utiliza las tecnologías ya establecidas en el proyecto. El sistema permitirá la gestión académica (notas, cursos) y contará con un **Chatbot con Inteligencia Artificial** para asistir a los estudiantes.

## 2. Tecnologías (Stack Actual del Proyecto)

Se confirma el uso estricto de las herramientas ya instaladas y configuradas:

### Backend & Autenticación
*   **Laravel 12**: Framework PHP principal.
*   **Laravel Breeze**: Sistema de autenticación ya instalado.
*   **Supabase (PostgreSQL)**: Base de datos en la nube para facilitar la colaboración.

### Frontend (Visual)
*   **Blade Templates**: Motor de plantillas nativo.
*   **Tailwind CSS**: Framework de estilos (Ya configurado con Vite).
*   **Alpine.js**: Interactividad ligera (Dropdowns, Modales).

### Inteligencia Artificial (Costo Cero)
*   **Gemini API (Google)**: Usaremos la capa gratuita de Gemini para potenciar el chatbot sin incurrir en costos.

## 3. Arquitectura del Sistema

### Estructura de Base de Datos (Supabase / PostgreSQL)
Adicional a la tabla `users` (ya existente por Breeze), implementaremos:
1.  **Courses**: Asignaturas (Nombre, Código, Profesor).
2.  **Grades**: Calificaciones (Relación Usuario <-> Curso).

### Módulos Principales
1.  **Dashboard**:
    *   *Estudiantes*: Vista de "Mis Cursos" y "Mis Notas".
    *   *Profesores/Admin*: Panel para subir notas.
2.  **Chatbot Asistente**:
    *   Componente flotante global.
    *   Capacidad de responder preguntas frecuentes y consultas de datos personales (ej. "Dime mi nota de Física").

## 4. Plan de Implementación

1.  **Validación**: Confirmar conexión Supabase y Breeze.
2.  **Base de Datos**: Crear migraciones para `Courses` y `Grades`.
3.  **Vistas**: Personalizar el dashboard de Breeze.
4.  **IA Integration**: Conectar el Chatbot con la API de Gemini.

---
**Estado**: Listo para comenzar desarrollo sobre la infraestructura existente.
