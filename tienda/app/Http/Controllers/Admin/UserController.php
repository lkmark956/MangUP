<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

/**
 * UserController - Panel de Administración
 * 
 * CRUD para gestionar usuarios del sistema
 */
class UserController extends Controller
{
    /**
     * Listar todos los usuarios
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        if ($request->filled('buscar')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->buscar . '%')
                  ->orWhere('email', 'like', '%' . $request->buscar . '%');
            });
        }
        
        // Filtro por tipo
        if ($request->filled('tipo')) {
            if ($request->tipo == 'admin') {
                $query->where('is_admin', true);
            } else {
                $query->where('is_admin', false);
            }
        }
        
        $usuarios = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        return view('admin.usuarios.create');
    }

    /**
     * Guardar nuevo usuario
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'is_admin' => 'boolean',
        ]);
        
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => $request->has('is_admin'),
        ]);
        
        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Formulario de edición
     */
    public function edit(User $usuario)
    {
        return view('admin.usuarios.edit', compact('usuario'));
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $usuario->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'is_admin' => 'boolean',
        ]);
        
        $usuario->name = $validated['name'];
        $usuario->email = $validated['email'];
        $usuario->is_admin = $request->has('is_admin');
        
        if ($request->filled('password')) {
            $usuario->password = Hash::make($validated['password']);
        }
        
        $usuario->save();
        
        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Eliminar usuario
     */
    public function destroy(User $usuario)
    {
        // No permitir eliminarse a sí mismo
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }
        
        $usuario->delete();
        
        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }

    /**
     * Cambiar estado admin de un usuario
     */
    public function toggleAdmin(User $usuario)
    {
        // No permitir quitarse admin a sí mismo
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes cambiar tu propio estado de administrador.');
        }
        
        $usuario->is_admin = !$usuario->is_admin;
        $usuario->save();
        
        $mensaje = $usuario->is_admin 
            ? 'Usuario promovido a administrador.' 
            : 'Permisos de administrador revocados.';
        
        return back()->with('success', $mensaje);
    }
}
