@extends('layouts.admin')

@section('title', 'Administracion')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-500 text-sm mt-0.5">Vista general de la plataforma &middot; {{ date('d/m/Y') }}</p>
    </div>

    {{-- Metricas --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <a href="{{ route('admin.empresas.index') }}" class="text-xs text-indigo-600 hover:underline font-medium">Ver todas</a>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $empresasActivas }}</p>
            <p class="text-sm text-gray-500 mt-0.5">Empresas activas</p>
            <p class="text-xs text-gray-400 mt-1">{{ $empresasInactivas }} de baja</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <a href="{{ route('admin.usuarios.index') }}" class="text-xs text-indigo-600 hover:underline font-medium">Ver todos</a>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $trabajadoresActivos }}</p>
            <p class="text-sm text-gray-500 mt-0.5">Trabajadores activos</p>
            <p class="text-xs text-gray-400 mt-1">{{ $trabajadoresInactivos }} de baja</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <a href="{{ route('admin.ofertas.index') }}" class="text-xs text-indigo-600 hover:underline font-medium">Ver todas</a>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $ofertasActivas }}</p>
            <p class="text-sm text-gray-500 mt-0.5">Ofertas activas</p>
            <p class="text-xs text-gray-400 mt-1">{{ $ofertasBorradores }} borradores &middot; {{ $ofertasCerradas }} cerradas</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-cyan-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <a href="{{ route('admin.ofertas.index') }}" class="text-xs text-indigo-600 hover:underline font-medium">Ver ofertas</a>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $postulacionesTotal }}</p>
            <p class="text-sm text-gray-500 mt-0.5">Postulaciones totales</p>
            <p class="text-xs text-gray-400 mt-1">{{ $postulacionesHoy }} hoy &middot; {{ $reportesPendientes }} reportes pendientes</p>
        </div>

    </div>

    {{-- Grafico + resumen --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">
        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
            <p class="text-sm font-semibold text-gray-800 mb-1">Postulaciones &mdash; ultimos 7 dias</p>
            <p class="text-xs text-gray-400 mb-5">Actividad diaria de la plataforma</p>
            <canvas id="chartPost" height="110"></canvas>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
            <p class="text-sm font-semibold text-gray-800 mb-4">Accesos rapidos</p>
            <div class="space-y-2">
                <a href="{{ route('admin.empresas.index') }}?estado=inactivo" class="flex items-center justify-between px-3 py-2.5 bg-red-50 hover:bg-red-100 border border-red-100 rounded-xl transition">
                    <span class="text-xs font-medium text-red-700">Empresas de baja</span>
                    <span class="text-xs font-bold text-red-600">{{ $empresasInactivas }}</span>
                </a>
                <a href="{{ route('admin.usuarios.index') }}?estado=inactivo" class="flex items-center justify-between px-3 py-2.5 bg-orange-50 hover:bg-orange-100 border border-orange-100 rounded-xl transition">
                    <span class="text-xs font-medium text-orange-700">Trabajadores de baja</span>
                    <span class="text-xs font-bold text-orange-600">{{ $trabajadoresInactivos }}</span>
                </a>
                <a href="{{ route('admin.ofertas.index') }}?estado=Cerrada" class="flex items-center justify-between px-3 py-2.5 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl transition">
                    <span class="text-xs font-medium text-gray-600">Ofertas cerradas</span>
                    <span class="text-xs font-bold text-gray-500">{{ $ofertasCerradas }}</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Tablas recientes --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <p class="text-sm font-semibold text-gray-800">Empresas recientes</p>
                <a href="{{ route('admin.empresas.index') }}" class="text-xs text-indigo-600 hover:underline font-medium">Ver todas</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($empresasRecientes as $e)
                    <div class="flex items-center gap-3 px-6 py-3.5 hover:bg-gray-50 transition">
                        @if($e->logo)
                            <img src="{{ asset($e->logo) }}" class="w-8 h-8 rounded-lg object-contain border border-gray-200 flex-shrink-0" alt="">
                        @else
                            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 text-xs font-bold flex-shrink-0">{{ strtoupper(substr($e->nombre_empresa,0,1)) }}</div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $e->nombre_empresa }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $e->user?->email ?? '' }}</p>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full border font-medium flex-shrink-0 {{ $e->estado === 'activo' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                            {{ ucfirst($e->estado) }}
                        </span>
                    </div>
                @empty
                    <p class="px-6 py-8 text-center text-sm text-gray-400">Sin datos</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <p class="text-sm font-semibold text-gray-800">Ofertas recientes</p>
                <a href="{{ route('admin.ofertas.index') }}" class="text-xs text-indigo-600 hover:underline font-medium">Ver todas</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($ofertasRecientes as $o)
                    @php
                        $ob = match($o->estado) {
                            'Activa'  => 'bg-green-50 text-green-700 border-green-200',
                            'Borrador'=> 'bg-amber-50 text-amber-700 border-amber-200',
                            'Cerrada' => 'bg-gray-100 text-gray-500 border-gray-200',
                            default   => 'bg-gray-100 text-gray-500 border-gray-200'
                        };
                    @endphp
                    <div class="flex items-center gap-3 px-6 py-3.5 hover:bg-gray-50 transition">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $o->titulo }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $o->empresa->nombre_empresa }}</p>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full border font-medium flex-shrink-0 {{ $ob }}">{{ $o->estado }}</span>
                    </div>
                @empty
                    <p class="px-6 py-8 text-center text-sm text-gray-400">Sin datos</p>
                @endforelse
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
new Chart(document.getElementById('chartPost').getContext('2d'), {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [{ label: 'Postulaciones', data: @json($chartValues),
            backgroundColor: 'rgba(99,102,241,0.12)', borderColor: 'rgba(99,102,241,0.7)',
            borderWidth: 2, borderRadius: 6, borderSkipped: false }]
    },
    options: {
        responsive: true, plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { precision:0, color:'#9ca3af', font:{size:11} }, grid: { color:'#f3f4f6' } },
            x: { ticks: { color:'#9ca3af', font:{size:11} }, grid: { display:false } }
        }
    }
});
</script>
@endpush