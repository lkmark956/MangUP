<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\CategoriaManga;
use App\Models\Imagen;
use Illuminate\Support\Facades\Storage;

/**
 * MangaController - Panel de Administración
 * 
 * Este controlador maneja todas las operaciones CRUD para los mangas:
 * - index(): Lista todos los mangas con paginación y búsqueda
 * - create(): Muestra el formulario para crear un nuevo manga
 * - store(): Valida y guarda un nuevo manga en la BD
 * - edit(): Muestra el formulario para editar un manga existente
 * - update(): Valida y actualiza un manga en la BD
 * - destroy(): Elimina un manga de la BD
 */
class MangaController extends Controller
{
    /**
     * Listar todos los mangas
     * 
     * Muestra una tabla con todos los mangas, permite:
     * - Búsqueda por nombre
     * - Ordenamiento por columnas
     * - Paginación
     */
    public function index(Request $request)
    {
        $query = Manga::with('categoria', 'imagenes');
        
        // Búsqueda por nombre
        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }
        
        // Ordenamiento
        $orden = $request->get('orden', 'created_at');
        $direccion = $request->get('dir', 'desc');
        $query->orderBy($orden, $direccion);
        
        $mangas = $query->paginate(10)->withQueryString();
        
        return view('admin.mangas.index', compact('mangas'));
    }

    /**
     * Mostrar formulario de creación
     * 
     * Carga las categorías disponibles para el select
     */
    public function create()
    {
        $categorias = CategoriaManga::all();
        return view('admin.mangas.create', compact('categorias'));
    }

    /**
     * Guardar nuevo manga
     * 
     * 1. Valida los datos del formulario
     * 2. Crea el manga en la BD
     * 3. Si hay imagen, la guarda y crea el registro en la tabla imagenes
     * 4. Redirige con mensaje de éxito
     */
    public function store(Request $request)
    {
        // Validación de datos
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'autor' => 'nullable|string|max:255',
            'editorial' => 'nullable|string|max:255',
            'fecha_publicacion' => 'nullable|date',
            'numero_paginas' => 'nullable|integer|min:1',
            'isbn' => 'nullable|string|max:20',
            'numero_tomo' => 'nullable|integer|min:0',
            'categoria_manga_id' => 'required|exists:categorias_manga,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        
        // Crear el manga
        $manga = Manga::create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'precio' => $validated['precio'],
            'stock' => $validated['stock'],
            'disponibilidad' => $validated['stock'] > 0,
            'autor' => $validated['autor'] ?? null,
            'editorial' => $validated['editorial'] ?? null,
            'fecha_publicacion' => $validated['fecha_publicacion'] ?? null,
            'numero_paginas' => $validated['numero_paginas'] ?? null,
            'isbn' => $validated['isbn'] ?? null,
            'numero_tomo' => $validated['numero_tomo'] ?? null,
            'categoria_manga_id' => $validated['categoria_manga_id'],
        ]);
        
        // Guardar imagen si se subió
        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('productos/mangas', 'public');
            
            Imagen::create([
                'ruta' => 'storage/' . $path,
                'es_principal' => true,
                'imageable_type' => Manga::class,
                'imageable_id' => $manga->id,
            ]);
        }
        
        return redirect()
            ->route('admin.mangas.index')
            ->with('success', 'Manga "' . $manga->nombre . '" creado correctamente.');
    }

    /**
     * Mostrar detalle (redirige a edit)
     */
    public function show(Manga $manga)
    {
        return redirect()->route('admin.mangas.edit', $manga);
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Manga $manga)
    {
        $categorias = CategoriaManga::all();
        $manga->load('imagenes');
        return view('admin.mangas.edit', compact('manga', 'categorias'));
    }

    /**
     * Actualizar manga existente
     */
    public function update(Request $request, Manga $manga)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'autor' => 'nullable|string|max:255',
            'editorial' => 'nullable|string|max:255',
            'fecha_publicacion' => 'nullable|date',
            'numero_paginas' => 'nullable|integer|min:1',
            'isbn' => 'nullable|string|max:20',
            'numero_tomo' => 'nullable|integer|min:0',
            'categoria_manga_id' => 'required|exists:categorias_manga,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        
        $manga->update([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'precio' => $validated['precio'],
            'stock' => $validated['stock'],
            'disponibilidad' => $validated['stock'] > 0,
            'autor' => $validated['autor'] ?? null,
            'editorial' => $validated['editorial'] ?? null,
            'fecha_publicacion' => $validated['fecha_publicacion'] ?? null,
            'numero_paginas' => $validated['numero_paginas'] ?? null,
            'isbn' => $validated['isbn'] ?? null,
            'numero_tomo' => $validated['numero_tomo'] ?? null,
            'categoria_manga_id' => $validated['categoria_manga_id'],
        ]);
        
        // Nueva imagen
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            $imagenAnterior = $manga->imagenes()->where('es_principal', true)->first();
            if ($imagenAnterior) {
                Storage::disk('public')->delete(str_replace('storage/', '', $imagenAnterior->ruta));
                $imagenAnterior->delete();
            }
            
            $path = $request->file('imagen')->store('productos/mangas', 'public');
            Imagen::create([
                'ruta' => 'storage/' . $path,
                'es_principal' => true,
                'imageable_type' => Manga::class,
                'imageable_id' => $manga->id,
            ]);
        }
        
        return redirect()
            ->route('admin.mangas.index')
            ->with('success', 'Manga "' . $manga->nombre . '" actualizado correctamente.');
    }

    /**
     * Eliminar manga
     */
    public function destroy(Manga $manga)
    {
        $nombre = $manga->nombre;
        
        // Eliminar imágenes asociadas
        foreach ($manga->imagenes as $imagen) {
            Storage::disk('public')->delete(str_replace('storage/', '', $imagen->ruta));
            $imagen->delete();
        }
        
        $manga->delete();
        
        return redirect()
            ->route('admin.mangas.index')
            ->with('success', 'Manga "' . $nombre . '" eliminado correctamente.');
    }
}
