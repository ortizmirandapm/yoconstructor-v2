<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Mis Ofertas</h2>
            <a href="{{ route('empresa.ofertas.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700">
                + Nueva Oferta
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Modalidad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Publicada</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($ofertas as $oferta)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $oferta->titulo }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        {{ $oferta->estado === 'Activa' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $oferta->estado === 'Pausada' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $oferta->estado === 'Cerrada' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $oferta->estado === 'Borrador' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ $oferta->estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $oferta->modalidad }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $oferta->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-sm flex gap-3">
                                    <a href="{{ route('empresa.ofertas.edit', $oferta) }}"
                                       class="text-blue-600 hover:underline">Editar</a>
                                    <form action="{{ route('empresa.ofertas.destroy', $oferta) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar esta oferta?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                                    No tenés ofertas publicadas todavía.
                                    <a href="{{ route('empresa.ofertas.create') }}" class="text-blue-600 hover:underline ml-1">Crear una</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($ofertas->hasPages())
                    <div class="px-6 py-4 border-t">
                        {{ $ofertas->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>