<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_report_payment()
    {
        $student = User::factory()->create([
            'role' => 'student',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $feeId = DB::table('fees')->insertGetId([
            'name' => 'Mensualidad Prueba',
            'price_usd' => 50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $debtId = DB::table('debts')->insertGetId([
            'user_id' => $student->id,
            'fee_id' => $feeId,
            'amount_usd' => 50,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('exchange_rates')->insert([
            'rate' => 40.00,
            'fetched_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($student)->post(route('student.payments.store'), [
            'reference' => 'TEST999888',
            'amount_bs' => 2000, // $50 * 40 = 2000
            'fee_id' => $debtId, // This acts as the dropdown value
        ]);

        $response->assertRedirect(route('student.payments.index'));
        $this->assertDatabaseHas('payments', [
            'reference' => 'TEST999888',
            'amount_usd' => 50,
            'fee_id' => $feeId,
            'status' => 'pending',
        ]);
        
        $this->assertDatabaseHas('debts', [
            'id' => $debtId,
            'status' => 'in_review',
        ]);
    }
}
