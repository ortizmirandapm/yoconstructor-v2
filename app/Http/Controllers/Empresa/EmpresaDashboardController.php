<?php

declare(strict_types=1);

namespace App\Http\Controllers\Empresa;

use App\Enums\OfertaEstado;
use App\Enums\PostulacionEstado;
use App\Http\Controllers\Controller;
use App\Models\Oferta;
use App\Models\Postulacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class EmpresaDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $empresa = $request->user()->empresa;

        // ── Ofertas stats ──
        $ofertasQuery = Oferta::where('empresa_id', $empresa->id);
        $ofertasTotal = (clone $ofertasQuery)->count();
        $ofertasActivas = (clone $ofertasQuery)->where('estado', OfertaEstado::Activa->value)->count();
        $ofertasPausadas = (clone $ofertasQuery)->where('estado', OfertaEstado::Pausada->value)->count();
        $ofertasBorradores = (clone $ofertasQuery)->where('estado', OfertaEstado::Borrador->value)->count();

        // ── Postulaciones stats ──
        $postQuery = Postulacion::whereHas('oferta', fn ($q) => $q->where('empresa_id', $empresa->id));
        $postTotal = (clone $postQuery)->count();
        $postPendientes = (clone $postQuery)->where('estado', PostulacionEstado::Pendiente->value)->count();
        $postRevisadas = (clone $postQuery)->where('estado', PostulacionEstado::Revisada->value)->count();
        $postEntrevistas = (clone $postQuery)->where('estado', PostulacionEstado::Entrevista->value)->count();
        $postAceptadas = (clone $postQuery)->where('estado', PostulacionEstado::Aceptada->value)->count();
        $postRechazadas = (clone $postQuery)->where('estado', PostulacionEstado::Rechazada->value)->count();
        $postHoy = (clone $postQuery)->whereDate('created_at', Carbon::today())->count();
        $postSemana = (clone $postQuery)->where('created_at', '>=', Carbon::now()->subDays(7))->count();

        // ── Últimas 5 postulaciones ──
        $recientes = Postulacion::with(['trabajador', 'oferta'])
            ->whereHas('oferta', fn ($q) => $q->where('empresa_id', $empresa->id))
            ->latest()
            ->take(5)
            ->get();

        // ── Top 5 ofertas con más postulantes ──
        $topOfertas = Oferta::withCount('postulaciones')
            ->withCount(['postulaciones as pendientes_count' => fn ($q) => $q->where('estado', PostulacionEstado::Pendiente->value)])
            ->where('empresa_id', $empresa->id)
            ->where('estado', '!=', OfertaEstado::Borrador->value)
            ->orderBy('postulaciones_count', 'desc')
            ->take(5)
            ->get();

        // ── Gráfico: postulaciones por día (últimos 7 días) ──
        $chartRaw = Postulacion::selectRaw('DATE(created_at) as dia, COUNT(*) as total')
            ->whereHas('oferta', fn ($q) => $q->where('empresa_id', $empresa->id))
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->groupBy('dia')
            ->orderBy('dia')
            ->pluck('total', 'dia');

        $chartLabels = [];
        $chartValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->format('d/m');
            $chartValues[] = (int) ($chartRaw[$date->format('Y-m-d')] ?? 0);
        }

        // ── Pipeline ──
        $pipeline = [
            ['label' => 'Pendientes', 'count' => $postPendientes, 'bg' => 'bg-yellow-400'],
            ['label' => 'Revisados', 'count' => $postRevisadas, 'bg' => 'bg-blue-400'],
            ['label' => 'Entrevistas', 'count' => $postEntrevistas, 'bg' => 'bg-purple-500'],
            ['label' => 'Aceptados', 'count' => $postAceptadas, 'bg' => 'bg-green-500'],
            ['label' => 'Rechazados', 'count' => $postRechazadas, 'bg' => 'bg-red-400'],
        ];

        // ── Ofertas por vencer (próximos 7 días) ──
        $porVencer = Oferta::where('empresa_id', $empresa->id)
            ->where('estado', OfertaEstado::Activa->value)
            ->whereNotNull('fecha_vencimiento')
            ->whereBetween('fecha_vencimiento', [Carbon::today(), Carbon::today()->addDays(7)])
            ->orderBy('fecha_vencimiento')
            ->get();

        $user = $request->user();

        return view('empresa.dashboard', compact(
            'empresa', 'user',
            'ofertasTotal', 'ofertasActivas', 'ofertasPausadas', 'ofertasBorradores',
            'postTotal', 'postPendientes', 'postRevisadas', 'postEntrevistas',
            'postAceptadas', 'postRechazadas', 'postHoy', 'postSemana',
            'recientes', 'topOfertas',
            'chartLabels', 'chartValues',
            'pipeline', 'porVencer',
        ));
    }
}
