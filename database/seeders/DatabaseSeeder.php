<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear los 4 Usuarios con sus Roles
        $admin = User::create([
            'name' => 'Admin Sistema',
            'email' => 'admin@uni.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $manager = User::create([
            'name' => 'Manager Academia',
            'email' => 'manager@uni.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'status' => 'active',
        ]);

        $teacher = User::create([
            'name' => 'Profesor Arreaza',
            'email' => 'teacher@uni.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'status' => 'active',
        ]);

        $student = User::create([
            'name' => 'Estudiante Activo',
            'email' => 'student@uni.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
        ]);

        // 2. Crear una Noticia
        \App\Models\Post::create([
            'title' => 'Bienvenidos al nuevo Portal Universitario',
            'content' => 'Estamos felices de lanzar nuestro nuevo portal con tecnología Supabase. Explora todas las nuevas funciones.',
            'category' => 'noticia',
            'is_published' => true,
            'user_id' => $admin->id,
            'created_at' => now(),
        ]);

        \App\Models\Post::create([
            'title' => 'Mantenimiento del Sistema Programado',
            'content' => 'El portal estará en mantenimiento el próximo domingo para mejoras de rendimiento.',
            'category' => 'noticia',
            'is_published' => true,
            'user_id' => $admin->id,
            'created_at' => now()->subDay(),
        ]);

        // 3. Crear un Evento
        \App\Models\Post::create([
            'title' => 'Taller de Desarrollo con Laravel',
            'content' => 'Únete a nuestro taller intensivo de Laravel y aprende a construir aplicaciones modernas.',
            'category' => 'evento',
            'event_date' => now()->addDays(7),
            'event_location' => 'Laboratorio de Computación 2',
            'is_published' => true,
            'user_id' => $manager->id,
            'created_at' => now(),
        ]);

        // 4. Crear un Curso de prueba
        $courseId = DB::table('courses')->insertGetId([
            'name' => 'Programación Web con Laravel',
            'code' => 'LAR-12',
            'teacher_name' => $teacher->name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 5. Crear una Calificación (Nota)
        DB::table('grades')->insert([
            'user_id' => $student->id,
            'course_id' => $courseId,
            'grade' => 18.5,
            'period' => '2026-I',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 6. Tasa BCV (Dólar hoy)
        DB::table('exchange_rates')->updateOrInsert(
            ['id' => 1],
            [
                'rate' => 36.50,
                'fetched_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 7. Crear Aranceles (Fees) por defecto
        $fees = [
            ['name' => 'Mensualidad (Marzo 2026)', 'price_usd' => 45.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Inscripción Semestre 2026-I', 'price_usd' => 20.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Constancia de Estudios', 'price_usd' => 5.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Derecho a Grado', 'price_usd' => 150.00, 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($fees as $fee) {
            DB::table('fees')->insert($fee);
        }
    }
}