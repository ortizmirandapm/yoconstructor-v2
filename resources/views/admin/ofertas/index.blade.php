<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Gestión de Ofertas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Empresa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Publicada</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($ofertas as $oferta)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-800">{{ $oferta->titulo }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $oferta->empresa->nombre_empresa }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-xs px-2 py-1 rounded-full
                                        {{ $oferta->estado === 'Activa' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ $oferta->estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-400">{{ $oferta->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('admin.ofertas.estado', $oferta) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="text-xs px-3 py-1 rounded
                                                {{ $oferta->estado === 'Activa' ? 'bg-yellow-100 text-yellow-600 hover:bg-yellow-200' : 'bg-green-100 text-green-600 hover:bg-green-200' }}">
                                            {{ $oferta->estado === 'Activa' ? 'Pausar' : 'Activar' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($ofertas->hasPages())
                    <div class="px-6 py-4 border-t">{{ $ofertas->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>