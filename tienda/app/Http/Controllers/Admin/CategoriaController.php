<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoriaManga;
use App\Models\CategoriaFigura;
use App\Models\CategoriaMerch;

/**
 * CategoriaController - Panel de Administración
 * 
 * Gestiona las categorías de los tres tipos de productos
 */
class CategoriaController extends Controller
{
    /**
     * Listar todas las categorías de un tipo
     */
    public function index($tipo)
    {
        $modelo = $this->getModelo($tipo);
        $categorias = $modelo::withCount($this->getRelacion($tipo))->get();
        
        return view('admin.categorias.index', compact('categorias', 'tipo'));
    }

    /**
     * Formulario de creación
     */
    public function create($tipo)
    {
        return view('admin.categorias.create', compact('tipo'));
    }

    /**
     * Guardar nueva categoría
     */
    public function store(Request $request, $tipo)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);
        
        $modelo = $this->getModelo($tipo);
        $modelo::create($validated);
        
        return redirect()
            ->route('admin.categorias.index', $tipo)
            ->with('success', 'Categoría creada correctamente.');
    }

    /**
     * Formulario de edición
     */
    public function edit($tipo, $id)
    {
        $modelo = $this->getModelo($tipo);
        $categoria = $modelo::findOrFail($id);
        
        return view('admin.categorias.edit', compact('categoria', 'tipo'));
    }

    /**
     * Actualizar categoría
     */
    public function update(Request $request, $tipo, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);
        
        $modelo = $this->getModelo($tipo);
        $categoria = $modelo::findOrFail($id);
        $categoria->update($validated);
        
        return redirect()
            ->route('admin.categorias.index', $tipo)
            ->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Eliminar categoría
     */
    public function destroy($tipo, $id)
    {
        $modelo = $this->getModelo($tipo);
        $categoria = $modelo::findOrFail($id);
        
        // Verificar si tiene productos asociados
        $relacion = $this->getRelacion($tipo);
        if ($categoria->$relacion()->count() > 0) {
            return back()->with('error', 'No se puede eliminar: tiene productos asociados.');
        }
        
        $categoria->delete();
        
        return redirect()
            ->route('admin.categorias.index', $tipo)
            ->with('success', 'Categoría eliminada correctamente.');
    }

    /**
     * Obtener el modelo según el tipo
     */
    private function getModelo($tipo)
    {
        return match($tipo) {
            'manga' => CategoriaManga::class,
            'figura' => CategoriaFigura::class,
            'merch' => CategoriaMerch::class,
            default => abort(404)
        };
    }

    /**
     * Obtener el nombre de la relación con productos
     */
    private function getRelacion($tipo)
    {
        return match($tipo) {
            'manga' => 'mangas',
            'figura' => 'figuras',
            'merch' => 'merchs',
            default => abort(404)
        };
    }
}
