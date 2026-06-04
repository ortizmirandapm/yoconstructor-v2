<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">{{ $oferta->titulo }}</h2>
            <a href="{{ route('ofertas.index') }}" class="text-sm text-gray-600 hover:underline">← Volver</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Cabecera --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $oferta->titulo }}</h1>
                        <p class="text-gray-600 mt-1">{{ $oferta->empresa->nombre_empresa }}</p>
                        <div class="flex gap-3 mt-3 flex-wrap">
                            <span class="text-sm bg-blue-50 text-blue-700 px-3 py-1 rounded-full">{{ $oferta->modalidad }}</span>
                            <span class="text-sm bg-gray-100 text-gray-600 px-3 py-1 rounded-full">{{ $oferta->tipo_contrato }}</span>
                            @if($oferta->provincia)
                                <span class="text-sm bg-gray-100 text-gray-600 px-3 py-1 rounded-full">{{ $oferta->provincia->nombre }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        @if($oferta->salario_min || $oferta->salario_max)
                            <p class="text-lg font-bold text-green-700">
                                ${{ number_format($oferta->salario_min, 0, ',', '.') }}
                                @if($oferta->salario_max)
                                    - ${{ number_format($oferta->salario_max, 0, ',', '.') }}
                                @endif
                            </p>
                        @endif
                        <p class="text-xs text-gray-400 mt-1">{{ $oferta->visitas }} visitas</p>
                        <p class="text-xs text-gray-400">Publicada {{ $oferta->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            {{-- Descripción --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-3">Descripción</h3>
                <p class="text-gray-600 whitespace-pre-line">{{ $oferta->descripcion }}</p>
            </div>

            @if($oferta->requisitos)
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-3">Requisitos</h3>
                <p class="text-gray-600 whitespace-pre-line">{{ $oferta->requisitos }}</p>
            </div>
            @endif

            {{-- Especialidades --}}
            @if($oferta->especialidades->count())
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-3">Especialidades requeridas</h3>
                <div class="flex gap-2 flex-wrap">
                    @foreach($oferta->especialidades as $esp)
                        <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm
                            {{ $esp->pivot->es_principal ? 'font-semibold border border-blue-300' : '' }}">
                            {{ $esp->nombre }}
                            @if($esp->pivot->es_principal)
                                <span class="text-xs ml-1">(principal)</span>
                            @endif
                        </span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Botón postularse --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                @auth
                    @if(auth()->user()->tipo === 'trabajador')
                        @if($yaPostulado)
                            <p class="text-green-600 font-medium">✓ Ya te postulaste a esta oferta</p>
                        @else
                            <a href="{{ route('trabajador.postulaciones.crear', $oferta) }}"
                                class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                                Postularme
                            </a>
                        @endif
                    @elseif(auth()->user()->tipo === 'empresa')
                        <p class="text-gray-500 text-sm">Las empresas no pueden postularse a ofertas.</p>
                    @endif
                @else
                    <p class="text-gray-600 text-sm">
                        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Iniciá sesión</a>
                        como trabajador para postularte.
                    </p>
                @endauth
            </div>

        </div>
    </div>
</x-app-layout>