# 🎓 UniPortal

**A comprehensive, modern university management system built with Laravel 12.**

UniPortal streamlines academic and financial operations for educational institutions. It features a robust multi-role architecture, dual-currency financial tracking (with automated exchange rates), and an internal academic chatbot designed to improve the student experience.

![Demo](https://via.placeholder.com/800x400?text=Insert+Video+or+Screenshot+Here) <!-- You can replace this placeholder with a GIF or screenshot from your video! -->

### 🚀 Key Features
- **Financial Management:** Dual-currency tracking with automated and manual exchange rate integration.
- **Internal Academic Chatbot:** Local keyword-based intent matching to answer student queries instantly without external API costs.
- **Role-Based Access Control:** Distinct panels and permissions for Students, Teachers, Managers, and Admins.
- **Academic Tools:** Bulk grade uploading via Excel, dynamic event agendas, and comprehensive reporting.

### 📖 Documentation
For a detailed overview of the architecture, security practices, and core modules, please refer to the [DOCUMENTATION.md](./DOCUMENTATION.md).

### 🛠️ Quick Start

#### Requirements
- PHP 8.2+
- Composer
- Node.js & npm
- PostgreSQL (via Supabase)

#### Installation
```bash
# Clone the repository
git clone https://github.com/Oxwul28M/Uniportal.git
cd Uniportal

# Install PHP dependencies
composer install

# Install frontend dependencies and build assets
npm install && npm run build

# Setup environment variables
cp .env.example .env
# Make sure to configure your Supabase database credentials in the .env file

# Generate application key
php artisan key:generate

# Run migrations and seeders
php artisan migrate --seed

# Link storage
php artisan storage:link

# Start the local development server
php artisan serve
```

### 👨‍💻 Tech Stack
- **Backend:** Laravel 12 (PHP 8.2)
- **Database:** PostgreSQL (Supabase)
- **Frontend:** Tailwind CSS, Alpine.js, Vite

---
*If you like this project, consider leaving a ⭐ on the repository!*
