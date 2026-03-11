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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');                          // Título de la noticia o evento
            $table->text('content');                          // El cuerpo del mensaje
            $table->string('category');                       // 'noticia' o 'evento'
            $table->string('image_url')->nullable();          // URL de imagen opcional
            $table->date('event_date')->nullable();           // Fecha del evento (solo para eventos)
            $table->string('event_location')->nullable();     // Lugar del evento
            $table->boolean('is_published')->default(true);  // Control de visibilidad
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Quién lo publicó
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
