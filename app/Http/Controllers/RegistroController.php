<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Concept;
use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PaymentDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;

class RegistroController extends Controller
{
    public function index():Response
    {
        // Aquí puedes manejar la lógica para mostrar todos los registros
        return Inertia::render('Registro/Registro');
    }

    public function create(string $dni): Response
    {
        $conceptos = Concept::all('concept_name');
        if (!ctype_digit($dni) || strlen($dni) !== 8) {
            return Inertia::render('Registro/Crear', [
                'dni' => $dni,
                'error' => 'Formato incorrecto de DNI',

            ]);
        }
        $visitante = Http::withoutVerifying()->get('https://api.apis.net.pe/v1/dni?numero=', [
            'numero' => $dni
        ]);
        $visitanteArray = $visitante->json();
        if  (is_null($visitanteArray) || (isset($visitanteArray['error']) && $visitanteArray['error'] === 'Invalid dni')) {
            return Inertia::render('Registro/Crear', [
                'error' => 'No se encontró el DNI ingresado',
                'dni' => $dni,
            ]);
        }
        return Inertia::render('Registro/Crear', [
            'visitanteArray' => $visitanteArray,
            'conceptos' => $conceptos,
            'dni' => $dni,
            'success' => 'Se encontró el DNI ingresado',
        ]);
    }

    public function store(Request $request)
    {
        // Generar un identificador único para esta operación de caja
        // Obtener el último identificador de operación
        $lastOperationId = Payment::max('operation_id');

        // Incrementar el identificador de operación
        $operationId = str_pad($lastOperationId + 1, 7, '0', STR_PAD_LEFT);

        foreach ($request->pagos as $pagoDetalle) {
            // Crear un nuevo registro de pago
            $pago = new Payment;
            $pago->dni = $request->dni;
            $pago->user_name = $request->visitante;
            $pago->payment_time = now()->format('H:i:s'); // Hora actual
            $pago->payment_date = now()->format('Y-m-d'); // Fecha actual
            $pago->observation = $request->observation;
            $pago->importe = $pagoDetalle['importe']; // Importe del pago individual
            $pago->operation_id = $operationId; // Asignar el identificador de la operación de caja
            $pago->save();

            // Crear un nuevo detalle de pago
            $detalle = new PaymentDetail;
            $detalle->payment_id = $pago->id;
            $detalle->concept_id = Concept::firstOrCreate(['concept_name' => $pagoDetalle['concepto']])->id;
            $detalle->save();
        }

        // Redirigir al usuario a la página de éxito
        return redirect()->route('registro.index')->with('success', 'Registro creado con éxito');
    }


    public function show($id)
    {
        // Aquí puedes manejar la lógica para mostrar un registro específico
    }

    public function getAll(): Response
    {
        // Obtener todos los pagos
        $pagos = Payment::all();

        // Pasar los pagos a la vista
        return Inertia::render('Registro/Ver', ['pagos' => $pagos]);
    }

    public function reporte(): Response
    {
        // Obtener todos los pagos
        $pagos = Payment::all();

        // Pasar los pagos a la vista
        return Inertia::render('Registro/Reporte', ['pagos' => $pagos]);
    }

    // public function boleta($id)
    // {
    //     $pago = Payment::findOrFail($id);
    //     $pagos = Payment::where('operation_id', $pago->operation_id)->get();
    //     return view('boleta', ['pagos' => $pagos]);
    // }
    public function boleta($id)
    {
        $pago = Payment::findOrFail($id);
        $pagos = Payment::where('operation_id', $pago->operation_id)->with('paymentDetails.concept')->get();
        $importeTotal = $pagos->sum('importe');
        return view('boleta', ['pagos' => $pagos, 'importeTotal' => $importeTotal]);
    }

    public function edit($id)
    {
        // Aquí puedes manejar la lógica para mostrar el formulario de edición de un registro específico
    }

    public function update(Request $request, $id)
    {
        // Aquí puedes manejar la lógica para actualizar un registro específico
    }

    public function destroy($id): RedirectResponse
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return to_route('registro.all');
    }
}
