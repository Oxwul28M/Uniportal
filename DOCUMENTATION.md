# 🎓 UniPortal — Sistema de Gestión Universitaria Integral

## 1. Introducción
UniPortal es una plataforma de gestión académica y financiera diseñada para instituciones universitarias en Venezuela. El sistema centraliza el control de inscripciones, calificaciones, cobros en divisas (REF) y atención al estudiante mediante un asistente virtual interno.

---

## 2. Arquitectura Técnica
- **Framework:** Laravel 12 (PHP 8.2+)
- **Base de Datos:** PostgreSQL (Alojada en Supabase)
- **Frontend:** Tailwind CSS + Alpine.js + Material Symbols
- **Estado de Cuenta:** Sistema bimoneda (Bs/REF) con integración de tasa BCV.

---

## 3. Roles y Permisos

| Rol | Capacidad |
| :--- | :--- |
| **Admin** | Gestión total de usuarios, aranceles, noticias, seguridad y reportes económicos globales. |
| **Manager** | Aprobación de avisos, validación de pagos estudiantiles y exportación de recaudación. |
| **Teacher** | Gestión de cursos asignados, carga de notas (manual/Excel) y control de agenda académica. |
| **Student** | Visualización de notas, reporte de pagos en Bs, descarga de documentos e interacción con el bot. |

---

## 4. Módulos Críticos

### 💰 Sistema Financiero (REF / BCV)
- **Estandarización:** El sistema utiliza la unidad **REF** (Referencial) para la contabilidad interna.
- **Tasa BCV:** Integración con API externa para obtener la tasa oficial del día. Permite la actualización manual desde el panel Admin/Manager.
- **Flujo de Pago:** El estudiante reporta el pago en Bolívares indicando la fecha de la transacción. El sistema busca la tasa BCV histórica de **ese día específico** para calcular la equivalencia exacta en REF.

### 🤖 Chatbot Académico Interno
- **Motor:** Sistema de procesamiento de intenciones basado en palabras clave (Keyword-based Intent Matching).
- **Entorno:** Optimizado para Venezuela (incluye jerga, errores ortográficos comunes y términos financieros).
- **Privacidad:** 100% local (no depende de APIs externas costosas), garantizando rapidez y ahorro de costos.
- **Funciones:** Consulta de saldo, materias inscritas y récord de notas de forma instantánea.

### 📚 Gestión Académica
- **Carga Masiva:** Los profesores pueden descargar una plantilla de Excel, cargar las notas y reimportarlas al sistema.
- **Agenda:** Sistema de eventos dinámico sincronizado entre profesores (creación) y estudiantes (visualización).

---

## 5. Instalación y Puesta en Marcha

1. **Clonar y Dependencias:**
   ```bash
   composer install
   npm install && npm run build
   ```
2. **Entorno:** Copiar `.env.example` a `.env` y configurar la base de datos Supabase.
3. **Optimización:**
   ```bash
   php artisan storage:link
   php artisan migrate --seed
   php artisan optimize
   ```

---

## 6. Seguridad y Buenas Prácticas
- **Validación de Archivos:** Los comprobantes de pago solo aceptan formatos de imagen seguros.
- **Middleware de Actividad:** Los usuarios suspendidos o rechazados son expulsados automáticamente del sistema en tiempo real.
- **Protección de Datos:** Se ha desactivado el borrado físico de usuarios para preservar el histórico de datos universitarios (Soft-block vía estatus `suspended`).

---
✨ *Desarrollado con enfoque en robustez y experiencia de usuario moderna.*
