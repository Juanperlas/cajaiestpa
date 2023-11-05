<?php

namespace App\Http\Middleware;

use App\Models\Payment;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserSharedResource;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {

        $today = now()->toDateString();

        $sharedData = [];

        if (Auth::check()) {
            // El usuario está autenticado, comparte los datos
            $sharedData = [
                'auth.user' => fn () => $request->user()
                    ? new UserSharedResource($request->user())
                    : null,
                'registros_data' => [
                    'pagosTotales' => Payment::count(),
                    'pagosHoy' => Payment::whereDate('created_at', $today)->count(),
                    'operacionesTotales' =>Payment::distinct('operation_id')->count('operation_id'),
                    'operacionesHoy' =>Payment::whereDate('created_at', $today)->distinct('operation_id')->count('operation_id'),
                    'sumaImporteTotal' => Payment::sum('importe'),
                    'ultimoPago' => Payment::latest()->first() ? Payment::latest()->first()->importe : 0,
                    'sumaImporteHoy' => Payment::whereDate('created_at', $today)->sum('importe'),
                    'sumaImporteAyer' => Payment::whereDate('created_at', now()->subDay()->toDateString())->sum('importe'),
                ],
            ];
        }

        if ($request->user()) {
            $sharedData['auth.user'] = new UserSharedResource($request->user());
        }

        return array_merge(parent::share($request), $sharedData);
    }
}
