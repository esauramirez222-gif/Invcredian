<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Resource;
use Illuminate\Http\Request;
use App\Http\Requests\StoreResourceRequest;
use App\Http\Requests\UpdateResourceRequest;
use App\Models\Movement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ResourceController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        // 1. Traer todas las categorías para los botones
        $categories = \App\Models\Category::all();

        // 2. Iniciar la consulta base (Carga ansiosa de categorías)
        $query = \App\Models\Resource::with('category');

        // 3. Aplicar el filtro si se seleccionó una categoría
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // 4. Paginar manteniendo los parámetros en la URL (para que no se borre el filtro al cambiar de página)
        $resources = $query->latest('id')->paginate(10)->withQueryString();
        
        return view('inventory.index', compact('resources', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('inventory.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // 1. Ejecutamos la validación (Laravel detendrá el proceso aquí si algo falla)
        $datosValidados = $request->validate([
            'name'           => 'required|string|max:255',
            'internal_code'  => [
                'nullable', 
                'string', 
                'max:50', 
                // Único, pero ignorando los que ya eliminamos (Soft Deletes)
                Rule::unique('resources', 'internal_code')->whereNull('deleted_at')
            ],
            'category_id'    => 'required|exists:categories,id',
            'total_quantity' => 'required|integer|min:1',
            'status'         => 'required|in:active,maintenance,inactive'
            // NOTA: Ajusta estos nombres según los inputs que tengas en tu formulario html
        ]);

        // 2. Guardamos en la base de datos
        DB::transaction(function () use ($request, $datosValidados) {
            $resource = Resource::create([
                ...$datosValidados, // Esparcimos el array de los datos que ya pasaron la validación
                'available_quantity' => $request->total_quantity, // Al iniciar, disponible = total
                'registration_date'  => now(), // <--- ESTA ES LA MAGIA QUE FALTABA
            ]);
        });

        // 3. Redirigimos de vuelta con un mensaje de éxito
        return redirect()->route('inventory.index')->with('success', 'Producto agregado correctamente al inventario.');
    }

    public function show(Resource $resource)
    {
        // No usaremos vista de detalle individual por ahora, todo estará en la tabla
    }

    public function edit(Resource $resource)
    {
        $categories = Category::all();
        return view('inventory.edit', compact('resource', 'categories'));
    }

    public function update(Request $request, Resource $resource)
    {
        // 1. Validamos los datos de entrada
        $datosValidados = $request->validate([
            'name'           => 'required|string|max:255',
            'internal_code'  => [
                'nullable', 
                'string', 
                'max:50', 
                // Único, ignorando al recurso actual Y a los eliminados
                Rule::unique('resources', 'internal_code')->ignore($resource->id)->whereNull('deleted_at')
            ],
            'category_id'    => 'required|exists:categories,id',
            'total_quantity' => 'required|integer|min:1',
            'status'         => 'required|in:active,maintenance,inactive'
        ]);

        // 2. MATEMÁTICAS DE INVENTARIO: 
        // Calculamos cuántos artículos se agregaron (o quitaron) del total original
        $diferencia = $datosValidados['total_quantity'] - $resource->total_quantity;
        
        // Se lo sumamos a la cantidad disponible actual
        $nuevaCantidadDisponible = $resource->available_quantity + $diferencia;

        // (Opcional pero recomendado): Evitar que la cantidad disponible quede en negativo
        // si intentas reducir el total por debajo de los equipos que ya tienes prestados.
        if ($nuevaCantidadDisponible < 0) {
            return back()->withErrors(['total_quantity' => 'No puedes reducir el total a una cantidad menor de los equipos que ya están prestados actualmente.']);
        }

        // Agregamos la cantidad disponible corregida a los datos que vamos a guardar
        $datosValidados['available_quantity'] = $nuevaCantidadDisponible;

        // 3. Guardamos todos los cambios sincronizados
        $resource->update($datosValidados);
        
        return redirect()->route('inventory.index')->with('success', 'Recurso actualizado correctamente.');
    }

    public function destroy(Resource $resource)
    {
        $resource->delete(); // Soft Delete
        return redirect()->route('inventory.index')->with('success', 'Recurso enviado a la papelera.');
    }
}