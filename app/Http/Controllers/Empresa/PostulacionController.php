<?php

declare(strict_types=1);

namespace App\Http\Controllers\Empresa;

use App\Enums\PostulacionEstado;
use App\Http\Controllers\Controller;
use App\Http\Requests\CambiarEstadoPostulacionRequest;
use App\Models\Postulacion;
use App\Models\Trabajador;
use App\Services\PostulacionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function index(Request $request): View|RedirectResponse
    {
        $empresa = $this->getEmpresa();
        $ofertaId = $request->input('oferta');

        if (!$ofertaId) {
            return redirect()->route('empresa.ofertas.index');
        }

        $oferta = $empresa->ofertas()->findOrFail($ofertaId);

        $query = Postulacion::where('oferta_id', $oferta->id)
            ->with('trabajador.user', 'trabajador.especialidades');

        $filtroEstado = $request->input('estado', '');
        $estadosValidos = ['Pendiente', 'Revisada', 'Entrevista', 'Aceptada', 'Rechazada'];
        if ($filtroEstado && in_array($filtroEstado, $estadosValidos)) {
            $query->where('estado', $filtroEstado);
        }

        $postulantes = $query->orderBy('created_at', 'desc')->get();

        $counts = [];
        foreach ($estadosValidos as $est) {
            $counts[$est] = Postulacion::where('oferta_id', $oferta->id)
                ->where('estado', $est)
                ->count();
        }
        $total = array_sum($counts);

        return view('empresa.postulaciones.index', compact('oferta', 'postulantes', 'counts', 'total', 'filtroEstado'));
    }

    public function cambiarEstado(CambiarEstadoPostulacionRequest $request, Postulacion $postulacion): RedirectResponse
    {
        $nuevoEstado = PostulacionEstado::from($request->validated('estado'));
        $this->postulacionService->cambiarEstado($postulacion, $nuevoEstado);

        return back()->with('toast', 'Estado actualizado.');
    }

    public function guardarNotas(Request $request, Postulacion $postulacion): RedirectResponse
    {
        $request->validate([
            'notas' => ['required', 'string', 'max:1000'],
        ]);

        $empresa = $this->getEmpresa();
        if ($postulacion->oferta->empresa_id !== $empresa->id) {
            abort(403);
        }

        $postulacion->update(['notas_empresa' => $request->input('notas')]);

        return back()->with('toast', 'Nota guardada.');
    }

    public function perfil(Trabajador $trabajador, Request $request): View
    {
        $empresa = $this->getEmpresa();

        $yaPostulado = Postulacion::where('trabajador_id', $trabajador->id)
            ->whereHas('oferta', fn ($q) => $q->where('empresa_id', $empresa->id))
            ->exists();

        if (!$yaPostulado) {
            abort(403);
        }

        $trabajador->load([
            'user',
            'especialidades',
            'provincia',
            'localidad',
        ]);

        $especialidades = $trabajador->especialidades;
        $espPrincipal = $especialidades->firstWhere('pivot.es_principal', 1) ?? $especialidades->first();
        $otrasEspecialidades = $especialidades->filter(fn ($e) => $espPrincipal && $e->id !== $espPrincipal->id)->values();

        $backUrl = route('empresa.postulaciones.index', ['oferta' => $request->query('oferta')]);
        $backLabel = 'Volver a postulantes';

        return view('empresa.postulaciones.perfil', compact(
            'trabajador',
            'espPrincipal',
            'otrasEspecialidades',
            'backUrl',
            'backLabel',
        ));
    }
}
