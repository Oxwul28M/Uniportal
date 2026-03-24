# 🎓 UniPortal — Laravel Edition

Sistema de gestión universitaria integral desarrollado en Laravel 12.

### Características Principales:
- Gestión financiera en REF y Bs (Tasa BCV).
- Chatbot académico interno optimizado.
- Roles: Estudiante, Profesor, Manager y Admin.
- Exportación de recaudación y carga masiva de notas.

### Documentación Completa:
Para más detalles sobre la arquitectura, seguridad y módulos, por favor consulta el archivo [DOCUMENTATION.md](./DOCUMENTATION.md).

### Instalación Rápida:
1. `composer install`
2. Configurar `.env` (Ver DOCUMENTATION.md)
3. `php artisan migrate --seed`
4. `php artisan storage:link`
5. `php artisan serve`
