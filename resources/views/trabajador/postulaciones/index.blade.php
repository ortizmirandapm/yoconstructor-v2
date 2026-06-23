@extends('layouts.trabajador')

@section('header')

@endsection

@section('content')
    @php
        $estadosValidos = ['Pendiente', 'Revisada', 'Entrevista', 'Aceptada', 'Rechazada'];
        $total = array_sum($counts);
        $defaultCounts = array_fill_keys($estadosValidos, 0);
        $counts = array_merge($defaultCounts, $counts);

        $estadoConfig = [
            'Pendiente'  => ['bg'=>'bg-yellow-100','text'=>'text-yellow-800','border'=>'border-yellow-300','dot'=>'bg-yellow-400','label'=>'Pendiente'],
            'Revisada'   => ['bg'=>'bg-blue-100',  'text'=>'text-blue-800',  'border'=>'border-blue-300',  'dot'=>'bg-blue-400',  'label'=>'Vista por empresa'],
            'Entrevista' => ['bg'=>'bg-purple-100','text'=>'text-purple-800','border'=>'border-purple-300','dot'=>'bg-purple-500','label'=>'Entrevista'],
            'Aceptada'   => ['bg'=>'bg-green-100', 'text'=>'text-green-800', 'border'=>'border-green-300', 'dot'=>'bg-green-500', 'label'=>'Aceptada'],
            'Rechazada'  => ['bg'=>'bg-red-100',   'text'=>'text-red-800',   'border'=>'border-red-300',   'dot'=>'bg-red-400',   'label'=>'Rechazada'],
        ];

        $filtroLabels = [
            'Pendiente'  => 'Pendiente',
            'Revisada'   => 'Vista por empresa',
            'Entrevista' => 'Entrevista',
            'Aceptada'   => 'Aceptada',
            'Rechazada'  => 'Rechazada',
        ];

        $estadoMensajes = [
            'Pendiente'  => 'Tu postulación está siendo revisada por la empresa.',
            'Revisada'   => 'La empresa ya vio tu postulación.',
            'Entrevista' => 'La empresa quiere hacerte una entrevista. Revisá tu email.',
            'Aceptada'   => '¡Felicitaciones! Fuiste aceptado para este puesto.',
            'Rechazada'  => 'La empresa no seleccionó tu postulación para este puesto.',
        ];
    @endphp

    @if(session('success'))
        <div class="mb-5 flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-medium">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 flex items-center gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm font-medium">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900">Mis postulaciones</h2>
            <p class="mt-1 text-sm text-gray-500">{{ $total }} postulación{{ $total !== 1 ? 'es' : '' }} en total</p>
        </div>
    </div>

    {{-- Filtro por estado --}}
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('trabajador.postulaciones.index') }}"
            class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-medium border transition
                {{ !$filtroEstado ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300 hover:border-gray-400' }}">
            Todos
            <span class="{{ !$filtroEstado ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-600' }} text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $total }}</span>
        </a>
        @foreach($estadosValidos as $val)
            @php $activo = $filtroEstado === $val; @endphp
            <a href="{{ route('trabajador.postulaciones.index', ['estado' => $val]) }}"
                class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-medium border transition
                    {{ $activo ? $estadoConfig[$val]['bg'] . ' ' . $estadoConfig[$val]['text'] . ' border-transparent' : 'bg-white text-gray-600 border-gray-300 hover:border-gray-400' }}">
                <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $activo ? 'bg-white opacity-80' : $estadoConfig[$val]['dot'] }}"></span>
                {{ $estadoConfig[$val]['label'] }}
                <span class="{{ $activo ? 'bg-white bg-opacity-30 text-white' : 'bg-gray-100 text-gray-600' }} text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $counts[$val] }}</span>
            </a>
        @endforeach
    </div>

    {{-- Listado --}}
    @if($postulaciones->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <svg class="w-16 h-16 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
            </svg>
            <p class="text-gray-400 font-semibold text-lg mb-1">Sin postulaciones{{ $filtroEstado ? ' en este estado' : '' }}</p>
            <p class="text-gray-400 text-sm mb-5">Explorá las ofertas disponibles y comenzá a postularte</p>
            <a href="{{ route('ofertas.index') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition shadow-md">
                Explorar ofertas laborales
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($postulaciones as $p)
                @php
                    $cfg = $estadoConfig[$p->estado] ?? $estadoConfig['Pendiente'];
                    $ofertaVencida = $p->oferta->fecha_vencimiento && $p->oferta->fecha_vencimiento->isPast();
                    $ofertaInactiva = $p->oferta->estado !== 'Activa';

                    $logoUrl = $p->oferta->empresa->logo
                        ? asset('uploads/logos/' . $p->oferta->empresa->logo)
                        : asset('img/profile.png');

                    $ofertaData = [
                        'titulo'        => $p->oferta->titulo,
                        'empresa'       => $p->oferta->empresa->nombre_empresa ?? '',
                        'descripcion'   => $p->oferta->descripcion ?? '',
                        'requisitos'    => $p->oferta->requisitos ?? '',
                        'tipo_contrato' => $p->oferta->tipo_contrato ?? '',
                        'modalidad'     => $p->oferta->modalidad ?? '',
                        'salario_min'   => $p->oferta->salario_min,
                        'salario_max'   => $p->oferta->salario_max,
                        'provincia'     => $p->oferta->provincia?->nombre ?? '',
                        'localidad'     => $p->oferta->localidad?->nombre ?? '',
                        'especialidad'  => $p->oferta->especialidades->first()?->nombre ?? '',
                        'experiencia'   => $p->oferta->experiencia_requerida ?? '',
                        'vencimiento'   => $p->oferta->fecha_vencimiento?->format('d/m/Y') ?? '',
                        'estado_oferta' => $p->oferta->estado,
                        'logo'          => $logoUrl,
                        'id_oferta'     => $p->oferta->id,
                        'estado_post'   => $p->estado,
                        'fecha_post'    => $p->created_at->diffForHumans(),
                    ];
                @endphp

                {{-- Card --}}
                <div class="group relative border border-gray-200 rounded-2xl hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 bg-white overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-0.5 bg-blue-600 scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                    <div class="p-5">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 rounded-xl overflow-hidden border border-gray-100 bg-gray-50">
                                    <img src="{{ $logoUrl }}" class="w-full h-full object-contain p-1" alt="logo">
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-start justify-between gap-2 mb-1">
                                    <div>
                                        <h3 class="text-base font-bold text-gray-900 leading-tight group-hover:text-blue-600 transition-colors">
                                            {{ $p->oferta->titulo }}
                                        </h3>
                                        <p class="text-sm text-blue-600 font-semibold mt-0.5">{{ $p->oferta->empresa->nombre_empresa ?? 'Empresa' }}</p>
                                    </div>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $cfg['bg'] }} {{ $cfg['text'] }} border {{ $cfg['border'] }} flex-shrink-0">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }}"></span>
                                        {{ $cfg['label'] }}
                                    </span>
                                </div>

                                {{-- Tags --}}
                                <div class="flex flex-wrap gap-1.5 my-3">
                                    @if($p->oferta->localidad?->nombre || $p->oferta->provincia?->nombre)
                                        <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            {{ $p->oferta->localidad?->nombre ? $p->oferta->localidad->nombre . ', ' . $p->oferta->provincia->nombre : $p->oferta->provincia->nombre }}
                                        </span>
                                    @endif
                                    @if($p->oferta->tipo_contrato)
                                        <span class="inline-flex items-center gap-1 text-xs bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full border border-blue-100">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            {{ $p->oferta->tipo_contrato }}
                                        </span>
                                    @endif
                                    @if($p->oferta->modalidad)
                                        <span class="inline-flex items-center gap-1 text-xs bg-purple-50 text-purple-700 px-2.5 py-1 rounded-full border border-purple-200">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                            {{ $p->oferta->modalidad }}
                                        </span>
                                    @endif
                                    @php $primeraEsp = $p->oferta->especialidades->first(); @endphp
                                    @if($primeraEsp)
                                        <span class="inline-flex items-center gap-1 text-xs bg-orange-50 text-orange-700 px-2.5 py-1 rounded-full border border-orange-200">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            {{ $primeraEsp->nombre }}
                                        </span>
                                    @endif
                                    @if($p->oferta->salario_min || $p->oferta->salario_max)
                                        @php
                                            $salMin = $p->oferta->salario_min ? '$' . number_format($p->oferta->salario_min / 1000, 0) . 'k' : '';
                                            $salMax = $p->oferta->salario_max ? '$' . number_format($p->oferta->salario_max / 1000, 0) . 'k' : '';
                                            $salTxt = $salMin && $salMax ? $salMin . ' – ' . $salMax . ' ARS' : ($salMin ?: $salMax) . ' ARS';
                                        @endphp
                                        <span class="inline-flex items-center gap-1 text-xs bg-green-50 text-green-700 px-2.5 py-1 rounded-full border border-green-200 font-medium">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            {{ $salTxt }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Bottom row --}}
                                <div class="flex flex-wrap items-center justify-between gap-2 pt-3 border-t border-gray-100">
                                    <div class="flex items-center gap-2 text-xs text-gray-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>Postulado {{ $p->created_at->diffForHumans() }}</span>
                                        @if($ofertaInactiva)
                                            <span class="inline-flex items-center gap-1 text-orange-500 font-medium">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                Oferta pausada
                                            </span>
                                        @elseif($ofertaVencida)
                                            <span class="inline-flex items-center gap-1 text-red-400 font-medium">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Oferta vencida
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button onclick='abrirOferta(@json($ofertaData))'
                                            class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 border border-gray-300 text-gray-600 hover:bg-gray-50 hover:border-gray-400 rounded-lg transition font-medium">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Ver oferta
                                        </button>
                                        @if($p->estado === 'Pendiente')
                                            <button onclick="confirmarCancelar({{ $p->id }}, '{{ addslashes($p->oferta->titulo) }}')"
                                                class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 border border-red-300 text-red-600 hover:bg-red-50 rounded-lg transition font-medium">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Cancelar
                                            </button>
                                        @elseif($p->estado === 'Aceptada')
                                            <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 bg-green-100 text-green-700 rounded-lg font-semibold">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                ¡Felicitaciones!
                                            </span>
                                        @elseif($p->estado === 'Entrevista')
                                            <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 bg-purple-100 text-purple-700 rounded-lg font-semibold">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                Revisá tu email
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($postulaciones->hasPages())
            <div class="mt-6">{{ $postulaciones->appends(request()->query())->links() }}</div>
        @endif
    @endif

    {{-- ============================== MODAL VER OFERTA ============================== --}}
    <div id="modalOferta" class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl overflow-hidden border border-gray-100 bg-gray-50 flex-shrink-0">
                        <img id="mo-logo" src="" class="w-full h-full object-contain p-1" alt="logo">
                    </div>
                    <div>
                        <h3 id="mo-titulo" class="text-lg font-extrabold text-gray-900 leading-tight"></h3>
                        <p id="mo-empresa" class="text-sm text-blue-600 font-semibold"></p>
                    </div>
                </div>
                <button onclick="cerrarOferta()" class="text-gray-400 hover:text-gray-600 transition ml-4 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="overflow-y-auto flex-1 px-6 py-5 space-y-5">
                <div id="mo-chips" class="flex flex-wrap gap-2"></div>
                <div id="mo-salario-wrap" class="hidden bg-green-50 border border-green-200 rounded-xl px-4 py-3 flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span id="mo-salario" class="text-green-800 font-semibold text-sm"></span>
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-sm font-bold text-gray-700">Descripción</p>
                    </div>
                    <p id="mo-descripcion" class="text-sm text-gray-600 leading-relaxed whitespace-pre-line"></p>
                </div>
                <div id="mo-req-wrap" class="hidden">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        <p class="text-sm font-bold text-gray-700">Requisitos</p>
                    </div>
                    <p id="mo-requisitos" class="text-sm text-gray-600 leading-relaxed whitespace-pre-line"></p>
                </div>
                <div id="mo-estado-wrap" class="rounded-xl p-4 border"></div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 flex justify-between items-center flex-shrink-0 bg-gray-50 rounded-b-2xl">
                <span id="mo-fecha-post" class="text-xs text-gray-400"></span>
                <div class="flex gap-3">
                    <button onclick="cerrarOferta()" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-xl text-sm hover:bg-gray-100 transition font-medium">Cerrar</button>
                    <a id="mo-link" href="#" target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Ver página completa
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================== MODAL CANCELAR ============================== --}}
    <div id="modalCancelar" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="text-lg font-extrabold text-gray-900">Cancelar postulación</h3>
            </div>
            <div class="px-6 py-5">
                <p class="text-gray-600 text-sm">¿Estás seguro que querés cancelar tu postulación a:</p>
                <p id="modalOfertaTitulo" class="font-semibold text-gray-900 mt-2 text-sm bg-gray-50 px-3 py-2 rounded-xl border border-gray-100"></p>
                <p class="text-gray-400 text-xs mt-3 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Esta acción no se puede deshacer.
                </p>
            </div>
            <form id="formCancelar" method="POST" action="">
                @csrf
                <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50 rounded-b-2xl">
                    <button type="button" onclick="cerrarModalCancelar()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-100 transition text-sm font-medium">
                        No, mantener
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition text-sm font-semibold shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Sí, cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    const estadoConfig = @json($estadoConfig);
    const estadoMensajes = @json($estadoMensajes);

    function confirmarCancelar(id, titulo) {
        document.getElementById('formCancelar').action = '{{ route('trabajador.postulaciones.cancelar', '_id_') }}'.replace('_id_', id);
        document.getElementById('modalOfertaTitulo').textContent = titulo;
        document.getElementById('modalCancelar').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function cerrarModalCancelar() {
        document.getElementById('modalCancelar').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function abrirOferta(o) {
        document.getElementById('mo-logo').src = o.logo;
        document.getElementById('mo-titulo').textContent = o.titulo;
        document.getElementById('mo-empresa').textContent = o.empresa;
        document.getElementById('mo-descripcion').textContent = o.descripcion || 'Sin descripción';
        document.getElementById('mo-link').href = '{{ route('ofertas.show', '_id_') }}'.replace('_id_', o.id_oferta);
        document.getElementById('mo-fecha-post').textContent = 'Postulado: ' + o.fecha_post;

        const reqWrap = document.getElementById('mo-req-wrap');
        if (o.requisitos) {
            document.getElementById('mo-requisitos').textContent = o.requisitos;
            reqWrap.classList.remove('hidden');
        } else { reqWrap.classList.add('hidden'); }

        const salWrap = document.getElementById('mo-salario-wrap');
        if (o.salario_min || o.salario_max) {
            let txt = '$' + Math.round((o.salario_min || o.salario_max) / 1000) + 'k ARS';
            if (o.salario_min && o.salario_max) txt = '$' + Math.round(o.salario_min/1000) + 'k – $' + Math.round(o.salario_max/1000) + 'k ARS';
            document.getElementById('mo-salario').textContent = txt;
            salWrap.classList.remove('hidden');
        } else { salWrap.classList.add('hidden'); }

        const chips = document.getElementById('mo-chips');
        chips.innerHTML = '';
        const addChip = (txt, cls) => {
            if (!txt) return;
            const s = document.createElement('span');
            s.className = 'inline-flex items-center text-xs px-2.5 py-1 rounded-full border font-medium ' + cls;
            s.textContent = txt;
            chips.appendChild(s);
        };
        addChip(o.localidad ? o.localidad + ', ' + o.provincia : o.provincia, 'bg-gray-100 text-gray-600 border-gray-200');
        addChip(o.tipo_contrato,  'bg-blue-50 text-blue-700 border-blue-100');
        addChip(o.modalidad,      'bg-purple-50 text-purple-700 border-purple-200');
        addChip(o.especialidad,   'bg-orange-50 text-orange-700 border-orange-200');
        addChip(o.experiencia ? o.experiencia + ' de experiencia' : null, 'bg-gray-100 text-gray-600 border-gray-200');
        if (o.vencimiento) addChip('Vence: ' + o.vencimiento, 'bg-red-50 text-red-600 border-red-200');

        const cfg = estadoConfig[o.estado_post] || estadoConfig['Pendiente'];
        const msg = estadoMensajes[o.estado_post] || '';
        const eWrap = document.getElementById('mo-estado-wrap');
        eWrap.className = 'rounded-xl p-4 border ' + cfg.bg + ' ' + cfg.border;
        eWrap.innerHTML = `<div class="flex items-center gap-2 mb-1">
            <span class="w-2 h-2 rounded-full flex-shrink-0 ${cfg.dot}"></span>
            <span class="text-sm font-semibold ${cfg.text}">${cfg.label}</span>
        </div>
        <p class="text-sm ${cfg.text} opacity-80">${msg}</p>`;

        document.getElementById('modalOferta').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function cerrarOferta() {
        document.getElementById('modalOferta').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') { cerrarModalCancelar(); cerrarOferta(); }
    });
    document.getElementById('modalCancelar')?.addEventListener('click', function(e) { if (e.target === this) cerrarModalCancelar(); });
    document.getElementById('modalOferta')?.addEventListener('click', function(e) { if (e.target === this) cerrarOferta(); });
    </script>
@endsection
