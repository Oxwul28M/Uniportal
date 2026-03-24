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
        Schema::table('courses', function (Blueprint $table) {
            $table->string('schedule_day')->nullable()->after('section'); // Lunes, Martes, etc.
            $table->string('schedule_time')->nullable()->after('schedule_day'); // 08:00 AM - 10:00 AM
            $table->string('room')->nullable()->after('schedule_time'); // AULA 101
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['schedule_day', 'schedule_time', 'room']);
        });
    }
};
