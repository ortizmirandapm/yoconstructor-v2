<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Panel de Administración</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500">Total Usuarios</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_usuarios'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-green-500">
                    <p class="text-sm text-gray-500">Empresas</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_empresas'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-purple-500">
                    <p class="text-sm text-gray-500">Trabajadores</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_trabajadores'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-yellow-500">
                    <p class="text-sm text-gray-500">Ofertas Activas</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['ofertas_activas'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-gray-400">
                    <p class="text-sm text-gray-500">Total Ofertas</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['ofertas_total'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-red-400">
                    <p class="text-sm text-gray-500">Usuarios Hoy</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['usuarios_nuevos'] }}</p>
                </div>
            </div>

            {{-- Links rápidos --}}
            <div class="flex gap-3">
                <a href="{{ route('admin.usuarios.index') }}"
                   class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                    Ver Usuarios
                </a>
                <a href="{{ route('admin.empresas.index') }}"
                   class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">
                    Ver Empresas
                </a>
                <a href="{{ route('admin.ofertas.index') }}"
                   class="px-4 py-2 bg-purple-600 text-white text-sm rounded-md hover:bg-purple-700">
                    Ver Ofertas
                </a>
            </div>

            {{-- Tablas recientes --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-5 py-3 border-b font-medium text-gray-700">Últimas Ofertas</div>
                    <table class="min-w-full divide-y divide-gray-100">
                        <tbody>
                            @foreach($ofertas_recientes as $oferta)
                                <tr class="px-5 py-3">
                                    <td class="px-5 py-3 text-sm text-gray-800">{{ $oferta->titulo }}</td>
                                    <td class="px-5 py-3 text-xs text-gray-500">{{ $oferta->empresa->nombre_empresa }}</td>
                                    <td class="px-5 py-3">
                                        <span class="text-xs px-2 py-1 rounded-full
                                            {{ $oferta->estado === 'Activa' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $oferta->estado }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-5 py-3 border-b font-medium text-gray-700">Últimos Usuarios</div>
                    <table class="min-w-full divide-y divide-gray-100">
                        <tbody>
                            @foreach($usuarios_recientes as $usuario)
                                <tr>
                                    <td class="px-5 py-3 text-sm text-gray-800">{{ $usuario->name }}</td>
                                    <td class="px-5 py-3 text-xs text-gray-500">{{ $usuario->tipo }}</td>
                                    <td class="px-5 py-3 text-xs text-gray-400">{{ $usuario->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>