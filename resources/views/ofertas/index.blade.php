@extends('layouts.public')

@section('title', 'Ofertas laborales - YoConstructor')

@section('content')
<nav class="w-full px-6 py-3 bg-gray-50 border-b border-gray-200">
    <ol class="flex items-center space-x-2 text-sm text-gray-500 max-w-7xl mx-auto">
        <li><a href="/" class="hover:text-blue-600 transition">Inicio</a></li>
        <li><span class="text-gray-300">/</span></li>
        <li class="text-gray-700 font-semibold">Ofertas laborales</li>
    </ol>
</nav>

<main class="max-w-7xl mx-auto px-4 py-10 flex-1 w-full">
    <div class="mb-10 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight tracking-tight">
            Encontrá tu próximo <span class="text-blue-600">trabajo</span>
        </h1>
        <p class="text-gray-500 mt-3 text-lg max-w-xl mx-auto">Explorá las ofertas laborales del sector construcción en Argentina.</p>
    </div>

    <form method="GET" action="{{ route('ofertas.index') }}" class="max-w-2xl mx-auto mb-8">
        <div class="flex gap-2">
            <div class="relative flex-1">
                <svg class="w-5 h-5 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                    placeholder="Buscar por título, descripción o empresa..."
                    class="w-full pl-11 pr-4 py-3 bg-white border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition">
                @foreach (['especialidad', 'provincia', 'modalidad', 'contrato'] as $f)
                    @if (request($f))
                    <input type="hidden" name="{{ $f }}" value="{{ request($f) }}">
                    @endif
                @endforeach
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl transition shadow-md">Buscar</button>
        </div>
    </form>

    <div class="flex flex-wrap justify-center gap-2 mb-10">
        @foreach ($especialidades->take(8) as $esp)
        <a href="{{ route('ofertas.index', array_merge(request()->all(), ['especialidad' => $esp->id])) }}"
            class="px-3 py-1.5 text-xs font-semibold rounded-full border transition {{ request('especialidad') == $esp->id ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300 hover:border-blue-400 hover:text-blue-600' }}">
            {{ $esp->nombre }}
        </a>
        @endforeach
        @if (request()->anyFilled(['especialidad', 'provincia', 'modalidad', 'contrato', 'buscar']))
        <a href="{{ route('ofertas.index') }}" class="px-3 py-1.5 text-xs font-semibold rounded-full bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 transition">✕ Limpiar</a>
        @endif
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        <aside class="lg:w-64 flex-shrink-0">
            <form method="GET" action="{{ route('ofertas.index') }}" class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 space-y-5 sticky top-24">
                <h2 class="text-sm font-bold text-gray-900">Filtros</h2>
                @if (request('buscar'))
                <input type="hidden" name="buscar" value="{{ request('buscar') }}">
                @endif
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">Especialidad</label>
                    <select name="especialidad" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todas</option>
                        @foreach ($especialidades as $esp)
                        <option value="{{ $esp->id }}" {{ request('especialidad') == $esp->id ? 'selected' : '' }}>{{ $esp->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">Provincia</label>
                    <select name="provincia" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todas</option>
                        @foreach ($provincias as $prov)
                        <option value="{{ $prov->id }}" {{ request('provincia') == $prov->id ? 'selected' : '' }}>{{ $prov->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">Modalidad</label>
                    <select name="modalidad" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todas</option>
                        @foreach (['Presencial', 'Remoto', 'Híbrido'] as $mod)
                        <option value="{{ $mod }}" {{ request('modalidad') == $mod ? 'selected' : '' }}>{{ $mod }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">Tipo de contrato</label>
                    <select name="contrato" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        @foreach ($tiposContrato as $tc)
                        <option value="{{ $tc }}" {{ request('contrato') == $tc ? 'selected' : '' }}>{{ $tc }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col gap-2 pt-1">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 rounded-xl transition shadow-sm">Aplicar filtros</button>
                    <a href="{{ route('ofertas.index') }}" class="w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium py-2.5 rounded-xl transition">Limpiar</a>
                </div>
            </form>
        </aside>

        <div class="flex-1">
            <p class="text-sm text-gray-500 mb-5">
                <span class="font-bold text-gray-900">{{ $ofertas->total() }}</span>
                oferta{{ $ofertas->total() !== 1 ? 's' : '' }} encontrada{{ $ofertas->total() !== 1 ? 's' : '' }}
            </p>

            @if ($ofertas->isEmpty())
            <div class="bg-white border border-dashed border-gray-200 rounded-2xl p-16 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-gray-500 font-semibold">No encontramos ofertas con esos criterios.</p>
                <a href="{{ route('ofertas.index') }}" class="inline-block mt-3 text-blue-600 hover:underline text-sm font-medium">Ver todas las ofertas</a>
            </div>
            @else
            <div class="space-y-4">
                @foreach ($ofertas as $o)
                @php
                    $esFueraZona = false;
                    if ($trabajadorId && $prefProvincia) {
                        if ($prefLocalidad && $o->localidad_id) {
                            $esFueraZona = $o->localidad_id !== $prefLocalidad;
                        } else {
                            $esFueraZona = $o->provincia_id !== $prefProvincia;
                        }
                    }
                @endphp
                <div class="group relative bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-0.5 bg-blue-600 scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>

                    <div class="p-5">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gray-100 border border-gray-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                                @if (!empty($o->empresa?->logo))
                                <img src="{{ asset($o->empresa->logo) }}" alt="Logo" class="w-full h-full object-cover">
                                @else
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 00-1-1h-2a1 1 0 00-1 1v5m4 0H9"/>
                                </svg>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <h3 class="font-bold text-gray-900 text-base group-hover:text-blue-600 transition-colors truncate">{{ $o->titulo }}</h3>
                                        <p class="text-sm text-blue-600 font-semibold mt-0.5">{{ $o->empresa?->nombre_empresa ?? 'Empresa confidencial' }}</p>
                                    </div>
                                    @if ($o->salario_min || $o->salario_max)
                                    <div class="text-right flex-shrink-0 hidden sm:block">
                                        <p class="text-sm font-bold text-green-700">
                                            @if ($o->salario_min && $o->salario_max)
                                                ${{ number_format($o->salario_min / 1000, 0) }}k – ${{ number_format($o->salario_max / 1000, 0) }}k
                                            @elseif ($o->salario_min)
                                                Desde ${{ number_format($o->salario_min / 1000, 0) }}k
                                            @else
                                                Hasta ${{ number_format($o->salario_max / 1000, 0) }}k
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-400">ARS / mes</p>
                                    </div>
                                    @endif
                                </div>

                                <div class="flex flex-wrap gap-3 mt-2.5 text-xs text-gray-500">
                                    @if ($o->provincia)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $o->localidad?->nombre ? $o->localidad->nombre . ', ' : '' }}{{ $o->provincia->nombre }}
                                    </span>
                                    @if ($esFueraZona)
                                    <span class="flex items-center gap-1 text-amber-600 font-semibold">
                                        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        Fuera de tu zona
                                    </span>
                                    @endif
                                    @endif
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $o->tipo_contrato }}
                                    </span>
                                    <span class="flex items-center gap-1 text-blue-600">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $o->total_postulantes }} postulante{{ $o->total_postulantes !== 1 ? 's' : '' }}
                                    </span>
                                </div>

                                @if ($o->especialidades->isNotEmpty())
                                <div class="mt-3 flex flex-wrap gap-1.5">
                                    @foreach ($o->especialidades as $esp)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-50 text-orange-700 border border-orange-100">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $esp->nombre }}
                                    </span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50 rounded-b-2xl flex items-center justify-between gap-3">
                        <span class="flex items-center gap-1.5 text-xs text-gray-400 flex-shrink-0">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ $o->created_at->diffForHumans() }}
                        </span>
                        <div class="flex items-center gap-2">
                            @if (isset($o->ya_postulado) && $o->ya_postulado > 0)
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-700 bg-green-50 border border-green-200 px-2.5 py-1.5 rounded-lg cursor-default">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                Ya postulado
                            </span>
                            @endif
                            <a href="{{ route('ofertas.show', $o) }}"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition shadow-sm flex-shrink-0">
                                Ver detalles
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $ofertas->links() }}
            </div>
            @endif
        </div>
    </div>
</main>
@endsection
