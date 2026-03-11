<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->decimal('eval1', 8, 2)->nullable()->after('course_id');
            $table->decimal('eval2', 8, 2)->nullable()->after('eval1');
            $table->decimal('eval3', 8, 2)->nullable()->after('eval2');
            $table->decimal('eval4', 8, 2)->nullable()->after('eval3');
            $table->decimal('grade', 8, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropColumn(['eval1', 'eval2', 'eval3', 'eval4']);
            $table->decimal('grade', 8, 2)->nullable(false)->change();
        });
    }
};
