<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Oferta;
use App\Models\Manga;
use App\Models\Figura;
use App\Models\Merch;

/**
 * OfertaController - Panel de Administración
 * 
 * Maneja todas las operaciones CRUD para las ofertas
 */
class OfertaController extends Controller
{
    /**
     * Listar todas las ofertas
     */
    public function index(Request $request)
    {
        $query = Oferta::query();
        
        // Filtro por estado (activa/inactiva)
        if ($request->filled('estado')) {
            $query->where('activa', $request->estado === 'activa');
        }
        
        // Búsqueda por nombre
        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }
        
        $ofertas = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        
        return view('admin.ofertas.index', compact('ofertas'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $mangas = Manga::select('id', 'nombre')->orderBy('nombre')->get();
        $figuras = Figura::select('id', 'nombre')->orderBy('nombre')->get();
        $merchs = Merch::select('id', 'nombre')->orderBy('nombre')->get();
        
        return view('admin.ofertas.create', compact('mangas', 'figuras', 'merchs'));
    }

    /**
     * Guardar nueva oferta
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo_descuento' => 'required|in:porcentaje,cantidad_fija',
            'valor_descuento' => 'required|numeric|min:0.01',
            'aplica_a' => 'required|in:todos,manga,figura,merch,producto_especifico',
            'producto_id' => 'required_if:aplica_a,producto_especifico|nullable|integer',
            'tipo_producto' => 'required_if:aplica_a,producto_especifico|nullable|in:manga,figura,merch',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'activa' => 'boolean',
        ]);
        
        // Validación adicional para porcentaje
        if ($validated['tipo_descuento'] === 'porcentaje' && $validated['valor_descuento'] > 100) {
            return back()->withErrors(['valor_descuento' => 'El porcentaje no puede ser mayor a 100%'])->withInput();
        }
        
        $validated['activa'] = $request->has('activa');
        
        // Limpiar campos si no aplica a producto específico
        if ($validated['aplica_a'] !== 'producto_especifico') {
            $validated['producto_id'] = null;
            $validated['tipo_producto'] = null;
        }
        
        Oferta::create($validated);
        
        return redirect()->route('admin.ofertas.index')
            ->with('success', 'Oferta creada correctamente');
    }

    /**
     * Mostrar detalles de una oferta
     */
    public function show(Oferta $oferta)
    {
        return view('admin.ofertas.show', compact('oferta'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Oferta $oferta)
    {
        $mangas = Manga::select('id', 'nombre')->orderBy('nombre')->get();
        $figuras = Figura::select('id', 'nombre')->orderBy('nombre')->get();
        $merchs = Merch::select('id', 'nombre')->orderBy('nombre')->get();
        
        return view('admin.ofertas.edit', compact('oferta', 'mangas', 'figuras', 'merchs'));
    }

    /**
     * Actualizar oferta
     */
    public function update(Request $request, Oferta $oferta)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo_descuento' => 'required|in:porcentaje,cantidad_fija',
            'valor_descuento' => 'required|numeric|min:0.01',
            'aplica_a' => 'required|in:todos,manga,figura,merch,producto_especifico',
            'producto_id' => 'required_if:aplica_a,producto_especifico|nullable|integer',
            'tipo_producto' => 'required_if:aplica_a,producto_especifico|nullable|in:manga,figura,merch',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'activa' => 'boolean',
        ]);
        
        // Validación adicional para porcentaje
        if ($validated['tipo_descuento'] === 'porcentaje' && $validated['valor_descuento'] > 100) {
            return back()->withErrors(['valor_descuento' => 'El porcentaje no puede ser mayor a 100%'])->withInput();
        }
        
        $validated['activa'] = $request->has('activa');
        
        // Limpiar campos si no aplica a producto específico
        if ($validated['aplica_a'] !== 'producto_especifico') {
            $validated['producto_id'] = null;
            $validated['tipo_producto'] = null;
        }
        
        $oferta->update($validated);
        
        return redirect()->route('admin.ofertas.index')
            ->with('success', 'Oferta actualizada correctamente');
    }

    /**
     * Eliminar oferta
     */
    public function destroy(Oferta $oferta)
    {
        $oferta->delete();
        
        return redirect()->route('admin.ofertas.index')
            ->with('success', 'Oferta eliminada correctamente');
    }
}
