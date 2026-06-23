<?php

declare(strict_types=1);

namespace App\Http\Controllers\Trabajador;

use App\Enums\OfertaEstado;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostulacionStoreRequest;
use App\Models\Oferta;
use App\Models\Postulacion;
use App\Services\PostulacionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class PostulacionController extends Controller
{
    public function __construct(
        private readonly PostulacionService $postulacionService,
    ) {}

    private function getTrabajador()
    {
        return auth()->user()->trabajador;
    }

    public function index(Request $request): View
    {
        $trabajador = $this->getTrabajador();
        $filtroEstado = $request->query('estado');

        [$postulaciones, $filtroEstado, $estadosValidos, $counts] =
            $this->postulacionService->obtenerPostulacionesTrabajador($trabajador, $filtroEstado);

        return view('trabajador.postulaciones.index', compact(
            'postulaciones', 'filtroEstado', 'estadosValidos', 'counts'
        ));
    }

    public function crear(Oferta $oferta): View|RedirectResponse
    {
        if (!$oferta->estado->esActiva()) {
            abort(404);
        }

        if ($this->postulacionService->yaPostulado($oferta, $this->getTrabajador())) {
            return redirect()->route('ofertas.show', $oferta)
                ->with('error', 'Ya te postulaste a esta oferta.');
        }

        $oferta->load('empresa');
        return view('trabajador.postulaciones.crear', compact('oferta'));
    }

    public function store(PostulacionStoreRequest $request, Oferta $oferta): RedirectResponse
    {
        $this->postulacionService->crear(
            $oferta,
            $this->getTrabajador(),
            $request->validated('mensaje'),
        );

        return redirect()->route('trabajador.postulaciones.index')
            ->with('success', 'Te postulaste correctamente.');
    }

    public function cancelar(Postulacion $postulacion): RedirectResponse
    {
        $cancelado = $this->postulacionService->cancelar($postulacion, $this->getTrabajador());

        if (!$cancelado) {
            return redirect()->route('trabajador.postulaciones.index')
                ->with('error', 'No se pudo cancelar la postulación.');
        }

        return redirect()->route('trabajador.postulaciones.index')
            ->with('success', 'Postulación cancelada correctamente.');
    }
}
