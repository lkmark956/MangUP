<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Direccion;

class CuentaController extends Controller
{
    /**
     * Mostrar la sección de datos personales
     */
    public function datosPersonales()
    {
        $user = Auth::user();
        return view('cuenta.datos-personales', compact('user'));
    }

    /**
     * Actualizar datos personales
     */
    public function actualizarDatos(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ], [
            'name.required' => 'El nombre es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.unique' => 'Este email ya está registrado',
        ]);

        $user->update($validated);

        return back()->with('success', 'Información actualizada correctamente');
    }

    /**
     * Cambiar contraseña
     */
    public function cambiarPassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'password_actual' => ['required'],
            'password_nueva' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'different:password_actual',
                'regex:/[A-Z]/',
                'regex:/[0-9].*[0-9].*[0-9]/',
            ],
        ], [
            'password_actual.required' => 'Debes ingresar tu contraseña actual',
            'password_nueva.required' => 'Debes ingresar una nueva contraseña',
            'password_nueva.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password_nueva.different' => 'La nueva contraseña debe ser diferente a la actual',
            'password_nueva.regex' => 'La contraseña debe contener al menos una mayúscula y 3 números',
            'password_nueva.confirmed' => 'Las contraseñas no coinciden',
        ]);

        // Verificar que la contraseña actual sea correcta
        if (!Hash::check($request->password_actual, $user->password)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual es incorrecta']);
        }

        // Actualizar contraseña
        $user->update([
            'password' => Hash::make($validated['password_nueva']),
        ]);

        return back()->with('success', 'Contraseña actualizada correctamente');
    }

    /**
     * Mostrar mis pedidos
     */
    public function misPedidos()
    {
        $user = Auth::user();
        $pedidos = [];

        return view('cuenta.pedidos', compact('user', 'pedidos'));
    }

    /**
     * Mostrar direcciones
     */
    public function direcciones()
    {
        $user = Auth::user();
        $direcciones = $user->direcciones()->get();

        return view('cuenta.direcciones', compact('user', 'direcciones'));
    }

    /**
     * Agregar nueva dirección
     */
    public function agregarDireccion(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'calle' => ['required', 'string', 'max:255'],
            'numero' => ['required', 'string', 'max:20'],
            'piso' => ['nullable', 'string', 'max:20'],
            'puerta' => ['nullable', 'string', 'max:20'],
            'ciudad' => ['required', 'string', 'max:100'],
            'provincia' => ['required', 'string', 'max:100'],
            'codigo_postal' => ['required', 'string', 'max:10'],
            'pais' => ['required', 'string', 'max:100'],
            'es_default' => ['nullable', 'in:on,true,1'],
        ], [
            'nombre.required' => 'El nombre de la dirección es obligatorio',
            'calle.required' => 'La calle es obligatoria',
            'numero.required' => 'El número es obligatorio',
            'ciudad.required' => 'La ciudad es obligatoria',
            'provincia.required' => 'La provincia es obligatoria',
            'codigo_postal.required' => 'El código postal es obligatorio',
            'pais.required' => 'El país es obligatorio',
        ]);

        // Convertir el checkbox a booleano
        $validated['es_default'] = $request->has('es_default') ? true : false;

        // Si es predeterminada, desmarcar las otras
        if ($validated['es_default']) {
            $user->direcciones()->update(['es_default' => false]);
        }

        // Crear la dirección
        $user->direcciones()->create($validated);

        return back()->with('success', 'Dirección agregada correctamente');
    }

    /**
     * Editar dirección
     */
    public function editarDireccion($id)
    {
        $user = Auth::user();
        $direccion = $user->direcciones()->find($id);

        if (!$direccion) {
            return back()->with('error', 'Dirección no encontrada');
        }

        return view('cuenta.editar-direccion', compact('direccion'));
    }

    /**
     * Actualizar dirección
     */
    public function actualizarDireccion(Request $request, $id)
    {
        $user = Auth::user();
        $direccion = $user->direcciones()->find($id);

        if (!$direccion) {
            return back()->with('error', 'Dirección no encontrada');
        }

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'calle' => ['required', 'string', 'max:255'],
            'numero' => ['required', 'string', 'max:20'],
            'piso' => ['nullable', 'string', 'max:20'],
            'puerta' => ['nullable', 'string', 'max:20'],
            'ciudad' => ['required', 'string', 'max:100'],
            'provincia' => ['required', 'string', 'max:100'],
            'codigo_postal' => ['required', 'string', 'max:10'],
            'pais' => ['required', 'string', 'max:100'],
            'es_default' => ['nullable', 'in:on,true,1'],
        ], [
            'nombre.required' => 'El nombre de la dirección es obligatorio',
            'calle.required' => 'La calle es obligatoria',
            'numero.required' => 'El número es obligatorio',
            'ciudad.required' => 'La ciudad es obligatoria',
            'provincia.required' => 'La provincia es obligatoria',
            'codigo_postal.required' => 'El código postal es obligatorio',
            'pais.required' => 'El país es obligatorio',
        ]);

        // Convertir el checkbox a booleano
        $validated['es_default'] = $request->has('es_default') ? true : false;

        // Si es predeterminada, desmarcar las otras
        if ($validated['es_default']) {
            $user->direcciones()->where('id', '!=', $id)->update(['es_default' => false]);
        }

        // Actualizar la dirección
        $direccion->update($validated);

        return redirect()->route('cuenta.direcciones')->with('success', 'Dirección actualizada correctamente');
    }

    /**
     * Eliminar dirección
     */
    public function eliminarDireccion($id)
    {
        $user = Auth::user();
        $direccion = $user->direcciones()->find($id);

        if (!$direccion) {
            return back()->with('error', 'Dirección no encontrada');
        }

        $direccion->delete();

        return back()->with('success', 'Dirección eliminada correctamente');
    }
}
