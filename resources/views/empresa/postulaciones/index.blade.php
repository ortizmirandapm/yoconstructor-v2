<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Postulaciones Recibidas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @forelse($ofertas as $oferta)
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-5 py-3 bg-gray-50 border-b flex justify-between items-center">
                        <h3 class="font-semibold text-gray-800">{{ $oferta->titulo }}</h3>
                        <span class="text-sm text-gray-500">{{ $oferta->postulaciones->count() }} postulaciones</span>
                    </div>

                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trabajador</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mensaje</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($oferta->postulaciones as $postulacion)
                                <tr>
                                    <td class="px-5 py-4">
                                        <p class="text-sm font-medium text-gray-800">
                                            {{ $postulacion->trabajador->nombre }} {{ $postulacion->trabajador->apellido }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $postulacion->trabajador->user->email }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-500 max-w-xs truncate">
                                        {{ $postulacion->mensaje ?? '—' }}
                                    </td>
                                    <td class="px-5 py-4 text-xs text-gray-400">
                                        {{ $postulacion->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="text-xs px-2 py-1 rounded-full
                                            {{ $postulacion->estado === 'Pendiente' ? 'bg-gray-100 text-gray-600' : '' }}
                                            {{ $postulacion->estado === 'Revisada' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                            {{ $postulacion->estado === 'Entrevista' ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ $postulacion->estado === 'Aceptada' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $postulacion->estado === 'Rechazada' ? 'bg-red-100 text-red-700' : '' }}">
                                            {{ $postulacion->estado }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <form action="{{ route('empresa.postulaciones.estado', $postulacion) }}" method="POST" class="flex gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="estado" class="border-gray-300 rounded text-xs">
                                                @foreach(['Pendiente','Revisada','Entrevista','Aceptada','Rechazada'] as $est)
                                                    <option value="{{ $est }}" {{ $postulacion->estado === $est ? 'selected' : '' }}>
                                                        {{ $est }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit"
                                                class="px-2 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                                Guardar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @empty
                <div class="bg-white rounded-lg p-8 text-center text-gray-400">
                    No recibiste postulaciones todavía.
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>