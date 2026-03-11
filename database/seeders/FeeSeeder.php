<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('fees')->insert([
            ['name' => 'Inscripción Semestre 2026-I', 'price_usd' => 80.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mensualidad Marzo', 'price_usd' => 45.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mensualidad Abril', 'price_usd' => 45.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Derecho a Examen de Reparación', 'price_usd' => 15.00, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Also ensure we have an initial exchange rate
        DB::table('exchange_rates')->insert([
            'rate' => 61.45,
            'fetched_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
