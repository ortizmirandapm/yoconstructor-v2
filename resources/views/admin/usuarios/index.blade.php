<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Gestión de Usuarios</h2>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registrado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($usuarios as $usuario)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-800">{{ $usuario->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $usuario->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-xs px-2 py-1 rounded-full
                                        {{ $usuario->tipo === 'admin' ? 'bg-red-100 text-red-700' : '' }}
                                        {{ $usuario->tipo === 'empresa' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $usuario->tipo === 'trabajador' ? 'bg-green-100 text-green-700' : '' }}">
                                        {{ $usuario->tipo }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs px-2 py-1 rounded-full
                                        {{ $usuario->estado ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $usuario->estado ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-400">{{ $usuario->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    @if($usuario->tipo !== 'admin')
                                        <form action="{{ route('admin.usuarios.estado', $usuario) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="text-xs px-3 py-1 rounded
                                                    {{ $usuario->estado ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-green-100 text-green-600 hover:bg-green-200' }}">
                                                {{ $usuario->estado ? 'Desactivar' : 'Activar' }}
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($usuarios->hasPages())
                    <div class="px-6 py-4 border-t">{{ $usuarios->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>