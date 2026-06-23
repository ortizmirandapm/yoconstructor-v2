<?php

declare(strict_types=1);

namespace App\Http\Controllers\Empresa;

use App\Enums\PostulacionEstado;
use App\Http\Controllers\Controller;
use App\Http\Requests\CambiarEstadoPostulacionRequest;
use App\Models\Oferta;
use App\Models\Postulacion;
use App\Services\PostulacionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class PostulacionController extends Controller
{
    public function __construct(
        private readonly PostulacionService $postulacionService,
    ) {}

    private function getEmpresa()
    {
        return auth()->user()->empresa;
    }

    public function index(): View
    {
        $ofertas = Oferta::with(['postulaciones.trabajador.user'])
            ->where('empresa_id', $this->getEmpresa()->id)
            ->whereHas('postulaciones')
            ->latest()
            ->get();

        return view('empresa.postulaciones.index', compact('ofertas'));
    }

    public function cambiarEstado(CambiarEstadoPostulacionRequest $request, Postulacion $postulacion): RedirectResponse
    {
        $nuevoEstado = PostulacionEstado::from($request->validated('estado'));
        $this->postulacionService->cambiarEstado($postulacion, $nuevoEstado);

        return back()->with('success', 'Estado actualizado.');
    }
}
