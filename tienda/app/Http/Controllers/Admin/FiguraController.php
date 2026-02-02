<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Figura;
use App\Models\CategoriaFigura;
use App\Models\Imagen;
use Illuminate\Support\Facades\Storage;

/**
 * FiguraController - Panel de Administración
 * 
 * CRUD completo para gestionar figuras coleccionables
 */
class FiguraController extends Controller
{
    /**
     * Listar todas las figuras
     */
    public function index(Request $request)
    {
        $query = Figura::with('categoria', 'imagenes');
        
        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }
        
        $orden = $request->get('orden', 'created_at');
        $direccion = $request->get('dir', 'desc');
        $query->orderBy($orden, $direccion);
        
        $figuras = $query->paginate(10)->withQueryString();
        
        return view('admin.figuras.index', compact('figuras'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $categorias = CategoriaFigura::all();
        return view('admin.figuras.create', compact('categorias'));
    }

    /**
     * Guardar nueva figura
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_figura_id' => 'required|exists:categorias_figura,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        
        $figura = Figura::create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'precio' => $validated['precio'],
            'stock' => $validated['stock'],
            'disponibilidad' => $validated['stock'] > 0,
            'categoria_figura_id' => $validated['categoria_figura_id'],
        ]);
        
        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('productos/figuras', 'public');
            Imagen::create([
                'ruta' => 'storage/' . $path,
                'es_principal' => true,
                'imageable_type' => Figura::class,
                'imageable_id' => $figura->id,
            ]);
        }
        
        return redirect()
            ->route('admin.figuras.index')
            ->with('success', 'Figura "' . $figura->nombre . '" creada correctamente.');
    }

    /**
     * Mostrar detalle (redirige a edit)
     */
    public function show(Figura $figura)
    {
        return redirect()->route('admin.figuras.edit', $figura);
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Figura $figura)
    {
        $categorias = CategoriaFigura::all();
        $figura->load('imagenes');
        return view('admin.figuras.edit', compact('figura', 'categorias'));
    }

    /**
     * Actualizar figura
     */
    public function update(Request $request, Figura $figura)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_figura_id' => 'required|exists:categorias_figura,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        
        $figura->update([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'precio' => $validated['precio'],
            'stock' => $validated['stock'],
            'disponibilidad' => $validated['stock'] > 0,
            'categoria_figura_id' => $validated['categoria_figura_id'],
        ]);
        
        if ($request->hasFile('imagen')) {
            $imagenAnterior = $figura->imagenes()->where('es_principal', true)->first();
            if ($imagenAnterior) {
                Storage::disk('public')->delete(str_replace('storage/', '', $imagenAnterior->ruta));
                $imagenAnterior->delete();
            }
            
            $path = $request->file('imagen')->store('productos/figuras', 'public');
            Imagen::create([
                'ruta' => 'storage/' . $path,
                'es_principal' => true,
                'imageable_type' => Figura::class,
                'imageable_id' => $figura->id,
            ]);
        }
        
        return redirect()
            ->route('admin.figuras.index')
            ->with('success', 'Figura "' . $figura->nombre . '" actualizada correctamente.');
    }

    /**
     * Eliminar figura
     */
    public function destroy(Figura $figura)
    {
        $nombre = $figura->nombre;
        
        foreach ($figura->imagenes as $imagen) {
            Storage::disk('public')->delete(str_replace('storage/', '', $imagen->ruta));
            $imagen->delete();
        }
        
        $figura->delete();
        
        return redirect()
            ->route('admin.figuras.index')
            ->with('success', 'Figura "' . $nombre . '" eliminada correctamente.');
    }
}
