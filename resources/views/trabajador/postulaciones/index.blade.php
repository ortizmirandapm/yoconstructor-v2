<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Mis Postulaciones</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-4">
                @forelse($postulaciones as $postulacion)
                    <div class="bg-white shadow-sm rounded-lg p-5">
                        <div class="flex justify-between items-start">
                            <div>
                                <a href="{{ route('ofertas.show', $postulacion->oferta) }}"
                                    class="font-semibold text-blue-700 hover:underline">
                                    {{ $postulacion->oferta->titulo }}
                                </a>
                                <p class="text-sm text-gray-600 mt-1">{{ $postulacion->oferta->empresa->nombre_empresa }}</p>
                                <div class="flex gap-3 mt-2 text-xs text-gray-500">
                                    <span>{{ $postulacion->oferta->modalidad }}</span>
                                    @if($postulacion->oferta->provincia)
                                        <span>{{ $postulacion->oferta->provincia->nombre }}</span>
                                    @endif
                                    <span>Postulado {{ $postulacion->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <span class="text-xs px-3 py-1 rounded-full font-medium
                                {{ $postulacion->estado === 'Pendiente' ? 'bg-gray-100 text-gray-600' : '' }}
                                {{ $postulacion->estado === 'Revisada' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $postulacion->estado === 'Entrevista' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $postulacion->estado === 'Aceptada' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $postulacion->estado === 'Rechazada' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ $postulacion->estado }}
                            </span>
                        </div>
                        @if($postulacion->mensaje)
                            <p class="text-sm text-gray-500 mt-3 border-t pt-3">{{ $postulacion->mensaje }}</p>
                        @endif
                    </div>
                @empty
                    <div class="bg-white rounded-lg p-8 text-center text-gray-400">
                        No te postulaste a ninguna oferta todavía.
                        <a href="{{ route('ofertas.index') }}" class="text-blue-600 hover:underline ml-1">Ver ofertas</a>
                    </div>
                @endforelse
            </div>

            @if($postulaciones->hasPages())
                <div class="mt-4">{{ $postulaciones->links() }}</div>
            @endif

        </div>
    </div>
</x-app-layout>