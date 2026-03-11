<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 2. Tabla de Cursos
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('teacher_name');
            $table->timestamps();
        });

        // 3. Tabla de Calificaciones
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->decimal('grade', 5, 2);
            $table->string('period'); // Ej: 2026-I
            $table->timestamps();
        });

        // 4. Historial de Tasas BCV
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->decimal('rate', 15, 2);
            $table->timestamp('fetched_at');
            $table->timestamps();
        });

        // 5. Registro de Pagos
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->decimal('amount_bs', 15, 2);
            $table->decimal('amount_usd', 15, 2);
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->string('receipt_image')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portal_master_schema');
    }
};
