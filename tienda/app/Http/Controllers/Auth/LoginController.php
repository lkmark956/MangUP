<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'El email es obligatorio',
            'email.email' => 'Introduce un email válido',
            'password.required' => 'La contraseña es obligatoria',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Verificar si hay un producto pendiente para agregar al carrito
            if ($request->session()->has('producto_pendiente_carrito')) {
                $productoPendiente = $request->session()->get('producto_pendiente_carrito');
                
                // Agregar el producto al carrito
                $carrito = $request->session()->get('carrito', []);
                $clave = $productoPendiente['tipo'] === 'merch' && $productoPendiente['variante_id'] 
                    ? "{$productoPendiente['tipo']}_{$productoPendiente['id']}_{$productoPendiente['variante_id']}"
                    : "{$productoPendiente['tipo']}_{$productoPendiente['id']}";
                
                if (isset($carrito[$clave])) {
                    $carrito[$clave]['cantidad'] += $productoPendiente['cantidad'];
                } else {
                    $carrito[$clave] = [
                        'id' => $productoPendiente['id'],
                        'tipo' => $productoPendiente['tipo'],
                        'cantidad' => $productoPendiente['cantidad'],
                        'variante_id' => $productoPendiente['variante_id'] ?? null
                    ];
                }
                
                $request->session()->put('carrito', $carrito);
                $request->session()->forget('producto_pendiente_carrito');
                
                // Redirigir al carrito
                return redirect()->route('carrito.index')
                    ->with('success', '¡Bienvenido de nuevo, ' . Auth::user()->name . '! Tu producto ha sido agregado al carrito.');
            }

            // Redirigir a la página anterior o al home
            return redirect()->intended(route('home'))
                ->with('success', '¡Bienvenido de nuevo, ' . Auth::user()->name . '!');
        }

        return back()
            ->withErrors(['email' => 'Las credenciales no coinciden con nuestros registros.'])
            ->withInput($request->only('email', 'remember'));
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Has cerrado sesión correctamente.');
    }
}
