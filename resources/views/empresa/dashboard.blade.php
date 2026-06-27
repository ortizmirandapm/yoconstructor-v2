@php
    $page = 'dashboard';
    $pageTitle = 'Dashboard';
    $chartTotal = array_sum($chartValues);
    $maxTop = $topOfertas->max('postulaciones_count') ?: 1;
    $maxPipeline = max(array_column($pipeline, 'count')) ?: 1;

    $estado_cfg = [
        'Pendiente'  => ['bg'=>'bg-yellow-100','text'=>'text-yellow-800','dot'=>'bg-yellow-400','label'=>'Pendiente'],
        'Revisada'   => ['bg'=>'bg-blue-100',  'text'=>'text-blue-800',  'dot'=>'bg-blue-400',  'label'=>'Revisada'],
        'Entrevista' => ['bg'=>'bg-purple-100','text'=>'text-purple-800','dot'=>'bg-purple-500','label'=>'Entrevista'],
        'Aceptada'   => ['bg'=>'bg-green-100', 'text'=>'text-green-800', 'dot'=>'bg-green-500', 'label'=>'Aceptada'],
        'Rechazada'  => ['bg'=>'bg-red-100',   'text'=>'text-red-800',   'dot'=>'bg-red-400',   'label'=>'Rechazada'],
    ];
@endphp

@extends('layouts.empresa')

@section('title', 'Empresa')
@section('subtitle', '')

@section('content')
<div class="min-h-screen bg-gray-50 p-6 md:p-10">

    {{-- Encabezado --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-500 mt-1 text-sm">Resumen de actividad de tu empresa</p>
    </div>

    {{-- ── MÉTRICAS TOP ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        {{-- Postulantes nuevos hoy --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                @if ($postHoy > 0)
                <span class="text-xs font-semibold bg-cyan-50 text-cyan-600 px-2 py-0.5 rounded-full">Hoy</span>
                @endif
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $postHoy }}</p>
            <p class="text-sm text-gray-500 mt-0.5">Postulante{{ $postHoy !== 1 ? 's' : '' }} hoy</p>
            <p class="text-xs text-gray-400 mt-1">{{ $postSemana }} esta semana</p>
        </div>

        {{-- Total postulantes --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                @if ($postPendientes > 0)
                <span class="text-xs font-semibold bg-yellow-50 text-yellow-600 px-2 py-0.5 rounded-full">{{ $postPendientes }} sin revisar</span>
                @endif
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $postTotal }}</p>
            <p class="text-sm text-gray-500 mt-0.5">Total postulantes</p>
            <p class="text-xs text-gray-400 mt-1">{{ $postAceptadas }} aceptado{{ $postAceptadas !== 1 ? 's' : '' }}</p>
        </div>

        {{-- Ofertas activas --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <a href="{{ route('empresa.ofertas.index') }}" class="text-xs text-gray-400 hover:text-cyan-600 transition">Ver todas →</a>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $ofertasActivas }}</p>
            <p class="text-sm text-gray-500 mt-0.5">Oferta{{ $ofertasActivas !== 1 ? 's' : '' }} activa{{ $ofertasActivas !== 1 ? 's' : '' }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $ofertasPausadas }} pausada{{ $ofertasPausadas !== 1 ? 's' : '' }} · {{ $ofertasBorradores }} borrador{{ $ofertasBorradores !== 1 ? 'es' : '' }}</p>
        </div>

        {{-- En entrevista --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $postEntrevistas }}</p>
            <p class="text-sm text-gray-500 mt-0.5">En entrevista</p>
            <p class="text-xs text-gray-400 mt-1">{{ $postRechazadas }} rechazado{{ $postRechazadas !== 1 ? 's' : '' }}</p>
        </div>

    </div>

    {{-- ── FILA CENTRAL: Gráfico + Top ofertas ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">

        {{-- Gráfico postulaciones últimos 7 días --}}
        <div class="lg:col-span-3 bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-base font-bold text-gray-800">Postulaciones</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Últimos 7 días</p>
                </div>
                <span class="text-xs bg-gray-100 text-gray-500 px-3 py-1 rounded-full font-medium">{{ $chartTotal }} total</span>
            </div>
            <div class="relative h-40">
                <canvas id="chartPostulaciones"></canvas>
            </div>
        </div>

        {{-- Top ofertas --}}
        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-base font-bold text-gray-800">Top ofertas</h2>
                <a href="{{ route('empresa.ofertas.index') }}" class="text-xs text-cyan-600 hover:underline font-medium">Ver todas</a>
            </div>
            @if ($topOfertas->isEmpty())
            <p class="text-sm text-gray-400 text-center py-8">Sin datos aún</p>
            @else
            <div class="space-y-3">
                @foreach ($topOfertas as $i => $to)
                    @php $pct = round(($to->postulaciones_count / $maxTop) * 100); @endphp
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="text-xs font-bold text-gray-400 w-4 flex-shrink-0">{{ $i + 1 }}</span>
                            <a href="{{ route('empresa.postulaciones.index') }}" class="text-xs font-medium text-gray-700 hover:text-cyan-600 truncate transition">
                                {{ $to->titulo }}
                            </a>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0 ml-2">
                            @if ($to->pendientes_count > 0)
                            <span class="text-xs bg-yellow-100 text-yellow-700 px-1.5 py-0.5 rounded font-medium">{{ $to->pendientes_count }} sin ver</span>
                            @endif
                            <span class="text-xs font-bold text-gray-600">{{ $to->postulaciones_count }}</span>
                        </div>
                    </div>
                    <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-cyan-500 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>

    {{-- ── FILA INFERIOR: Últimas postulaciones + Pipeline + Por vencer ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- Últimas postulaciones --}}
        <div class="lg:col-span-3 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-bold text-gray-800">Últimas postulaciones</h2>
                @if ($postPendientes > 0)
                <span class="text-xs bg-yellow-100 text-yellow-700 font-semibold px-2.5 py-1 rounded-full">{{ $postPendientes }} pendiente{{ $postPendientes !== 1 ? 's' : '' }}</span>
                @endif
            </div>
            @if ($recientes->isEmpty())
            <div class="px-6 py-12 text-center">
                <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p class="text-sm text-gray-400">Aún no hay postulaciones</p>
            </div>
            @else
            <ul class="divide-y divide-gray-100">
                @foreach ($recientes as $rec)
                    @php
                        $cfg = $estado_cfg[$rec->estado->value] ?? $estado_cfg['Pendiente'];
                        $foto = $rec->trabajador?->imagen_perfil
                            ? asset('uploads/perfil/' . $rec->trabajador->imagen_perfil)
                            : asset('img/profile.png');
                        $nombre = strtoupper(trim(($rec->trabajador?->nombre ?? '') . ' ' . ($rec->trabajador?->apellido ?? '')));
                    @endphp
                <li class="px-6 py-4 hover:bg-gray-50 transition">
                    <div class="flex items-center gap-3">
                        <img src="{{ $foto }}" class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 flex-shrink-0" alt="foto">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $nombre ?: 'Trabajador' }}</p>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium {{ $cfg['bg'] }} {{ $cfg['text'] }} flex-shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }}"></span>
                                    {{ $cfg['label'] }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between mt-0.5">
                                <a href="{{ route('empresa.postulaciones.index') }}" class="text-xs text-cyan-600 hover:underline truncate">
                                    {{ $rec->oferta?->titulo ?? 'Oferta' }}
                                </a>
                                <span class="text-xs text-gray-400 flex-shrink-0 ml-2">{{ $rec->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            @endif
        </div>

        {{-- Pipeline + Por vencer --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Pipeline de estados --}}
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <h2 class="text-base font-bold text-gray-800 mb-4">Pipeline de postulantes</h2>
                @foreach ($pipeline as $pl)
                    @php $pct = round(($pl['count'] / $maxPipeline) * 100); @endphp
                <div class="flex items-center gap-3 mb-2.5">
                    <span class="text-xs text-gray-500 w-20 flex-shrink-0">{{ $pl['label'] }}</span>
                    <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full {{ $pl['bg'] }} rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                    <span class="text-xs font-bold text-gray-600 w-6 text-right">{{ $pl['count'] }}</span>
                </div>
                @endforeach
            </div>

            {{-- Ofertas por vencer --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-base font-bold text-gray-800">Por vencer</h2>
                    <span class="text-xs text-gray-400">próximos 7 días</span>
                </div>
                @if ($porVencer->isEmpty())
                <div class="px-6 py-8 text-center">
                    <svg class="w-8 h-8 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xs text-gray-400">Sin ofertas próximas a vencer</p>
                </div>
                @else
                <ul class="divide-y divide-gray-100">
                    @foreach ($porVencer as $pv)
                        @php
                            $diasRestantes = now()->diffInDays($pv->fecha_vencimiento, false);
                            $urgente = $diasRestantes <= 2;
                        @endphp
                    <li class="px-6 py-3 flex items-center justify-between gap-3 hover:bg-gray-50 transition">
                        <div class="min-w-0">
                            <a href="{{ route('empresa.ofertas.edit', $pv) }}" class="text-sm font-medium text-gray-700 hover:text-cyan-600 truncate block transition">
                                {{ $pv->titulo }}
                            </a>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $pv->fecha_vencimiento?->format('d/m/Y') }}</p>
                        </div>
                        <span class="flex-shrink-0 text-xs font-bold px-2.5 py-1 rounded-full {{ $urgente ? 'bg-red-100 text-red-600' : 'bg-orange-100 text-orange-600' }}">
                            {{ $diasRestantes <= 0 ? 'Hoy' : $diasRestantes . 'd' }}
                        </span>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
const ctx = document.getElementById('chartPostulaciones').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Postulaciones',
            data: @json($chartValues),
            backgroundColor: 'rgba(6,182,212,0.15)',
            borderColor: 'rgba(6,182,212,1)',
            borderWidth: 2,
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ' ' + ctx.parsed.y + ' postulaci' + (ctx.parsed.y === 1 ? 'ón' : 'ones')
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1, font: { size: 11 } },
                grid: { color: 'rgba(0,0,0,0.05)' }
            },
            x: {
                ticks: { font: { size: 11 } },
                grid: { display: false }
            }
        }
    }
});
</script>
@endpush
