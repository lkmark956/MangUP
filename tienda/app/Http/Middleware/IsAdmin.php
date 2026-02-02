<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware IsAdmin
 * 
 * Este middleware verifica si el usuario autenticado tiene permisos de administrador.
 * Se aplica a todas las rutas del panel de administración (/admin/*).
 * 
 * Flujo:
 * 1. Verifica si hay un usuario autenticado (auth()->check())
 * 2. Verifica si ese usuario tiene is_admin = true
 * 3. Si ambas condiciones se cumplen, permite el acceso
 * 4. Si no, devuelve error 403 (Forbidden)
 */
class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado
        if (!auth()->check()) {
            // No está logueado -> redirigir a login
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }

        // Verificar si el usuario es administrador
        if (!auth()->user()->is_admin) {
            // Está logueado pero NO es admin -> Error 403
            abort(403, 'Acceso denegado. No tienes permisos de administrador.');
        }

        // El usuario es admin -> continuar con la petición
        return $next($request);
    }
}
