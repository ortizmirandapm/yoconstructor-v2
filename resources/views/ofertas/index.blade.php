<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Ofertas de Trabajo</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filtros --}}
            <div class="bg-white shadow-sm rounded-lg p-4 mb-6">
                <form method="GET" action="{{ route('ofertas.index') }}" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <input type="text" name="buscar" placeholder="Buscar por título..."
                        value="{{ request('buscar') }}"
                        class="border-gray-300 rounded-md text-sm w-full" />

                    <select name="especialidad" class="border-gray-300 rounded-md text-sm w-full">
                        <option value="">Todas las especialidades</option>
                        @foreach($especialidades as $esp)
                            <option value="{{ $esp->id }}" {{ request('especialidad') == $esp->id ? 'selected' : '' }}>
                                {{ $esp->nombre }}
                            </option>
                        @endforeach
                    </select>

                    <select name="provincia" class="border-gray-300 rounded-md text-sm w-full">
                        <option value="">Todas las provincias</option>
                        @foreach($provincias as $provincia)
                            <option value="{{ $provincia->id }}" {{ request('provincia') == $provincia->id ? 'selected' : '' }}>
                                {{ $provincia->nombre }}
                            </option>
                        @endforeach
                    </select>

                    <select name="modalidad" class="border-gray-300 rounded-md text-sm w-full">
                        <option value="">Todas las modalidades</option>
                        @foreach(['Presencial','Remoto','Híbrido'] as $mod)
                            <option value="{{ $mod }}" {{ request('modalidad') == $mod ? 'selected' : '' }}>
                                {{ $mod }}
                            </option>
                        @endforeach
                    </select>

                    <div class="col-span-2 md:col-span-4 flex gap-2">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                            Filtrar
                        </button>
                        <a href="{{ route('ofertas.index') }}"
                            class="px-4 py-2 border text-sm rounded-md text-gray-600 hover:bg-gray-50">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            {{-- Resultados --}}
            <p class="text-sm text-gray-500 mb-4">{{ $ofertas->total() }} ofertas encontradas</p>

            <div class="space-y-4">
                @forelse($ofertas as $oferta)
                    <div class="bg-white shadow-sm rounded-lg p-5 hover:shadow-md transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <a href="{{ route('ofertas.show', $oferta) }}"
                                    class="text-lg font-semibold text-blue-700 hover:underline">
                                    {{ $oferta->titulo }}
                                </a>
                                <p class="text-sm text-gray-600 mt-1">{{ $oferta->empresa->nombre_empresa }}</p>
                                <div class="flex gap-3 mt-2 flex-wrap">
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ $oferta->modalidad }}</span>
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ $oferta->tipo_contrato }}</span>
                                    @if($oferta->provincia)
                                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ $oferta->provincia->nombre }}</span>
                                    @endif
                                    @foreach($oferta->especialidades->take(3) as $esp)
                                        <span class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded">{{ $esp->nombre }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="text-right">
                                @if($oferta->salario_min || $oferta->salario_max)
                                    <p class="text-sm font-medium text-green-700">
                                        ${{ number_format($oferta->salario_min, 0, ',', '.') }}
                                        @if($oferta->salario_max)
                                            - ${{ number_format($oferta->salario_max, 0, ',', '.') }}
                                        @endif
                                    </p>
                                @endif
                                <p class="text-xs text-gray-400 mt-1">{{ $oferta->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg p-8 text-center text-gray-400">
                        No hay ofertas activas con esos filtros.
                    </div>
                @endforelse
            </div>

            @if($ofertas->hasPages())
                <div class="mt-6">{{ $ofertas->links() }}</div>
            @endif

        </div>
    </div>
</x-app-layout>