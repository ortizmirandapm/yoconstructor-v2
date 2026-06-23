@extends('layouts.trabajador')

@section('header')
    
@endsection

@section('content')
    @php
        $tabs = [
            'todas'       => 'Todas',
            'postulacion' => 'Postulaciones',
            'oferta'      => 'Ofertas',
            'sistema'     => 'Sistema',
        ];
    @endphp

    @if(session('success'))
        <div class="mb-5 flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-medium">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900">Notificaciones</h2>
            <p class="mt-1 text-sm text-gray-500">
                @if($counts['todas']['no_leidas'] > 0)
                    Tenés <span class="font-semibold text-blue-600">{{ $counts['todas']['no_leidas'] }}</span> sin leer
                @else
                    Todo al día
                @endif
            </p>
        </div>
        @if($counts['todas']['no_leidas'] > 0)
            <form action="{{ route('trabajador.notificaciones.leerTodas') }}" method="POST">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Marcar todas como leídas
                </button>
            </form>
        @endif
    </div>

    {{-- Filtros por tipo --}}
    <div class="flex flex-wrap gap-2 mb-6">
        @foreach($tabs as $key => $label)
            @php
                $activo = $filtro === $key;
                $noLeidas = $counts[$key]['no_leidas'] ?? 0;
                $total = $counts[$key]['total'] ?? 0;
                $base = $activo
                    ? 'bg-blue-600 text-white border-blue-600'
                    : 'bg-white text-gray-600 border-gray-200 hover:border-blue-400 hover:text-blue-600';
            @endphp
            <a href="{{ route('trabajador.notificaciones.index', $key !== 'todas' ? ['filtro' => $key] : []) }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium border rounded-xl transition {{ $base }}">
                {{ $label }}
                @if($noLeidas > 0)
                    <span class="{{ $activo ? 'bg-white text-blue-700' : 'bg-red-500 text-white' }} text-xs font-bold px-1.5 py-0.5 rounded-full">
                        {{ $noLeidas }}
                    </span>
                @elseif($total > 0)
                    <span class="{{ $activo ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-500' }} text-xs font-semibold px-1.5 py-0.5 rounded-full">
                        {{ $total }}
                    </span>
                @endif
            </a>
        @endforeach
    </div>

    {{-- Lista --}}
    @if($notificaciones->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-700 mb-1">Sin notificaciones</h3>
            <p class="text-sm text-gray-400">
                {{ $filtro !== 'todas' ? 'No tenés notificaciones de este tipo.' : 'Cuando haya novedades, las verás acá.' }}
            </p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($notificaciones as $notif)
                @php
                    $data = $notif->data;
                    $leida = !is_null($notif->read_at);
                    $basename = class_basename($notif->type);

                    // Config por tipo
                    if ($basename === 'NuevaOfertaMatch') {
                        $tipoKey = 'oferta';
                        $iconoBg = 'bg-blue-100';
                        $iconoColor = 'text-blue-600';
                        $borde = $leida ? '' : 'border-l-4 border-blue-400';
                        $iconoSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>';
                        $tipoBadge = 'bg-blue-50 text-blue-700';
                        $tipoLabel = 'Oferta';
                        $titulo = $data['titulo'] ?? 'Nueva oferta';
                        $mensaje = ($data['empresa'] ?? 'Una empresa') . ' publicó una oferta que coincide con tu perfil.';
                        $url = $data['url'] ?? null;
                    } elseif ($basename === 'PostulacionActualizada') {
                        $tipoKey = 'postulacion';
                        $estado = $data['nuevo_estado'] ?? '';
                        if (in_array($estado, ['Aceptada', 'Entrevista'])) {
                            $iconoBg = 'bg-green-100';
                            $iconoColor = 'text-green-600';
                            $borde = $leida ? '' : 'border-l-4 border-green-400';
                            $iconoSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                        } elseif ($estado === 'Rechazada') {
                            $iconoBg = 'bg-red-100';
                            $iconoColor = 'text-red-500';
                            $borde = $leida ? '' : 'border-l-4 border-red-400';
                            $iconoSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                        } else {
                            $iconoBg = 'bg-amber-100';
                            $iconoColor = 'text-amber-600';
                            $borde = $leida ? '' : 'border-l-4 border-amber-400';
                            $iconoSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                        }
                        $tipoBadge = 'bg-green-50 text-green-700';
                        if ($estado === 'Rechazada') $tipoBadge = 'bg-red-50 text-red-700';
                        elseif ($estado === 'Entrevista') $tipoBadge = 'bg-amber-50 text-amber-700';
                        $tipoLabel = $estado ?: 'Postulación';
                        $titulo = $data['titulo'] ?? 'Estado de postulación';
                        $mensaje = $data['mensaje'] ?? '';
                        $url = $data['url'] ?? null;
                    } elseif ($basename === 'AlertaSistema') {
                        $tipoKey = 'sistema';
                        $iconoBg = 'bg-amber-100';
                        $iconoColor = 'text-amber-600';
                        $borde = $leida ? '' : 'border-l-4 border-amber-400';
                        $iconoSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                        $tipoBadge = 'bg-amber-50 text-amber-700';
                        $tipoLabel = 'Sistema';
                        $titulo = $data['titulo'] ?? 'Notificación del sistema';
                        $mensaje = $data['mensaje'] ?? '';
                        $url = $data['url'] ?? null;
                    } else {
                        $tipoKey = 'sistema';
                        $iconoBg = 'bg-gray-100';
                        $iconoColor = 'text-gray-600';
                        $borde = $leida ? '' : 'border-l-4 border-gray-400';
                        $iconoSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                        $tipoBadge = 'bg-gray-50 text-gray-700';
                        $tipoLabel = 'General';
                        $titulo = $data['titulo'] ?? 'Notificación';
                        $mensaje = $data['mensaje'] ?? '';
                        $url = $data['url'] ?? null;
                    }

                    $bgCard = $leida ? 'bg-gray-50' : 'bg-blue-50/30';
                @endphp

                <div class="{{ $bgCard }} {{ $borde }} rounded-2xl border border-gray-200 p-4 flex gap-4 hover:shadow-sm transition group">

                    {{-- Ícono --}}
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 {{ $iconoBg }} rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 {{ $iconoColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $iconoSvg !!}
                            </svg>
                        </div>
                    </div>

                    {{-- Contenido --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $tipoBadge }}">
                                        {{ $tipoLabel }}
                                    </span>
                                    @if(!$leida)
                                        <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse" title="No leída"></span>
                                    @endif
                                </div>
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $titulo }}
                                </p>
                                <p class="text-sm text-gray-500 mt-0.5 leading-relaxed">
                                    {{ $mensaje }}
                                </p>
                            </div>

                            {{-- Tiempo + acción --}}
                            <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                <span class="text-xs text-gray-400 whitespace-nowrap">
                                    {{ $notif->created_at->diffForHumans() }}
                                </span>
                                @if(!$leida)
                                    <form action="{{ route('trabajador.notificaciones.leer', $notif->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" title="Marcar como leída"
                                            class="text-gray-300 hover:text-blue-500 transition opacity-0 group-hover:opacity-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        {{-- Link de acción --}}
                        @if($url)
                            <div class="mt-2">
                                <a href="{{ $url }}"
                                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 transition">
                                    Ver detalle
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($notificaciones->hasPages())
            <div class="mt-6">{{ $notificaciones->appends(request()->query())->links() }}</div>
        @endif
    @endif
@endsection
