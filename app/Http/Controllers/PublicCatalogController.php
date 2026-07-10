<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanItem;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // <--- AGREGAMOS LA FACHADA AUTH

class PublicCatalogController extends Controller
{
    public function index(Request $request)
    {
        // 1. Traer todas las categorías para armar los botones del filtro
        $categories = \App\Models\Category::all();

        // 2. Iniciar la consulta base
        $query = Resource::where('status', 'active')
            ->where('available_quantity', '>', 0)
            ->with('category');

        // 3. Aplicar el filtro si viene en la URL
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // 4. Paginar y mantener el parámetro de la URL en los botones de "Siguiente"
        $resources = $query->latest()->paginate(12)->withQueryString();

        $listCount = count(session()->get('request_list', []));

        return view('welcome', compact('resources', 'listCount', 'categories'));
    }

    public function addToList(Request $request, Resource $resource)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $resource->available_quantity
        ]);

        $list = session()->get('request_list', []);

        // Si ya está en la lista, actualizamos la cantidad
        $list[$resource->id] = [
            'name' => $resource->name,
            'internal_code' => $resource->internal_code,
            'quantity' => $request->quantity,
            'max_available' => $resource->available_quantity
        ];

        session()->put('request_list', $list);

        return back()->with('success', 'Recurso agregado a tu solicitud.');
    }

    public function removeFromList($id)
    {
        $list = session()->get('request_list', []);
        
        if(isset($list[$id])) {
            unset($list[$id]);
            session()->put('request_list', $list);
        }

        return back()->with('success', 'Recurso eliminado de la lista.');
    }

    public function viewList()
    {
        $list = session()->get('request_list', []);
        return view('public.request-list', compact('list'));
    }

    // RENOMBRADO A "submitRequest" PARA COINCIDIR CON WEB.PHP
    public function submitRequest(Request $request)
    {
        // 1. Ya solo validamos las notas, porque el nombre nos lo da Google
        $request->validate([
            'notes' => 'nullable|string'
        ]);

        $list = session()->get('request_list', []);

        if(empty($list)) {
            return back()->withErrors(['error' => 'Tu lista de solicitud está vacía.']);
        }

        // 2. Extraer al usuario mágicamente desde la sesión
        $user = Auth::user();

        // 3. Dividir el string completo de Google ("Juan Perez") en Nombre y Apellido
        $nameParts = explode(' ', $user->name, 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? ''; // Si el usuario no tiene apellido, lo deja en blanco para que no falle

        DB::transaction(function () use ($request, $list, $firstName, $lastName) {
            // 4. Crear la cabecera del préstamo inyectando los datos de Google
            $loan = Loan::create([
                'applicant_name' => $firstName,
                'applicant_last_name' => $lastName,
                'notes' => $request->notes,
                'status' => 'pending'
            ]);

            // 5. Crear los detalles
            foreach ($list as $resourceId => $item) {
                LoanItem::create([
                    'loan_id' => $loan->id,
                    'resource_id' => $resourceId,
                    'quantity' => $item['quantity']
                ]);
            }
        });

        // Limpiar la sesión
        session()->forget('request_list');

        return redirect()->route('catalog.index')->with('success', '¡Tu solicitud ha sido enviada! Por favor, acércate al administrador para su aprobación.');
    }
}