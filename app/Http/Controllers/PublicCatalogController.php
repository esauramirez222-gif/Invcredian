<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanItem;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Mail;
use App\Mail\LoanNotification;

class PublicCatalogController extends Controller
{
    public function index(Request $request)
    {
        $categories = \App\Models\Category::all();

        $query = Resource::where('status', 'active')
            ->where('available_quantity', '>', 0)
            ->with('category');

        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

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
        // 1. Obtenemos la lista actual del "carrito"
        $list = session()->get('request_list', []);
        
        // 2. Extraemos TODO el historial de préstamos del usuario actual
        $myLoans = Loan::where('user_id', Auth::id())
            ->with('items.resource') // Cargamos los detalles para poder mostrarlos
            ->latest()
            ->get();

        // 3. Pasamos ambas variables a la vista
        return view('public.request-list', compact('list', 'myLoans'));
    }

    public function submitRequest(Request $request)
    {
        $request->validate([
            'notes' => 'nullable|string'
        ]);

        $list = session()->get('request_list', []);

        if(empty($list)) {
            return back()->withErrors(['error' => 'Tu lista de solicitud está vacía.']);
        }

        $user = Auth::user();

        $nameParts = explode(' ', $user->name, 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? ''; 

        DB::transaction(function () use ($request, $list, $firstName, $lastName, $user) {
            
            // 4. ¡AQUÍ GUARDAMOS EL user_id PARA ENLAZARLO CON SU CUENTA!
            $loan = Loan::create([
                'user_id' => $user->id,
                'applicant_name' => $firstName,
                'applicant_last_name' => $lastName,
                'notes' => $request->notes,
                'status' => 'pending'
            ]);

            foreach ($list as $resourceId => $item) {
                LoanItem::create([
                    'loan_id' => $loan->id,
                    'resource_id' => $resourceId,
                    'quantity' => $item['quantity']
                ]);
            }
        });

        session()->forget('request_list');

        // Cargar los detalles para el correo y enviarlo a los admins
        $loan->load('items.resource');
        $admins = ['jaziel@credian.mx', 'leonardo@credian.mx'];
        Mail::to($admins)->send(new LoanNotification($loan, 'new_request'));

        return redirect()->route('catalog.index')->with('success', '¡Tu solicitud ha sido enviada! Por favor, acércate al administrador para su aprobación.');
    }
}