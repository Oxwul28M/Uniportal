<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userRole = strtolower(trim($user->role));
        
        // Split by comma or pipe, and trim each role
        $allowedRoles = array_map('trim', explode(',', str_replace('|', ',', strtolower($roles))));

        // Admin always has access to everything for management purposes
        if ($userRole === 'admin') {
            return $next($request);
        }

        if (!in_array($userRole, $allowedRoles)) {
            \Illuminate\Support\Facades\Log::warning("ACCESO DENEGADO (403): User {$user->email} [Role: {$userRole}] attempted to access " . $request->fullUrl() . ". Required: " . json_encode($allowedRoles));
            abort(403, 'Acceso denegado. No tienes permisos para esta sección.');
        }

        return $next($request);
    }
}
