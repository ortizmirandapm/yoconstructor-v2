@php
    $currentRoute = request()->route()->getName();
@endphp

@extends('layouts.empresa')

@section('title', 'Postulantes')
@section('subtitle', $oferta->titulo)

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-400 mb-1">
                <a href="{{ route('empresa.ofertas.index') }}" class="hover:text-cyan-600 transition">Mis ofertas</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-600 font-medium truncate max-w-xs">{{ $oferta->titulo }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Postulantes</h1>
            <p class="text-gray-500 mt-1 text-sm">{{ $total }} postulante{{ $total !== 1 ? 's' : '' }} en total</p>
        </div>
        <a href="{{ route('empresa.ofertas.index') }}"
            class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 px-4 py-2 rounded-lg transition self-start">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver a ofertas
        </a>
    </div>

    {{-- Filtros pills --}}
    <div class="flex flex-wrap gap-2 mb-8">
        @php
            $pills = [
                ''           => ['label' => 'Todos',            'cnt' => $total,                'active' => 'bg-teal-600 text-white border-teal-600',   'dot' => ''],
                'Pendiente'  => ['label' => 'Pendiente',         'cnt' => $counts['Pendiente'],  'active' => 'bg-yellow-500 text-white border-yellow-500', 'dot' => 'bg-yellow-400'],
                'Revisada'   => ['label' => 'Revisada',          'cnt' => $counts['Revisada'],   'active' => 'bg-blue-500 text-white border-blue-500',    'dot' => 'bg-blue-400'],
                'Entrevista' => ['label' => 'Pre-seleccionado',  'cnt' => $counts['Entrevista'], 'active' => 'bg-purple-500 text-white border-purple-500', 'dot' => 'bg-purple-500'],
                'Aceptada'   => ['label' => 'Aceptada',          'cnt' => $counts['Aceptada'],   'active' => 'bg-green-500 text-white border-green-500',  'dot' => 'bg-green-500'],
                'Rechazada'  => ['label' => 'Rechazada',         'cnt' => $counts['Rechazada'],  'active' => 'bg-red-500 text-white border-red-500',      'dot' => 'bg-red-400'],
            ];
        @endphp
        @foreach($pills as $val => $pi)
            @php
                $activo = $filtroEstado === $val;
                $href = route('empresa.postulaciones.index') . '?oferta=' . $oferta->id . ($val ? '&estado=' . $val : '');
            @endphp
            <a href="{{ $href }}"
                class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-medium border transition
                {{ $activo ? $pi['active'] : 'bg-white text-gray-600 border-gray-300 hover:border-gray-400' }}">
                @if($val)
                    <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $activo ? 'bg-white opacity-70' : $pi['dot'] }}"></span>
                @endif
                {{ $pi['label'] }}
                <span class="{{ $activo ? 'bg-white bg-opacity-25 text-white' : 'bg-gray-100 text-gray-600' }} text-xs font-bold px-1.5 py-0.5 rounded-full">
                    {{ $pi['cnt'] }}
                </span>
            </a>
        @endforeach
    </div>

    {{-- Cards --}}
    @if($postulantes->isEmpty())
        <div class="bg-white border border-dashed border-gray-300 rounded-xl p-16 text-center">
            <svg class="w-14 h-14 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <p class="text-gray-400 font-medium text-lg mb-1">Sin postulantes{{ $filtroEstado ? ' en este estado' : '' }}</p>
            <p class="text-gray-400 text-sm">Cuando alguien se postule aparecerá aquí</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach($postulantes as $p)
                @php
                    $estadoCfg = match($p->estado->value) {
                        'Pendiente'  => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-300', 'dot' => 'bg-yellow-400', 'label' => 'Pendiente'],
                        'Revisada'   => ['bg' => 'bg-blue-100',   'text' => 'text-blue-800',   'border' => 'border-blue-300',   'dot' => 'bg-blue-400',   'label' => 'Revisada'],
                        'Entrevista' => ['bg' => 'bg-purple-100',  'text' => 'text-purple-800', 'border' => 'border-purple-300', 'dot' => 'bg-purple-500', 'label' => 'Pre-seleccionado'],
                        'Aceptada'   => ['bg' => 'bg-green-100',   'text' => 'text-green-800',  'border' => 'border-green-300',  'dot' => 'bg-green-500',  'label' => 'Aceptada'],
                        'Rechazada'  => ['bg' => 'bg-red-100',     'text' => 'text-red-800',    'border' => 'border-red-300',    'dot' => 'bg-red-400',    'label' => 'Rechazada'],
                    };
                    $nombre = $p->trabajador->nombre . ' ' . $p->trabajador->apellido;
                    $espPrincipal = $p->trabajador->especialidades->firstWhere('pivot.es_principal', 1) ?? $p->trabajador->especialidades->first();
                    $cvPath = $p->trabajador->curriculum_pdf;
                    $edad = null;
                    if ($p->trabajador->fecha_nacimiento) {
                        $edad = $p->trabajador->fecha_nacimiento->age;
                    }
                    $ddId = 'dd-' . $p->id;
                @endphp
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col">

                    {{-- Header card --}}
                    <div class="px-5 pt-5 pb-4 border-b border-gray-100">
                        <div class="flex justify-end mb-3">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $estadoCfg['bg'] }} {{ $estadoCfg['text'] }} border {{ $estadoCfg['border'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $estadoCfg['dot'] }}"></span>
                                {{ $estadoCfg['label'] }}
                            </span>
                        </div>

                        <div class="flex items-start gap-4">
                            @if($p->trabajador->imagen_perfil)
                                <img src="{{ asset('uploads/perfil/' . $p->trabajador->imagen_perfil) }}" class="w-14 h-14 rounded-full object-cover border-2 border-gray-200 flex-shrink-0" alt="foto">
                            @else
                                <img src="{{ asset('img/profile.png') }}" class="w-14 h-14 rounded-full object-cover border-2 border-gray-200 flex-shrink-0" alt="foto">
                            @endif
                            <div class="min-w-0 flex-1">
                                <h3 class="text-base font-bold text-gray-900 leading-tight">{{ $nombre }}</h3>
                                @if($p->trabajador->nombre_titulo)
                                    <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $p->trabajador->nombre_titulo }}</p>
                                @endif
                                <div class="mt-2">
                                    @if($cvPath)
                                        @php
                                            $cvSrc = str_starts_with($cvPath, 'uploads/') ? $cvPath : 'uploads/cv/' . $cvPath;
                                        @endphp
                                        <a href="{{ asset($cvSrc) }}" target="_blank"
                                            class="inline-flex items-center gap-1 text-xs font-semibold text-cyan-600 hover:text-cyan-700 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Ver CV
                                        </a>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs text-gray-400">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Sin CV
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Body card --}}
                    <div class="px-5 py-4 flex-1 space-y-4">
                        @if($p->trabajador->descripcion)
                            <p class="text-sm text-gray-600 leading-relaxed line-clamp-3">{{ $p->trabajador->descripcion }}</p>
                        @else
                            <p class="text-sm text-gray-400 italic">Sin descripción</p>
                        @endif

                        <div class="space-y-2.5">
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm">
                                    <span class="text-gray-400">Experiencia:</span>
                                    <span class="text-gray-700 font-medium ml-1">
                                        {{ $p->trabajador->anios_experiencia ? $p->trabajador->anios_experiencia . ' año' . ($p->trabajador->anios_experiencia != 1 ? 's' : '') : 'No especificada' }}
                                    </span>
                                </p>
                            </div>

                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <p class="text-sm">
                                    <span class="text-gray-400">Edad:</span>
                                    <span class="text-gray-700 font-medium ml-1">
                                        {{ $edad ? $edad . ' años' : 'No especificada' }}
                                    </span>
                                </p>
                            </div>

                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <span class="text-sm text-gray-400">Especialidad principal:</span>
                                    @if($espPrincipal)
                                        <span class="text-sm text-gray-700 font-medium">{{ $espPrincipal->nombre }}</span>
                                    @else
                                        <span class="text-sm text-gray-700 font-medium">No especificada</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-sm">
                                    <span class="text-gray-400">Postulado:</span>
                                    <span class="text-gray-700 font-medium ml-1">{{ $p->created_at->diffForHumans() }}</span>
                                </p>
                            </div>
                        </div>

                        @if($p->notas_empresa)
                            <div class="px-3 py-2 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800 flex items-start gap-2">
                                <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span class="line-clamp-2">{{ $p->notas_empresa }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Footer card --}}
                    <div class="px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl flex items-center gap-2">

                        {{-- Dropdown acciones --}}
                        <div class="relative flex-1">
                            <button onclick="toggleDropdown('{{ $ddId }}')"
                                class="w-full inline-flex items-center justify-between gap-2 text-sm font-medium px-3 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                    Acción
                                </span>
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div id="{{ $ddId }}"
                                class="hidden absolute bottom-full left-0 mb-1 w-52 bg-white border border-gray-200 rounded-xl shadow-xl z-30 py-1">
                                @php
                                    $opciones = [
                                        'Revisada'   => ['label' => 'Marcar como visto',  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>', 'color' => 'text-blue-600',   'hover' => 'hover:bg-blue-50'],
                                        'Entrevista' => ['label' => 'Pre-seleccionar',     'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>', 'color' => 'text-purple-600', 'hover' => 'hover:bg-purple-50'],
                                        'Aceptada'   => ['label' => 'Aceptar postulante',  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>', 'color' => 'text-green-600',  'hover' => 'hover:bg-green-50'],
                                        'Rechazada'  => ['label' => 'Rechazar postulante', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>', 'color' => 'text-red-600',    'hover' => 'hover:bg-red-50'],
                                        'Pendiente'  => ['label' => 'Volver a Pendiente',  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>', 'color' => 'text-yellow-600', 'hover' => 'hover:bg-yellow-50'],
                                    ];
                                @endphp
                                @foreach($opciones as $estVal => $op)
                                    @if($estVal === $p->estado->value) @continue @endif
                                    <form method="POST" action="{{ route('empresa.postulaciones.estado', $p) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="estado" value="{{ $estVal }}">
                                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm {{ $op['color'] }} {{ $op['hover'] }} transition">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $op['icon'] !!}</svg>
                                            {{ $op['label'] }}
                                        </button>
                                    </form>
                                @endforeach

                                <div class="border-t border-gray-100 mt-1 pt-1">
                                    <button onclick="abrirNotas({{ $p->id }}, `{!! addslashes($p->notas_empresa ?? '') !!}`); cerrarDropdown('{{ $ddId }}')"
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 transition">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Agregar / editar nota
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Ver perfil --}}
                        <a href="{{ route('empresa.postulaciones.perfil', ['trabajador' => $p->trabajador_id, 'from' => 'postulantes', 'oferta' => $oferta->id]) }}"
                            class="inline-flex items-center gap-1.5 text-sm px-3 py-2 border border-gray-300 text-gray-600 bg-white hover:bg-gray-50 rounded-lg transition font-medium whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Perfil
                        </a>

                        {{-- Contactar --}}
                        <button onclick="abrirContacto('{{ addslashes($p->trabajador->user->email) }}','{{ addslashes($p->trabajador->telefono ?? '') }}','{{ addslashes($nombre) }}')"
                            class="inline-flex items-center gap-1.5 text-sm px-3 py-2 border border-cyan-300 text-cyan-700 bg-white hover:bg-cyan-50 rounded-lg transition font-medium whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Contactar
                        </button>

                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>

{{-- ===== MODAL CONTACTAR ===== --}}
<div id="modalContacto" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="font-bold text-gray-900">Datos de contacto</h3>
            <button onclick="cerrarContacto()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <p id="contacto-nombre" class="font-semibold text-gray-800"></p>
            <div class="space-y-3">
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                    <svg class="w-5 h-5 text-cyan-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs text-gray-400">Email</p>
                        <a id="contacto-email" href="#" class="text-sm font-medium text-cyan-600 hover:underline truncate block"></a>
                    </div>
                    <button onclick="copiar('contacto-email-txt')" class="flex-shrink-0 text-gray-400 hover:text-gray-600" title="Copiar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </button>
                    <span id="contacto-email-txt" class="hidden"></span>
                </div>
                <div id="contacto-tel-wrap" class="hidden items-center gap-3 p-3 bg-gray-50 rounded-lg">
                    <svg class="w-5 h-5 text-cyan-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs text-gray-400">Teléfono</p>
                        <p id="contacto-tel" class="text-sm font-medium text-gray-800"></p>
                    </div>
                    <button onclick="copiar('contacto-tel-txt')" class="flex-shrink-0 text-gray-400 hover:text-gray-600" title="Copiar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </button>
                    <span id="contacto-tel-txt" class="hidden"></span>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-xl flex justify-end">
            <button onclick="cerrarContacto()" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg text-sm hover:bg-gray-100 transition">Cerrar</button>
        </div>
    </div>
</div>

{{-- ===== MODAL NOTAS ===== --}}
<div id="modalNotas" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="font-bold text-gray-900">Notas internas</h3>
            <button onclick="cerrarNotas()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form method="POST" id="formNotas">
            @csrf
            <input type="hidden" name="postulacion_id" id="notas-id">
            <div class="px-6 py-5">
                <p class="text-xs text-gray-400 mb-3">Estas notas solo son visibles para tu empresa.</p>
                <textarea name="notas" id="notas-texto" rows="5" maxlength="1000"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 resize-none"
                    placeholder="Ej: Candidato con buen perfil, pendiente entrevista técnica..."></textarea>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-xl flex justify-end gap-3">
                <button type="button" onclick="cerrarNotas()" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg text-sm hover:bg-gray-100 transition">Cancelar</button>
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg text-sm font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Guardar nota
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Toast container --}}
<div id="toast-container" class="fixed bottom-6 right-6 z-[9999] flex flex-col gap-3 items-end pointer-events-none [&>*]:pointer-events-auto"></div>

@endsection

@push('scripts')
<script>
    // ── Toast ─────────────────────────────────────────────────────────────
    function showToast(msg, type) {
        type = type || 'success';
        var id  = 'toast-' + Date.now();
        var cfg = {
            success: { border: 'border-green-200', bar: 'bg-green-500', icon: '<svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' },
            error:   { border: 'border-red-200',   bar: 'bg-red-400',   icon: '<svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' }
        };
        var c = cfg[type] || cfg.success;
        var t = document.createElement('div');
        t.id        = id;
        t.className = 'flex items-center gap-3 bg-white border ' + c.border + ' rounded-2xl shadow-lg px-4 py-3.5 min-w-[280px] max-w-sm translate-x-full opacity-0 transition-all duration-300 ease-out relative overflow-hidden pointer-events-auto';
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

    // ── Dropdown ─────────────────────────────────────────────────────────
    function toggleDropdown(id) {
        var el = document.getElementById(id);
        var isHidden = el.classList.contains('hidden');
        document.querySelectorAll('[id^="dd-"]').forEach(function(d) { d.classList.add('hidden'); });
        if (isHidden) el.classList.remove('hidden');
    }
    function cerrarDropdown(id) {
        var el = document.getElementById(id);
        if (el) el.classList.add('hidden');
    }
    document.addEventListener('click', function(e) {
        if (!e.target.closest('[id^="dd-"]') && !e.target.closest('button[onclick^="toggleDropdown"]')) {
            document.querySelectorAll('[id^="dd-"]').forEach(function(d) { d.classList.add('hidden'); });
        }
    });

    // ── Contactar ────────────────────────────────────────────────────────
    function abrirContacto(email, tel, nombre) {
        document.getElementById('contacto-nombre').textContent = nombre;
        document.getElementById('contacto-email').textContent  = email;
        document.getElementById('contacto-email').href         = 'mailto:' + email;
        document.getElementById('contacto-email-txt').textContent = email;
        var telWrap = document.getElementById('contacto-tel-wrap');
        if (tel) {
            document.getElementById('contacto-tel').textContent = tel;
            document.getElementById('contacto-tel-txt').textContent = tel;
            telWrap.classList.remove('hidden');
            telWrap.classList.add('flex');
        } else {
            telWrap.classList.add('hidden');
            telWrap.classList.remove('flex');
        }
        document.getElementById('modalContacto').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function cerrarContacto() {
        document.getElementById('modalContacto').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    document.getElementById('modalContacto').addEventListener('click', function(e) {
        if (e.target === this) cerrarContacto();
    });

    // ── Notas ────────────────────────────────────────────────────────────
    function abrirNotas(id, texto) {
        document.getElementById('notas-id').value    = id;
        document.getElementById('notas-texto').value = texto || '';
        document.getElementById('formNotas').action  = '/empresa/postulaciones/' + id + '/notas';
        document.getElementById('modalNotas').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function cerrarNotas() {
        document.getElementById('modalNotas').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    document.getElementById('modalNotas').addEventListener('click', function(e) {
        if (e.target === this) cerrarNotas();
    });

    // ── Copiar ───────────────────────────────────────────────────────────
    function copiar(id) {
        var text = document.getElementById(id).textContent;
        navigator.clipboard.writeText(text).then(function() {
            showToast('Copiado al portapapeles');
        });
    }

    // ── Escape global ────────────────────────────────────────────────────
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarContacto();
            cerrarNotas();
        }
    });
</script>
@endpush