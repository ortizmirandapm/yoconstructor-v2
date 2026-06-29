<?php

declare(strict_types=1);

namespace App\Http\Controllers\Trabajador;

use App\Http\Controllers\Controller;
use App\Services\NotificacionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class NotificacionController extends Controller
{
    public function __construct(
        private readonly NotificacionService $notificacionService,
    ) {}

    public function index(Request $request): View
    {
        $user = auth()->user();
        $filtro = $request->query('filtro', 'todas');
        $filtrosValidos = ['todas', 'postulacion', 'oferta', 'sistema'];

        if (! in_array($filtro, $filtrosValidos, true)) {
            $filtro = 'todas';
        }

        $notificaciones = $this->notificacionService->obtenerNotificaciones($user, $filtro);
        $counts = $this->notificacionService->obtenerConteos($user);

        $this->notificacionService->chequearPerfilIncompleto($user, $user->trabajador);

        return view('trabajador.notificaciones', compact('notificaciones', 'filtro', 'filtrosValidos', 'counts'));
    }

    public function marcarLeida(string $id): RedirectResponse
    {
        $this->notificacionService->marcarLeida(auth()->user(), $id);

        return back();
    }

    public function marcarTodasLeidas(): RedirectResponse
    {
        $this->notificacionService->marcarTodasLeidas(auth()->user());

        return back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }
}
