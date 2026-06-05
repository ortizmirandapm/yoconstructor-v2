<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Dashboard</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @php
            $empresa = auth()->user()->empresa;
            $ofertas = $empresa->ofertas()->withCount('postulaciones')->latest()->get();
            $totalPostulaciones = $ofertas->sum('postulaciones_count');
            $ofertasActivas = $ofertas->where('estado', 'Activa')->count();
            @endphp

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500">Ofertas publicadas</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $ofertas->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-green-500">
                    <p class="text-sm text-gray-500">Ofertas activas</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $ofertasActivas }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-purple-500">
                    <p class="text-sm text-gray-500">Total postulaciones</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalPostulaciones }}</p>
                </div>
            </div>

            {{-- Links rápidos --}}
            <div class="flex gap-3">
                <a href="{{ route('empresa.ofertas.create') }}"
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                    + Nueva Oferta
                </a>
                <a href="{{ route('empresa.ofertas.index') }}"
                    class="px-4 py-2 border text-sm rounded-md text-gray-600 hover:bg-gray-50">
                    Ver mis ofertas
                </a>
                <a href="{{ route('empresa.postulaciones.index') }}"
                    class="px-4 py-2 border text-sm rounded-md text-gray-600 hover:bg-gray-50">
                    Ver postulaciones
                </a>
                <a href="{{ route('empresa.perfil.edit') }}"
                    class="px-4 py-2 border text-sm rounded-md text-gray-600 hover:bg-gray-50">
                    Mi perfil
                </a>
            </div>

            {{-- Tabla de ofertas con postulaciones --}}
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-5 py-3 border-b flex justify-between items-center">
                    <h3 class="font-medium text-gray-700">Mis Ofertas</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Postulaciones</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Publicada</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($ofertas as $oferta)
                        <tr>
                            <td class="px-5 py-4 text-sm text-gray-800">{{ $oferta->titulo }}</td>
                            <td class="px-5 py-4">
                                <span class="text-xs px-2 py-1 rounded-full
                                        {{ $oferta->estado === 'Activa' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $oferta->estado === 'Pausada' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $oferta->estado === 'Cerrada' ? 'bg-red-100 text-red-700' : '' }}
                                        {{ $oferta->estado === 'Borrador' ? 'bg-gray-100 text-gray-600' : '' }}">
                                    {{ $oferta->estado }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600">
                                {{ $oferta->postulaciones_count }}
                                @if($oferta->postulaciones_count > 0)
                                <a href="{{ route('empresa.postulaciones.index') }}"
                                    class="text-blue-600 text-xs hover:underline ml-1">ver</a>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-400">{{ $oferta->created_at->format('d/m/Y') }}</td>
                            <td class="px-5 py-4 flex gap-2">
                                <a href="{{ route('empresa.ofertas.edit', $oferta) }}"
                                    class="text-xs text-blue-600 hover:underline">Editar</a>
                                <form action="{{ route('empresa.ofertas.destroy', $oferta) }}" method="POST"
                                    onsubmit="return confirm('¿Eliminar?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:underline">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-gray-400">
                                No tenés ofertas publicadas.
                                <a href="{{ route('empresa.ofertas.create') }}" class="text-blue-600 hover:underline ml-1">Crear una</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>