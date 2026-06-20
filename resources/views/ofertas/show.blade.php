@extends('layouts.public')

@section('title', $oferta->titulo . ' - YoConstructor')

@section('content')
<nav class="w-full px-6 py-3 bg-gray-50 border-b border-gray-200">
    <ol class="flex items-center space-x-2 text-sm text-gray-500 max-w-7xl mx-auto">
        <li><a href="/" class="hover:text-blue-600 transition">Inicio</a></li>
        <li><span class="text-gray-300">/</span></li>
        <li><a href="{{ route('ofertas.index') }}" class="hover:text-blue-600 transition">Ofertas</a></li>
        <li><span class="text-gray-300">/</span></li>
        <li class="text-gray-700 font-semibold truncate max-w-[200px]">{{ $oferta->titulo }}</li>
    </ol>
</nav>

<main class="max-w-7xl mx-auto px-4 py-10 flex-1 w-full">
    @if (session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-5 py-3.5 rounded-xl flex items-center gap-3 shadow-sm">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if (session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-5 py-3.5 rounded-xl flex items-center gap-3 shadow-sm">
        <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="text-sm font-medium">{{ session('error') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div class="space-y-1">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 leading-tight">{{ $oferta->titulo }}</h1>
                        <p class="text-base text-blue-600 font-semibold">{{ $oferta->empresa?->nombre_empresa ?? 'Empresa confidencial' }}</p>
                        <div class="flex flex-wrap gap-2 pt-1">
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-semibold border border-blue-100">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ $oferta->tipo_contrato }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-xs font-semibold border border-purple-100">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                {{ $oferta->modalidad }}
                            </span>
                            @if ($oferta->provincia)
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $oferta->localidad?->nombre ? $oferta->localidad->nombre . ', ' : '' }}{{ $oferta->provincia->nombre }}
                            </span>
                            @endif
                        </div>
                    </div>
                    @if ($oferta->salario_min || $oferta->salario_max)
                    <div class="bg-green-50 border border-green-200 rounded-xl px-5 py-3 text-center flex-shrink-0 self-start">
                        <p class="text-xs text-green-600 font-semibold uppercase tracking-wide">Salario</p>
                        <p class="text-lg font-bold text-green-800">
                            @if ($oferta->salario_min && $oferta->salario_max)
                                ${{ number_format($oferta->salario_min / 1000, 0) }}k – ${{ number_format($oferta->salario_max / 1000, 0) }}k
                            @elseif ($oferta->salario_min)
                                Desde ${{ number_format($oferta->salario_min / 1000, 0) }}k
                            @else
                                Hasta ${{ number_format($oferta->salario_max / 1000, 0) }}k
                            @endif
                        </p>
                        <p class="text-xs text-green-500 mt-0.5">ARS / mes</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 sm:p-8">
                <h2 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    Descripción de la oferta
                </h2>
                <div class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $oferta->descripcion }}</div>
            </div>

            @if ($oferta->requisitos)
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 sm:p-8">
                <h2 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Requisitos
                </h2>
                <div class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $oferta->requisitos }}</div>
            </div>
            @endif

            @if ($oferta->especialidades->isNotEmpty())
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 sm:p-8">
                <h2 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Especialidades requeridas
                </h2>
                <div class="flex flex-wrap gap-2">
                    @foreach ($oferta->especialidades as $esp)
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-orange-50 text-orange-700 rounded-full text-sm font-semibold border border-orange-100 {{ $esp->pivot->es_principal ? 'ring-2 ring-orange-300' : '' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        {{ $esp->nombre }}
                        @if ($esp->pivot->es_principal)
                        <span class="text-[10px] uppercase tracking-wider bg-orange-200 text-orange-800 px-1.5 py-0.5 rounded-full ml-0.5">Principal</span>
                        @endif
                    </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 sm:p-8">
                @auth
                    @if (auth()->user()->tipo === 'trabajador')
                        @if ($yaPostulado)
                        <div class="text-center">
                            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <p class="text-green-700 font-bold text-base">Ya te postulaste</p>
                            <p class="text-green-600 text-sm mt-1">Tu solicitud fue enviada correctamente.</p>
                        </div>
                        @else
                        <a href="{{ route('trabajador.postulaciones.crear', $oferta) }}"
                            class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3.5 rounded-xl transition shadow-md text-base">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            Postularme
                        </a>
                        <p class="text-xs text-gray-400 mt-2 text-center">Te redirigiremos para confirmar tu postulación.</p>
                        @endif
                    @elseif (auth()->user()->tipo === 'empresa')
                        <div class="text-center">
                            <p class="text-gray-500 text-sm">Las empresas no pueden postularse a ofertas.</p>
                        </div>
                    @endif
                @else
                    <div class="text-center">
                        <a href="{{ route('login') }}"
                            class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3.5 rounded-xl transition shadow-md text-base">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Iniciá sesión para postularte
                        </a>
                        <p class="text-xs text-gray-400 mt-2">¿No tenés cuenta? <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-semibold">Registrate</a></p>
                    </div>
                @endauth
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                <h3 class="font-bold text-gray-900 text-base mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 00-1-1h-2a1 1 0 00-1 1v5m4 0H9"/>
                    </svg>
                    Sobre la empresa
                </h3>

                @if ($oferta->empresa)
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center flex-shrink-0 overflow-hidden">
                        @if (!empty($oferta->empresa->logo))
                        <img src="{{ asset($oferta->empresa->logo) }}" alt="Logo" class="w-full h-full object-cover">
                        @else
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 00-1-1h-2a1 1 0 00-1 1v5m4 0H9"/>
                        </svg>
                        @endif
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">{{ $oferta->empresa->nombre_empresa }}</p>
                        @if ($oferta->empresa->rubro)
                        <p class="text-xs text-gray-500">{{ $oferta->empresa->rubro->nombre ?? '' }}</p>
                        @endif
                    </div>
                </div>
                @if ($oferta->empresa->descripcion)
                <p class="text-sm text-gray-600 leading-relaxed line-clamp-4">{{ $oferta->empresa->descripcion }}</p>
                @endif
                @else
                <p class="text-sm text-gray-500">Empresa confidencial</p>
                @endif
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 space-y-3">
                <h3 class="font-bold text-gray-900 text-base flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Detalles
                </h3>
                <div class="text-sm space-y-2 text-gray-600">
                    <div class="flex justify-between">
                        <span>Visitas</span>
                        <span class="font-semibold text-gray-900">{{ $oferta->visitas }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Postulantes</span>
                        <span class="font-semibold text-gray-900">{{ $totalPostulantes }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Publicación</span>
                        <span class="font-semibold text-gray-900">{{ $oferta->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Estado</span>
                        <span class="font-semibold text-green-700">Activa</span>
                    </div>
                </div>
            </div>

            <a href="{{ route('ofertas.index') }}"
                class="inline-flex items-center justify-center gap-2 w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold px-6 py-3 rounded-xl transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
                Ver todas las ofertas
            </a>
        </div>
    </div>
</main>
@endsection
