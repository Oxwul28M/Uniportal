<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Ruta pública (Landing page)
Route::get('/', [HomeController::class, 'index']);

// 2. Rutas protegidas por Autenticación
Route::middleware(['auth', 'verified', 'active'])->group(function () {

    // Dashboard con lógica de Roles
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    // ── Rutas del Perfil ──
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // ── Rutas de Estudiante ──
    Route::middleware(['role:student'])->prefix('student')->name('student.')->group(function () {
        Route::get('/grades', [StudentController::class, 'grades'])->name('grades');
        Route::get('/schedule', [StudentController::class, 'schedule'])->name('schedule');
        Route::get('/schedule/export', [StudentController::class, 'exportSchedule'])->name('schedule.export');
        Route::get('/documents', [StudentController::class, 'documents'])->name('documents');
        Route::post('/documents', [StudentController::class, 'storeDocument'])->name('documents.store');
        Route::delete('/documents/{id}', [StudentController::class, 'cancelDocument'])->name('documents.cancel');
        Route::get('/enrollment', [StudentController::class, 'enrollment'])->name('enrollment');
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    });

    // ── Rutas de Manager ──
    Route::middleware(['role:manager'])->prefix('manager')->name('manager.')->group(function () {
        Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [ManagerController::class, 'users'])->name('users.index');
        Route::post('/users', [ManagerController::class, 'store'])->name('users.store');
        Route::patch('/users/{id}', [ManagerController::class, 'update'])->name('users.update');
        Route::get('/payments', [ManagerController::class, 'payments'])->name('payments.index');
        Route::get('/reports', [ManagerController::class, 'reports'])->name('reports.index');
        Route::get('/reports/export', [ManagerController::class, 'export'])->name('reports.export');
        Route::get('/posts', [ManagerController::class, 'posts'])->name('posts.index');

        Route::post('/payments/{id}/approve', [PaymentController::class, 'approve'])->name('payments.approve');
        Route::post('/payments/{id}/reject', [PaymentController::class, 'reject'])->name('payments.reject');
        Route::post('/exchange-rates', [PaymentController::class, 'updateRate'])->name('exchange-rates.store');
        Route::post('/fees', [PaymentController::class, 'storeFee'])->name('fees.store');
        Route::post('/debts/assign', [ManagerController::class, 'assignDebts'])->name('debts.assign');
    });

    // ── Rutas de Profesor ──
    Route::middleware(['role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
        Route::get('/courses', [TeacherController::class, 'courses'])->name('courses.index');
        Route::get('/grading', [TeacherController::class, 'grading'])->name('grading.index');
        Route::get('/agenda', [TeacherController::class, 'agenda'])->name('agenda.index');

        Route::post('/grades/store', [TeacherController::class, 'storeGrades'])->name('grades.store');
        Route::get('/grades/export-template', [TeacherController::class, 'exportTemplate'])->name('grades.export');
        Route::post('/grades/import', [TeacherController::class, 'importGrades'])->name('grades.import');
    });

    // ── Rutas de Administrador ──
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::get('/posts', [AdminController::class, 'posts'])->name('posts.index');
        Route::get('/security', [AdminController::class, 'security'])->name('security.index');
        Route::get('/registration-requests', [AdminController::class, 'registrationRequests'])->name('requests.index');

        Route::post('/users', [AdminController::class, 'store'])->name('users.store');
        Route::patch('/users/{id}', [AdminController::class, 'update'])->name('users.update');
        Route::patch('/users/{id}/suspend', [AdminController::class, 'suspend'])->name('users.suspend');
        Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{id}/approve', [AdminController::class, 'approve'])->name('requests.approve');
        Route::post('/users/{id}/reject', [AdminController::class, 'reject'])->name('requests.reject');
        Route::post('/fees', [PaymentController::class, 'storeFee'])->name('fees.store');
        Route::post('/debts/assign', [AdminController::class, 'assignDebts'])->name('debts.assign');
    });

    // ── Rutas de Posts (Solo Admin y Manager) ──
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
        Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
        Route::patch('/posts/{post}/toggle', [PostController::class, 'toggle'])->name('posts.toggle');
    });

    Route::get('/api/bcv/update', [PaymentController::class, 'updateRateFromApi'])->name('api.bcv.update');

});

// Logout vía GET
Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout.get');

require __DIR__ . '/auth.php';