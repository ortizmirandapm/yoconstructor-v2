@extends('layouts.admin')

@section('title', 'Usuarios')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Gestion de Usuarios</h1>
        <p class="text-sm text-gray-500 mt-1">Administrar todos los usuarios registrados</p>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registrado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Accion</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($usuarios as $usuario)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $usuario->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $usuario->email }}</td>
                            <td class="px-6 py-4">
                                <span class="text-xs px-2 py-1 rounded-full
                                    {{ $usuario->tipo === \App\Enums\UserTipo::Admin ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $usuario->tipo === \App\Enums\UserTipo::Empresa ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $usuario->tipo === \App\Enums\UserTipo::Trabajador ? 'bg-green-100 text-green-700' : '' }}">
                                    {{ $usuario->tipo->value }}
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
                                @if($usuario->tipo !== \App\Enums\UserTipo::Admin)
                                    <form action="{{ route('admin.usuarios.estado', $usuario) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="text-xs px-3 py-1.5 rounded-lg font-medium transition
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
        </div>
        @if($usuarios->hasPages())
            <div class="px-6 py-4 border-t">{{ $usuarios->links() }}</div>
        @endif
    </div>
</div>
@endsection
