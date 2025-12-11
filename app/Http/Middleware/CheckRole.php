<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (empty($roles)) {
            return $next($request);
        }

        foreach ($roles as $role) {
            if ($user->hasRole($role) || $user->rol === $role) {
                return $next($request);
            }
        }

        if ($user->hasRole('admin') || $user->rol === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No tienes permisos para acceder a esa sección.');
        }

        return redirect()->route('tienda.home')
            ->with('error', 'No tienes permisos para acceder a esa sección.');
    }
}
