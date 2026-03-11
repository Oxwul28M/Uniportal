<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Allow active users and ALWAYS allow admins to prevent lockouts
            if ($user->status === 'active' || $user->role === 'admin') {
                return $next($request);
            }

            // If not active and not admin, boot them
            $status = $user->status;
            
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($status === 'pending') {
                return redirect()->route('register.pending')->with('status', 'Tu cuenta aún está en revisión. Un administrador debe aprobarla.');
            }

            $message = $status === 'suspended' ? 'Tu cuenta ha sido suspendida.' : 'Tu solicitud de registro ha sido rechazada.';
            return redirect()->route('login')->with('error', $message);
        }

        return $next($request);
    }
}
