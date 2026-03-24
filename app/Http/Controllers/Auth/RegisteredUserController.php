<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student', // Force student role for public registration
            'status' => 'pending', // All public registrations start as pending
        ]);

        // Automatically assign registration fee
        $feeName = 'Cuota de Inscripción';
        $fee = \Illuminate\Support\Facades\DB::table('fees')->where('name', $feeName)->first();
        if (!$fee) {
            $feeId = \Illuminate\Support\Facades\DB::table('fees')->insertGetId([
                'name' => $feeName,
                'price_usd' => 20.00, // Default price
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $feeAmount = 20.00;
        } else {
            $feeId = $fee->id;
            $feeAmount = $fee->price_usd;
        }

        \Illuminate\Support\Facades\DB::table('debts')->insert([
            'user_id' => $user->id,
            'fee_id' => $feeId,
            'amount_usd' => $feeAmount,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        event(new Registered($user));

        // Auth::login($user); // Comentado para evitar login automático

        return redirect()->route('register.pending');
    }
}
