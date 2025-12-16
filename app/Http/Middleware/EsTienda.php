<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EsTienda
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para acceder.');
        }

        if (!auth()->user()->isTienda()) {
            return redirect()->route('tienda.home')
                ->with('error', 'No tienes acceso al panel de tienda.');
        }

        return $next($request);
    }
}
