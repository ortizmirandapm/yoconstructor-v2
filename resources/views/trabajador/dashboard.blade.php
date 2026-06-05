<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Dashboard</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @php
                $trabajador = auth()->user()->trabajador;
                $postulaciones = $trabajador->postulaciones()->with('oferta.empresa')->latest()->get();
                $pendientes = $postulaciones->where('estado', 'Pendiente')->count();
                $entrevistas = $postulaciones->where('estado', 'Entrevista')->count();
                $aceptadas = $postulaciones->where('estado', 'Aceptada')->count();
                $notificaciones = auth()->user()->unreadNotifications->count();
            @endphp

            {{-- Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500">Postulaciones</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $postulaciones->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-yellow-500">
                    <p class="text-sm text-gray-500">Pendientes</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $pendientes }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-purple-500">
                    <p class="text-sm text-gray-500">Entrevistas</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $entrevistas }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-green-500">
                    <p class="text-sm text-gray-500">Aceptadas</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $aceptadas }}</p>
                </div>
            </div>

            {{-- Links rápidos --}}
            <div class="flex gap-3">
                <a href="{{ route('ofertas.index') }}"
                   class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                    Ver ofertas disponibles
                </a>
                <a href="{{ route('trabajador.perfil.edit') }}"
                   class="px-4 py-2 border text-sm rounded-md text-gray-600 hover:bg-gray-50">
                    Mi perfil
                </a>
                <a href="{{ route('trabajador.notificaciones.index') }}"
                   class="px-4 py-2 border text-sm rounded-md text-gray-600 hover:bg-gray-50 relative">
                    Notificaciones
                    @if($notificaciones > 0)
                        <span class="ml-1 bg-red-500 text-white text-xs rounded-full px-2">{{ $notificaciones }}</span>
                    @endif
                </a>
            </div>

            {{-- Últimas postulaciones --}}
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-5 py-3 border-b flex justify-between items-center">
                    <h3 class="font-medium text-gray-700">Mis Postulaciones</h3>
                    <a href="{{ route('trabajador.postulaciones.index') }}"
                       class="text-sm text-blue-600 hover:underline">Ver todas</a>
                </div>

                @forelse($postulaciones->take(5) as $postulacion)
                    <div class="px-5 py-4 border-b last:border-0">
                        <div class="flex justify-between items-start">
                            <div>
                                <a href="{{ route('ofertas.show', $postulacion->oferta) }}"
                                   class="text-sm font-medium text-blue-700 hover:underline">
                                    {{ $postulacion->oferta->titulo }}
                                </a>
                                <p class="text-xs text-gray-500 mt-1">{{ $postulacion->oferta->empresa->nombre_empresa }}</p>
                                <p class="text-xs text-gray-400">{{ $postulacion->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full
                                {{ $postulacion->estado === 'Pendiente' ? 'bg-gray-100 text-gray-600' : '' }}
                                {{ $postulacion->estado === 'Revisada' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $postulacion->estado === 'Entrevista' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $postulacion->estado === 'Aceptada' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $postulacion->estado === 'Rechazada' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ $postulacion->estado }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-gray-400">
                        No te postulaste a ninguna oferta todavía.
                        <a href="{{ route('ofertas.index') }}" class="text-blue-600 hover:underline ml-1">Ver ofertas</a>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>