<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Merch;
use App\Models\CategoriaMerch;
use App\Models\Talla;
use App\Models\Color;
use App\Models\MerchVariante;
use App\Models\Imagen;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

/**
 * MerchController - Panel de Administración
 * 
 * CRUD para gestionar merchandising con variantes (talla/color)
 */
class MerchController extends Controller
{
    /**
     * Listar todo el merch
     */
    public function index(Request $request)
    {
        $query = Merch::with('categoria', 'imagenes', 'variantes');
        
        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }
        
        $orden = $request->get('orden', 'created_at');
        $direccion = $request->get('dir', 'desc');
        $query->orderBy($orden, $direccion);
        
        $merchs = $query->paginate(10)->withQueryString();
        
        return view('admin.merch.index', compact('merchs'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $categorias = CategoriaMerch::all();
        $tallas = Talla::all();
        $colores = Color::all();
        return view('admin.merch.create', compact('categorias', 'tallas', 'colores'));
    }

    /**
     * Guardar nuevo merch con variantes
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'categoria_merch_id' => 'required|exists:categorias_merch,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'variantes' => 'required|array|min:1',
            'variantes.*.talla_id' => 'required|exists:tallas,id',
            'variantes.*.color_id' => 'required|exists:colores,id',
            'variantes.*.stock' => 'required|integer|min:0',
        ]);
        
        DB::beginTransaction();
        try {
            // Crear el merch
            $merch = Merch::create([
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'],
                'precio' => $validated['precio'],
                'disponibilidad' => true,
                'categoria_merch_id' => $validated['categoria_merch_id'],
            ]);
            
            // Crear las variantes
            foreach ($validated['variantes'] as $variante) {
                MerchVariante::create([
                    'merch_id' => $merch->id,
                    'talla_id' => $variante['talla_id'],
                    'color_id' => $variante['color_id'],
                    'stock' => $variante['stock'],
                ]);
            }
            
            // Actualizar disponibilidad basándose en stock total
            $stockTotal = $merch->variantes()->sum('stock');
            $merch->update(['disponibilidad' => $stockTotal > 0]);
            
            // Guardar imagen
            if ($request->hasFile('imagen')) {
                $path = $request->file('imagen')->store('productos/merch', 'public');
                Imagen::create([
                    'ruta' => 'storage/' . $path,
                    'es_principal' => true,
                    'imageable_type' => Merch::class,
                    'imageable_id' => $merch->id,
                ]);
            }
            
            DB::commit();
            
            return redirect()
                ->route('admin.merch.index')
                ->with('success', 'Merch "' . $merch->nombre . '" creado correctamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error al crear el merch: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalle (redirige a edit)
     */
    public function show(Merch $merch)
    {
        return redirect()->route('admin.merch.edit', $merch);
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Merch $merch)
    {
        $categorias = CategoriaMerch::all();
        $tallas = Talla::all();
        $colores = Color::all();
        $merch->load('imagenes', 'variantes.talla', 'variantes.color');
        return view('admin.merch.edit', compact('merch', 'categorias', 'tallas', 'colores'));
    }

    /**
     * Actualizar merch
     */
    public function update(Request $request, Merch $merch)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'categoria_merch_id' => 'required|exists:categorias_merch,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'variantes' => 'required|array|min:1',
            'variantes.*.talla_id' => 'required|exists:tallas,id',
            'variantes.*.color_id' => 'required|exists:colores,id',
            'variantes.*.stock' => 'required|integer|min:0',
        ]);
        
        DB::beginTransaction();
        try {
            $merch->update([
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'],
                'precio' => $validated['precio'],
                'categoria_merch_id' => $validated['categoria_merch_id'],
            ]);
            
            // Eliminar variantes existentes y crear las nuevas
            $merch->variantes()->delete();
            
            foreach ($validated['variantes'] as $variante) {
                MerchVariante::create([
                    'merch_id' => $merch->id,
                    'talla_id' => $variante['talla_id'],
                    'color_id' => $variante['color_id'],
                    'stock' => $variante['stock'],
                ]);
            }
            
            // Actualizar disponibilidad
            $stockTotal = $merch->variantes()->sum('stock');
            $merch->update(['disponibilidad' => $stockTotal > 0]);
            
            // Nueva imagen
            if ($request->hasFile('imagen')) {
                $imagenAnterior = $merch->imagenes()->where('es_principal', true)->first();
                if ($imagenAnterior) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $imagenAnterior->ruta));
                    $imagenAnterior->delete();
                }
                
                $path = $request->file('imagen')->store('productos/merch', 'public');
                Imagen::create([
                    'ruta' => 'storage/' . $path,
                    'es_principal' => true,
                    'imageable_type' => Merch::class,
                    'imageable_id' => $merch->id,
                ]);
            }
            
            DB::commit();
            
            return redirect()
                ->route('admin.merch.index')
                ->with('success', 'Merch "' . $merch->nombre . '" actualizado correctamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar merch
     */
    public function destroy(Merch $merch)
    {
        $nombre = $merch->nombre;
        
        // Eliminar variantes
        $merch->variantes()->delete();
        
        // Eliminar imágenes
        foreach ($merch->imagenes as $imagen) {
            Storage::disk('public')->delete(str_replace('storage/', '', $imagen->ruta));
            $imagen->delete();
        }
        
        $merch->delete();
        
        return redirect()
            ->route('admin.merch.index')
            ->with('success', 'Merch "' . $nombre . '" eliminado correctamente.');
    }
}
