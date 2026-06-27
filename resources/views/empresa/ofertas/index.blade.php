@php
    $currentRoute = request()->route()->getName();
    $search = request()->input('q', '');
    $filtroEsp = (int) request()->input('especialidad', 0);
    $filtroEstado = request()->input('estado', '');
    $filtroProvincia = (int) request()->input('provincia', 0);
    $filtroOrden = request()->input('orden', 'desc');
@endphp

@extends('layouts.empresa')

@section('title', 'Ofertas')
@section('subtitle', 'Gestioná tus ofertas laborales publicadas')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">

    {{-- Encabezado --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Mis ofertas publicadas</h1>
            <p class="text-gray-500 mt-1 text-sm">
                {{ $ofertas->total() }} oferta{{ $ofertas->total() !== 1 ? 's' : '' }} encontrada{{ $ofertas->total() !== 1 ? 's' : '' }}
            </p>
        </div>
        <a href="{{ route('empresa.ofertas.create') }}"
            class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nueva oferta
        </a>
    </div>

    {{-- Filtros --}}
    <form method="GET" action="" class="bg-white border border-gray-200 rounded-xl shadow-sm p-5 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="relative">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="q" value="{{ $search }}"
                    placeholder="Buscar por título..."
                    class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
            </div>
            <select name="especialidad"
                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
                <option value="">Todas las especialidades</option>
                @foreach($especialidades as $esp)
                    <option value="{{ $esp->id }}" {{ $filtroEsp == $esp->id ? 'selected' : '' }}>
                        {{ $esp->nombre }}
                    </option>
                @endforeach
            </select>
            <select name="estado"
                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
                <option value="">Todos los estados</option>
                <option value="Activa"  {{ $filtroEstado === 'Activa'  ? 'selected' : '' }}>Activa</option>
                <option value="Pausada" {{ $filtroEstado === 'Pausada' ? 'selected' : '' }}>Pausada</option>
                <option value="Cerrada" {{ $filtroEstado === 'Cerrada' ? 'selected' : '' }}>Cerrada</option>
            </select>
            <select name="provincia"
                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
                <option value="">Todas las provincias</option>
                @foreach($provincias as $prov)
                    <option value="{{ $prov->id }}" {{ $filtroProvincia == $prov->id ? 'selected' : '' }}>
                        {{ $prov->nombre }}
                    </option>
                @endforeach
            </select>
            <select name="orden"
                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
                <option value="desc" {{ $filtroOrden !== 'asc' ? 'selected' : '' }}>Más recientes primero</option>
                <option value="asc"  {{ $filtroOrden === 'asc'  ? 'selected' : '' }}>Más antiguas primero</option>
            </select>
        </div>
        <div class="flex gap-3 mt-4">
            <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition">Filtrar</button>
            <a href="{{ route('empresa.ofertas.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-5 py-2.5 rounded-lg transition">Limpiar</a>
        </div>
    </form>

    {{-- Cards --}}
    @if ($ofertas->isEmpty())
        <div class="bg-white border border-dashed border-gray-300 rounded-xl p-16 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-gray-500 font-medium">No hay ofertas que coincidan con los filtros.</p>
            <a href="{{ route('empresa.ofertas.create') }}" class="inline-block mt-4 text-cyan-600 hover:underline text-sm font-medium">Publicar tu primera oferta →</a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach($ofertas as $oferta)
                @php
                    $espPrincipal = $oferta->especialidades->firstWhere('pivot.es_principal', 1) ?? $oferta->especialidades->first();
                    $estado = $oferta->estado instanceof \App\Enums\OfertaEstado ? $oferta->estado->value : $oferta->estado;
                    $tipoContrato = $oferta->tipo_contrato instanceof \App\Enums\TipoContrato ? $oferta->tipo_contrato->value : $oferta->tipo_contrato;
                    $modalidad = $oferta->modalidad instanceof \App\Enums\Modalidad ? $oferta->modalidad->value : $oferta->modalidad;
                    $vencida = $oferta->fecha_vencimiento && $oferta->fecha_vencimiento->isPast();
                @endphp
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition flex flex-col">

                    {{-- Header card --}}
                    <div class="p-5 border-b border-gray-100">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-800 text-sm leading-snug truncate" title="{{ $oferta->titulo }}">
                                    {{ $oferta->titulo }}
                                </h3>
                                @if($espPrincipal)
                                    <p class="text-xs text-cyan-600 font-medium mt-0.5">{{ $espPrincipal->nombre }}</p>
                                @endif
                            </div>
                            @php
                                $badgeClass = match($estado) {
                                    'Activa'  => 'bg-green-100 text-green-700',
                                    'Pausada' => 'bg-yellow-100 text-yellow-700',
                                    'Cerrada' => 'bg-red-100 text-red-700',
                                    default   => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $estado === 'Activa' ? 'bg-green-500' : ($estado === 'Pausada' ? 'bg-yellow-500' : ($estado === 'Cerrada' ? 'bg-red-500' : 'bg-gray-400')) }}"></span>
                                {{ $estado }}
                            </span>
                        </div>
                    </div>

                    {{-- Body card --}}
                    <div class="p-5 flex-1 space-y-3">
                        <div class="flex flex-wrap gap-x-4 gap-y-1.5 text-xs text-gray-500">
                            @if($oferta->provincia)
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $oferta->provincia->nombre }}
                                </span>
                            @endif
                            @if($tipoContrato)
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ $tipoContrato }}
                                </span>
                            @endif
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Publicado: {{ $oferta->created_at->format('d/m/Y') }}
                            </span>
                        </div>

                        @if($oferta->salario_min || $oferta->salario_max)
                            @php
                                $fmt = fn($n) => number_format((float) $n, 0, ',', '.');
                            @endphp
                            <div class="text-xs text-gray-600 font-medium bg-gray-50 rounded-lg px-3 py-2">
                                @if($oferta->salario_min && $oferta->salario_max)
                                    ${{ $fmt($oferta->salario_min) }} – ${{ $fmt($oferta->salario_max) }} ARS
                                @elseif($oferta->salario_min)
                                    Desde ${{ $fmt($oferta->salario_min) }} ARS
                                @else
                                    Hasta ${{ $fmt($oferta->salario_max) }} ARS
                                @endif
                            </div>
                        @endif

                        <div class="flex items-center gap-2">
                            <div class="flex items-center gap-1.5 bg-cyan-50 text-cyan-700 text-xs font-semibold px-3 py-1.5 rounded-lg">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $oferta->postulaciones_count }} postulante{{ $oferta->postulaciones_count !== 1 ? 's' : '' }}
                            </div>
                            @if($oferta->fecha_vencimiento)
                                <span class="text-xs {{ $vencida ? 'text-red-500 font-semibold' : 'text-gray-400' }}">
                                    {{ $vencida ? 'Cerrada ' : 'Cierra: ' }}
                                    {{ $oferta->fecha_vencimiento->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Acciones --}}
                    <div class="px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-b-xl">
                        <div class="grid grid-cols-2 gap-2">

                            @php
                            $ofertaJson = json_encode([
                                'id' => $oferta->id,
                                'titulo' => $oferta->titulo,
                                'descripcion' => $oferta->descripcion,
                                'requisitos' => $oferta->requisitos,
                                'especialidad' => $espPrincipal?->nombre,
                                'id_especialidad' => $espPrincipal?->id,
                                'tipo_contrato' => $tipoContrato,
                                'modalidad' => $modalidad,
                                'salario_min' => $oferta->salario_min,
                                'salario_max' => $oferta->salario_max,
                                'experiencia_requerida' => $oferta->experiencia_requerida,
                                'estado' => $estado,
                                'provincia' => $oferta->provincia?->nombre,
                                'id_provincia' => $oferta->provincia_id,
                                'id_localidad' => $oferta->localidad_id,
                                'fecha_vencimiento' => $oferta->fecha_vencimiento?->format('Y-m-d'),
                                'fecha_publicacion' => $oferta->created_at->format('d/m/Y'),
                                'postulaciones_count' => $oferta->postulaciones_count,
                            ]);
                        @endphp
                        <button type="button"
                                onclick='abrirModalVer({{ $ofertaJson }})'
                                class="flex items-center justify-center gap-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-100 px-3 py-2 rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Ver / Editar
                            </button>

                            <a href="{{ route('empresa.postulaciones.index') }}?oferta={{ $oferta->id }}"
                                class="flex items-center justify-center gap-1.5 text-xs font-medium text-cyan-700 bg-cyan-50 border border-cyan-200 hover:bg-cyan-100 px-3 py-2 rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Postulantes
                            </a>

                            <form method="POST" action="{{ route('empresa.ofertas.toggle', $oferta) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="w-full flex items-center justify-center gap-1.5 text-xs font-medium px-3 py-2 rounded-lg border transition
                                    {{ $estado === 'Activa'
                                        ? 'text-yellow-700 bg-yellow-50 border-yellow-200 hover:bg-yellow-100'
                                        : 'text-green-700 bg-green-50 border-green-200 hover:bg-green-100' }}">
                                    @if($estado === 'Activa')
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Pausar
                                    @else
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Activar
                                    @endif
                                </button>
                            </form>

                            <button type="button"
                                onclick="abrirModalEliminar({{ $oferta->id }}, '{{ addslashes($oferta->titulo) }}')"
                                class="flex items-center justify-center gap-1.5 text-xs font-medium text-red-600 bg-red-50 border border-red-200 hover:bg-red-100 px-3 py-2 rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Eliminar
                            </button>

                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        @if($ofertas->hasPages())
            <div class="mt-8">
                {{ $ofertas->links() }}
            </div>
        @endif
    @endif

</div>

{{-- ===== MODAL VER OFERTA ===== --}}
<div id="modalVer" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="cerrarModalVer()"></div>
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">

            <div class="flex items-start justify-between px-6 py-4 border-b border-gray-100 flex-shrink-0">
                <div class="flex items-start gap-3 flex-1 min-w-0">
                    <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-lg font-bold text-gray-800 leading-snug" id="ver_titulo"></h2>
                        <p class="text-sm text-blue-600 font-medium mt-0.5" id="ver_especialidad"></p>
                    </div>
                </div>
                <button onclick="cerrarModalVer()" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100 flex-shrink-0 ml-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="overflow-y-auto flex-1 p-6 space-y-5">
                <div class="flex flex-wrap gap-2" id="ver_badges"></div>
                <div class="flex flex-wrap gap-x-5 gap-y-2 text-sm text-gray-500" id="ver_chips"></div>
                <div id="ver_salario_wrap" class="hidden">
                    <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm font-semibold text-green-800" id="ver_salario"></div>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Descripción</h4>
                    <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line" id="ver_descripcion"></p>
                </div>
                <div id="ver_requisitos_wrap" class="hidden">
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Requisitos</h4>
                    <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line" id="ver_requisitos"></p>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl flex items-center justify-between flex-shrink-0">
                <p class="text-xs text-gray-400" id="ver_fecha_publicacion"></p>
                <div class="flex gap-2">
                    <button type="button" onclick="cerrarModalVer()"
                        class="px-4 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                        Cerrar
                    </button>
                    <button type="button" id="btn_ver_editar"
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-cyan-600 hover:bg-cyan-700 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar oferta
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ===== MODAL EDITAR ===== --}}
<div id="modalEditar" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="cerrarModalEditar()"></div>
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-cyan-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-gray-800">Editar oferta</h2>
                </div>
                <button onclick="cerrarModalEditar()" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" id="formEditar" class="overflow-y-auto flex-1 flex flex-col">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-5 flex-1">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Título <span class="text-red-500">*</span></label>
                        <input type="text" name="titulo" id="edit_titulo" required maxlength="200"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Descripción <span class="text-red-500">*</span></label>
                        <textarea name="descripcion" id="edit_descripcion" rows="4" required
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Requisitos</label>
                        <textarea name="requisitos" id="edit_requisitos" rows="3"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition resize-none"></textarea>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Especialidad <span class="text-red-500">*</span></label>
                            <select name="especialidad_principal" id="edit_id_especialidad" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
                                <option value="">-- Seleccioná --</option>
                                @foreach($especialidades as $esp)
                                    <option value="{{ $esp->id }}">{{ $esp->nombre }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="especialidades[]" id="edit_especialidades_hidden" value="">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Años de experiencia</label>
                            <input type="number" name="experiencia_requerida" id="edit_experiencia" min="0" max="50"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipo de contrato</label>
                            <select name="tipo_contrato" id="edit_tipo_contrato"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
                                <option value="Tiempo completo">Tiempo completo</option>
                                <option value="Medio tiempo">Medio tiempo</option>
                                <option value="Por proyecto">Por proyecto</option>
                                <option value="Pasantía">Pasantía</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Modalidad</label>
                            <select name="modalidad" id="edit_modalidad"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
                                <option value="Presencial">Presencial</option>
                                <option value="Remoto">Remoto</option>
                                <option value="Híbrido">Híbrido</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Salario mínimo (ARS)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                                <input type="number" name="salario_min" id="edit_salario_min" min="0"
                                    class="w-full pl-8 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Salario máximo (ARS)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                                <input type="number" name="salario_max" id="edit_salario_max" min="0"
                                    class="w-full pl-8 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Provincia</label>
                            <select name="provincia_id" id="edit_id_provincia" onchange="cargarLocalidadesEditar(this.value)"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
                                <option value="">-- Seleccioná provincia --</option>
                                @foreach($provincias as $prov)
                                    <option value="{{ $prov->id }}">{{ $prov->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Localidad</label>
                            <select name="localidad_id" id="edit_id_localidad"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
                                <option value="">-- Seleccioná primero una provincia --</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cierre de postulaciones</label>
                        <input type="date" name="fecha_vencimiento" id="edit_fecha_vencimiento" min="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl flex items-center justify-end gap-3 flex-shrink-0">
                    <button type="button" onclick="cerrarModalEditar()"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">Cancelar</button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-cyan-600 hover:bg-cyan-700 rounded-lg transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== MODAL ELIMINAR ===== --}}
<div id="modalEliminar" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <div class="px-6 py-5 border-b border-gray-200 flex items-center gap-3">
            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-900">Eliminar oferta</h3>
                <p class="text-xs text-gray-400 truncate max-w-xs" id="modal-elim-nombre"></p>
            </div>
        </div>
        <form method="POST" id="formEliminar">
            @csrf
            @method('DELETE')
            <div class="px-6 py-5">
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                    <p class="text-xs text-amber-700">La oferta pasará a <span class="font-semibold">Borradores</span> y dejará de ser visible para los trabajadores. Podés restaurarla desde esa sección cuando quieras.</p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl flex justify-end gap-3">
                <button type="button" onclick="cerrarModalEliminar()"
                    class="px-4 py-2.5 border border-gray-300 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-100 transition">Cancelar</button>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Sí, eliminar oferta
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Toast container --}}
<div id="toast-container" class="fixed bottom-6 right-6 z-[9999] flex flex-col gap-3 items-end pointer-events-none [&>*]:pointer-events-auto"></div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ── Toast ─────────────────────────────────────────────────────────────
    function showToast(msg, type) {
        type = type || 'success';
        var id  = 'toast-' + Date.now();
        var cfg = {
            success: { border: 'border-green-200', bar: 'bg-green-500', icon: '<svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' },
            warning: { border: 'border-yellow-200', bar: 'bg-yellow-400', icon: '<svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>' },
            error:   { border: 'border-red-200',   bar: 'bg-red-400',   icon: '<svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' }
        };
        var c = cfg[type] || cfg.success;
        var t = document.createElement('div');
        t.id        = id;
        t.className = 'flex items-center gap-3 bg-white border ' + c.border + ' rounded-2xl shadow-lg px-4 py-3.5 min-w-[280px] max-w-sm translate-x-full opacity-0 transition-all duration-300 ease-out relative overflow-hidden';
        t.innerHTML = c.icon + '<p class="text-sm font-medium text-gray-800 flex-1">' + msg + '</p>' +
            '<button onclick="removeToast(\'' + id + '\')" class="text-gray-400 hover:text-gray-600 ml-1">' +
            '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>' +
            '<div class="absolute bottom-0 left-0 h-0.5 w-full ' + c.bar + ' origin-left" id="bar-' + id + '"></div>';
        document.getElementById('toast-container').appendChild(t);
        requestAnimationFrame(function() {
            requestAnimationFrame(function() {
                t.classList.replace('translate-x-full', 'translate-x-0');
                t.classList.replace('opacity-0', 'opacity-100');
            });
        });
        document.getElementById('bar-' + id).style.cssText = 'transition:transform 4s linear;transform:scaleX(0)';
        setTimeout(function() { removeToast(id); }, 4200);
    }
    function removeToast(id) {
        var el = document.getElementById(id);
        if (!el) return;
        el.classList.add('translate-x-full', 'opacity-0');
        setTimeout(function() { el.remove(); }, 300);
    }

    // ── Toast desde session ──────────────────────────────────────────────
    @if(session('toast'))
        showToast('{{ session('toast') }}', 'success');
    @endif
    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif
    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif

    // ── Ver oferta ───────────────────────────────────────────────────────
    var _ofertaActual = null;

    function abrirModalVer(o) {
        _ofertaActual = o;

        document.getElementById('ver_titulo').textContent       = o.titulo || '';
        document.getElementById('ver_especialidad').textContent  = o.especialidad || '';
        document.getElementById('ver_descripcion').textContent   = o.descripcion || '';

        var reqWrap = document.getElementById('ver_requisitos_wrap');
        if (o.requisitos && o.requisitos.trim()) {
            document.getElementById('ver_requisitos').textContent = o.requisitos;
            reqWrap.classList.remove('hidden');
        } else {
            reqWrap.classList.add('hidden');
        }

        var estadoColor = {
            'Activa':  'bg-green-100 text-green-700',
            'Pausada': 'bg-yellow-100 text-yellow-700',
            'Cerrada': 'bg-red-100 text-red-600',
        };
        var sc = estadoColor[o.estado] || 'bg-gray-100 text-gray-500';
        var badges = '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold ' + sc + '">' + (o.estado || '') + '</span>';
        if (o.tipo_contrato) badges += '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">' + o.tipo_contrato + '</span>';
        if (o.modalidad)    badges += '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-600">' + o.modalidad + '</span>';
        document.getElementById('ver_badges').innerHTML = badges;

        var chips = '';
        if (o.provincia) chips += '<span class="flex items-center gap-1"><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>' + o.provincia + '</span>';
        if (o.experiencia_requerida) chips += '<span class="flex items-center gap-1"><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' + o.experiencia_requerida + ' año' + (o.experiencia_requerida != 1 ? 's' : '') + ' de experiencia</span>';
        if (o.fecha_vencimiento) {
            var vencida = new Date(o.fecha_vencimiento + 'T00:00:00') < new Date(new Date().toDateString());
            var partes = o.fecha_vencimiento.split('-');
            var fmtDate = partes[2] + '/' + partes[1] + '/' + partes[0];
            chips += '<span class="flex items-center gap-1 ' + (vencida ? 'text-red-500 font-semibold' : '') + '"><svg class="w-4 h-4 ' + (vencida ? 'text-red-400' : 'text-gray-400') + '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>' + (vencida ? 'Cerrada: ' : 'Cierre: ') + fmtDate + '</span>';
        }
        document.getElementById('ver_chips').innerHTML = chips;

        var salWrap = document.getElementById('ver_salario_wrap');
        if (o.salario_min || o.salario_max) {
            var sal = ' ';
            function fmtSal(n) { return Number(n).toLocaleString('es-AR'); }
            if (o.salario_min && o.salario_max) sal += '$' + fmtSal(o.salario_min) + ' – $' + fmtSal(o.salario_max) + ' ARS';
            else if (o.salario_min)              sal += 'Desde $' + fmtSal(o.salario_min) + ' ARS';
            else                                 sal += 'Hasta $' + fmtSal(o.salario_max) + ' ARS';
            document.getElementById('ver_salario').textContent = sal;
            salWrap.classList.remove('hidden');
        } else {
            salWrap.classList.add('hidden');
        }

        document.getElementById('ver_fecha_publicacion').textContent = o.fecha_publicacion ? 'Publicada el ' + o.fecha_publicacion : '';

        document.getElementById('btn_ver_editar').onclick = function() {
            cerrarModalVer();
            abrirModalEditar(_ofertaActual);
        };

        document.getElementById('modalVer').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function cerrarModalVer() {
        document.getElementById('modalVer').classList.add('hidden');
        document.body.style.overflow = '';
    }
    document.getElementById('modalVer').addEventListener('click', function(e) {
        if (e.target === this) cerrarModalVer();
    });

    // ── Editar ───────────────────────────────────────────────────────────
    var localidadPendiente = null;

    function abrirModalEditar(o) {
        document.getElementById('formEditar').action = '/empresa/ofertas/' + o.id;
        document.getElementById('edit_titulo').value          = o.titulo || '';
        document.getElementById('edit_descripcion').value     = o.descripcion || '';
        document.getElementById('edit_requisitos').value      = o.requisitos || '';
        document.getElementById('edit_experiencia').value     = o.experiencia_requerida || '';
        document.getElementById('edit_salario_min').value     = o.salario_min || '';
        document.getElementById('edit_salario_max').value     = o.salario_max || '';
        document.getElementById('edit_fecha_vencimiento').value = o.fecha_vencimiento || '';
        setSelect('edit_id_especialidad', o.id_especialidad);
        document.getElementById('edit_especialidades_hidden').value = o.id_especialidad || '';
        setSelect('edit_tipo_contrato', o.tipo_contrato);
        setSelect('edit_modalidad', o.modalidad);
        setSelect('edit_id_provincia', o.id_provincia);
        if (o.id_provincia) {
            localidadPendiente = o.id_localidad;
            cargarLocalidadesEditar(o.id_provincia);
        } else {
            document.getElementById('edit_id_localidad').innerHTML = '<option value="">-- Seleccioná primero una provincia --</option>';
        }
        document.getElementById('modalEditar').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function cerrarModalEditar() {
        document.getElementById('modalEditar').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function setSelect(id, value) {
        var sel = document.getElementById(id);
        if (!sel) return;
        for (var i = 0; i < sel.options.length; i++) {
            sel.options[i].selected = (sel.options[i].value == value);
        }
    }

    function cargarLocalidadesEditar(idProvincia) {
        var sel = document.getElementById('edit_id_localidad');
        sel.innerHTML = '<option value="">Cargando...</option>';
        if (!idProvincia) {
            sel.innerHTML = '<option value="">-- Seleccioná primero una provincia --</option>';
            return;
        }
        fetch('/api/localidades/' + idProvincia)
            .then(function(r) { if (!r.ok) throw new Error(); return r.json(); })
            .then(function(data) {
                sel.innerHTML = '<option value="">-- Todas las localidades --</option>';
                data.forEach(function(loc) {
                    var opt = document.createElement('option');
                    opt.value = loc.id;
                    opt.textContent = loc.nombre;
                    sel.appendChild(opt);
                });
                if (localidadPendiente) {
                    setSelect('edit_id_localidad', localidadPendiente);
                    localidadPendiente = null;
                }
            })
            .catch(function() {
                sel.innerHTML = '<option value="">Error al cargar</option>';
            });
    }

    document.getElementById('formEditar').addEventListener('submit', function(e) {
        var min = parseFloat(document.getElementById('edit_salario_min').value) || 0;
        var max = parseFloat(document.getElementById('edit_salario_max').value) || 0;
        if (min > 0 && max > 0 && min > max) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Salario inválido', text: 'El mínimo no puede ser mayor al máximo.', confirmButtonColor: '#0891b2' });
        }
    });

    document.getElementById('modalEditar').addEventListener('click', function(e) {
        if (e.target === this) cerrarModalEditar();
    });

    // ── Eliminar ─────────────────────────────────────────────────────────
    function abrirModalEliminar(id, titulo) {
        document.getElementById('modal-elim-nombre').textContent = titulo;
        document.getElementById('formEliminar').action = '/empresa/ofertas/' + id;
        document.getElementById('modalEliminar').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function cerrarModalEliminar() {
        document.getElementById('modalEliminar').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    document.getElementById('modalEliminar').addEventListener('click', function(e) {
        if (e.target === this) cerrarModalEliminar();
    });

    // ── Escape global ────────────────────────────────────────────────────
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModalVer();
            cerrarModalEditar();
            cerrarModalEliminar();
        }
    });
</script>
@endpush
