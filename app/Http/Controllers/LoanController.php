<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Movement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\LoanNotification;

class LoanController extends Controller
{
    public function index()
    {
        // Traemos todas las solicitudes ordenadas por las más recientes
        $loans = Loan::latest()->paginate(10);
        return view('loans.index', compact('loans'));
    }

    public function show(Loan $loan)
    {
        // Cargamos los items y sus recursos asociados para ver el detalle
        $loan->load('items.resource');
        return view('loans.show', compact('loan'));
    }

    public function approve(Request $request, Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return back()->with('error', 'Esta solicitud ya fue procesada.');
        }

        try {
            DB::transaction(function () use ($loan) {
                // 1. Validar y descontar stock
                foreach ($loan->items as $item) {
                    $resource = $item->resource;

                    if ($resource->available_quantity < $item->quantity) {
                        // Lanzamos un error que cancelará toda la transacción
                        throw new \Exception("Stock insuficiente para el recurso: {$resource->name}");
                    }

                    // Descontamos el stock
                    $resource->decrement('available_quantity', $item->quantity);

                    // Registramos el movimiento
                    Movement::create([
                        'resource_id' => $resource->id,
                        'user_id' => Auth::id(),
                        'type' => 'loan',
                        'quantity' => -$item->quantity, // Negativo porque sale del inventario
                        'notes' => "Préstamo aprobado a {$loan->applicant_name} {$loan->applicant_last_name}"
                    ]);
                }

                // 2. Actualizar la cabecera de la solicitud
                $loan->update([
                    'status' => 'approved',
                    'reviewer_id' => Auth::id()
                ]);
            });

            // Enviar correo de aprobación al usuario
            $loan->load('user', 'items.resource');
            Mail::to($loan->user->email)->send(new LoanNotification($loan, 'approved'));

            return redirect()->route('loans.index')->with('success', 'Préstamo aprobado. El stock ha sido descontado.');

        } catch (\Exception $e) {
            // Si hubo error de stock, regresamos con el mensaje
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Request $request, Loan $loan)
    {

        if ($loan->status !== 'pending') {
            return back()->with('error', 'Esta solicitud ya fue procesada.');
        }

        $loan->update([
            'status' => 'rejected',
            'reviewer_id' => Auth::id(),
            'reviewer_notes' => $request->reviewer_notes
        ]);

        // Enviar correo de rechazo al usuario
        $loan->load('user', 'items.resource');
        Mail::to($loan->user->email)->send(new LoanNotification($loan, 'rejected'));

        return redirect()->route('loans.index')->with('success', 'La solicitud ha sido rechazada.');
    }

    public function returnLoan(Request $request, Loan $loan)
    {
        // 1. Validación de seguridad
        if ($loan->status !== 'approved') {
            return back()->with('error', 'Solo se pueden devolver préstamos que están aprobados.');
        }

        try {
            DB::transaction(function () use ($loan) {
                // 2. Recorrer los ítems para devolver el stock
                foreach ($loan->items as $item) {
                    $resource = $item->resource;

                    // Incrementamos el stock disponible
                    $resource->increment('available_quantity', $item->quantity);

                    // Registramos el movimiento de entrada
                    Movement::create([
                        'resource_id' => $resource->id,
                        'user_id' => Auth::id(),
                        'type' => 'return',
                        'quantity' => $item->quantity, // Positivo, porque regresa al inventario
                        'notes' => "Devolución de préstamo #{$loan->id}"
                    ]);
                }

                // 3. Actualizar el estado de la solicitud
                $loan->update(['status' => 'returned']);
            });

            // Enviar correo de devolución al usuario
            $loan->load('user', 'items.resource');
            Mail::to($loan->user->email)->send(new LoanNotification($loan, 'returned'));

            return redirect()->route('loans.index')->with('success', 'Devolución registrada exitosamente. El inventario ha sido restablecido.');

        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error al procesar la devolución: ' . $e->getMessage());
        }
    }

    public function destroy(\App\Models\Loan $loan)
    {
        // Eliminamos la solicitud de la base de datos
        $loan->delete();
        
        // Redirigimos de vuelta a la tabla con un mensaje de éxito
        return redirect()->route('loans.index')->with('success', 'La solicitud fue eliminada correctamente.');
    }
}