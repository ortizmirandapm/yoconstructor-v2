@extends('layouts.admin')

@section('title', 'Trabajadores')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gesti&oacute;n de Trabajadores</h1>
            <p class="text-sm text-gray-500 mt-1">Administrar trabajadores registrados en la plataforma</p>
        </div>
        <button onclick="abrirModalCrear()"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nuevo Trabajador
        </button>
    </div>

    {{-- TOAST --}}
    @if(session('success'))
        <div id="toast-success" role="alert" aria-live="assertive" aria-atomic="true" class="fixed top-6 right-6 z-[9999] flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-xl shadow-xl max-w-sm transition-all duration-500">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p class="text-sm font-medium">{{ session('success') }}</p>
            <button onclick="this.parentElement.remove()" class="ml-auto text-green-400 hover:text-green-600">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </button>
        </div>
        <script>setTimeout(function(){var t=document.getElementById("toast-success");if(t)t.remove();},4000);</script>
    @endif

    {{-- FILTERS --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-4 sm:p-6 mb-6">
        <form method="GET" action="{{ route('admin.trabajadores.index') }}" class="flex flex-col lg:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Buscar</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/></svg>
                    <input type="text" name="buscar" value="{{ request('buscar') }}"
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition"
                        placeholder="Buscar trabajadores...">
                </div>
            </div>
            <div class="w-full sm:w-48">
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Especialidad</label>
                <select name="especialidad"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition">
                    <option value="">Todas</option>
                    @foreach($especialidades as $esp)
                        <option value="{{ $esp->id }}" {{ request('especialidad') == $esp->id ? 'selected' : '' }}>{{ $esp->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-3 w-full lg:w-auto">
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer whitespace-nowrap">
                    <input type="checkbox" name="con_cv" value="1" {{ request('con_cv') ? 'checked' : '' }}
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    Solo con CV
                </label>
                <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                    Filtrar
                </button>
                @if(request()->anyFilled(['buscar', 'estado', 'especialidad', 'con_cv']))
                    <a href="{{ route('admin.trabajadores.index') }}"
                        class="px-4 py-2.5 border border-gray-300 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition text-center">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ESTADO PILLS --}}
    <div class="flex items-center gap-2 flex-wrap mb-4">
        <a href="{{ route('admin.trabajadores.index', array_merge(request()->except('estado'), ['estado' => ''])) }}"
            class="px-4 py-1.5 text-xs font-semibold rounded-full transition {{ !request('estado') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            Todos
        </a>
        <a href="{{ route('admin.trabajadores.index', array_merge(request()->except('estado'), ['estado' => 'activos'])) }}"
            class="px-4 py-1.5 text-xs font-semibold rounded-full transition {{ request('estado') === 'activos' ? 'bg-green-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            Activos ({{ $totalActivos }})
        </a>
        <a href="{{ route('admin.trabajadores.index', array_merge(request()->except('estado'), ['estado' => 'inactivos'])) }}"
            class="px-4 py-1.5 text-xs font-semibold rounded-full transition {{ request('estado') === 'inactivos' ? 'bg-red-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            Inactivos ({{ $totalInactivos }})
        </a>
    </div>

    {{-- TABLE --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Trabajador</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Especialidad</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Postulaciones</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell">CV</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($trabajadores as $t)
                        @php
                            $nc = trim(($t->nombre ?? '') . ' ' . ($t->apellido ?? ''));
                            $user = $t->user;
                            $esActivo = $user?->estado ?? false;
                            $espPrincipal = $t->especialidades->firstWhere('pivot.es_principal', true)
                                ?? $t->especialidades->first();
                        @endphp
                        <tr class="hover:bg-gray-50 transition {{ !$esActivo ? 'opacity-60 bg-gray-50/50' : '' }}">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-green-100 rounded-xl flex items-center justify-center text-green-700 text-sm font-bold flex-shrink-0 border border-green-200">
                                        {{ strtoupper(substr($nc ?: 'T', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-800 truncate">{{ $nc ?: '(sin nombre)' }}</p>
                                        <p class="text-xs text-gray-400 truncate">{{ $user?->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 hidden md:table-cell text-xs text-gray-600">
                                {{ $espPrincipal?->nombre ?? '&mdash;' }}
                            </td>
                            <td class="px-4 py-4 text-center hidden lg:table-cell font-semibold text-gray-700">
                                {{ $t->postulaciones_count }}
                            </td>
                            <td class="px-4 py-4 text-center hidden lg:table-cell">
                                @if($t->curriculum_pdf)
                                    <span class="text-[10px] bg-red-50 text-red-600 border border-red-200 px-2 py-0.5 rounded-full font-bold">PDF</span>
                                @else
                                    <span class="text-xs text-gray-300">&mdash;</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($esActivo)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-50 text-green-700 border border-green-200 rounded-full text-xs font-medium">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 text-gray-500 border border-gray-300 rounded-full text-xs font-medium">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="relative inline-block text-left">
                                    <button type="button"
                                        onclick="toggleDropdown(event, 'dropdown-{{ $t->id }}')"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                        class="flex items-center justify-center w-8 h-8 text-gray-500 rounded-full hover:bg-gray-100 focus:outline-none transition mx-auto"
                                        aria-label="Acciones">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                        </svg>
                                    </button>
                                    <div id="dropdown-{{ $t->id }}"
                                        class="hidden absolute right-0 z-[100] mt-2 w-44 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none"
                                        role="menu">
                                        <div class="py-1">
                                            <button type="button"
                                                onclick="abrirModalToggle({{ $t->id }}, '{{ addslashes($nc ?: 'este trabajador') }}', {{ $esActivo ? 'true' : 'false' }})"
                                                class="flex w-full px-4 py-2 text-sm text-left transition {{ $esActivo ? 'text-orange-700 hover:bg-orange-50' : 'text-green-700 hover:bg-green-50' }}"
                                                role="menuitem">
                                                {{ $esActivo ? 'Desactivar' : 'Activar' }}
                                            </button>
                                            <button type="button"
                                                onclick="abrirModalEditar({{ $t->id }}, '{{ addslashes($t->nombre ?? '') }}', '{{ addslashes($t->apellido ?? '') }}', '{{ addslashes($t->nombre_titulo ?? '') }}', '{{ addslashes($t->telefono ?? '') }}', '{{ addslashes($t->dni ?? '') }}', {{ $t->provincia_preferencia_id ?: 'null' }})"
                                                class="flex w-full px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition text-left"
                                                role="menuitem">
                                                Editar
                                            </button>
                                        </div>
                                        <div class="py-1">
                                            <button type="button"
                                                onclick="abrirModalEliminar({{ $t->id }}, '{{ addslashes($nc ?: 'este trabajador') }}')"
                                                class="flex w-full px-4 py-2 text-sm text-red-700 hover:bg-red-50 transition font-medium text-left"
                                                role="menuitem">
                                                Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <p class="text-gray-400 text-sm">No hay trabajadores</p>
                                @if(request('estado') === 'inactivos')
                                    <a href="{{ route('admin.trabajadores.index') }}" class="text-xs text-indigo-600 hover:underline mt-1 inline-block">Ver activos</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($trabajadores->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $trabajadores->links() }}
            </div>
        @endif
    </div>
</div>

{{-- MODAL CREAR --}}
<div id="modalCrear" role="dialog" aria-modal="true" aria-labelledby="modal-crear-titulo" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-hidden flex flex-col">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4 flex items-center justify-between flex-shrink-0">
            <h3 id="modal-crear-titulo" class="font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                Nuevo trabajador
            </h3>
            <button onclick="cerrarModalCrear()" class="text-white hover:text-indigo-200 transition" aria-label="Cerrar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.trabajadores.store') }}" class="overflow-y-auto flex-1">
            @csrf
            <div class="p-6 space-y-4">

                {{-- Cuenta de acceso --}}
                <div>
                    <p class="text-xs font-bold text-indigo-600 uppercase tracking-wider mb-3">Cuenta de acceso</p>
                    <div class="space-y-3">
                        <div>
                            <label for="crear-email" class="block text-xs font-semibold text-gray-600 mb-1.5">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="crear-email" required placeholder="trabajador@email.com"
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                                aria-required="true">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="relative">
                                <label for="crear-password" class="block text-xs font-semibold text-gray-600 mb-1.5">Contrase&ntilde;a <span class="text-red-500">*</span></label>
                                <input type="password" name="password" id="crear-password" required placeholder="M&iacute;n. 8 caracteres"
                                    class="w-full px-3 py-2.5 pr-10 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                                    aria-required="true">
                                <button type="button" onclick="togglePass('crear-password')" class="absolute right-3 top-[34px] text-gray-400 hover:text-gray-600" aria-label="Mostrar contrase&ntilde;a">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                            <div class="relative">
                                <label for="crear-password-confirm" class="block text-xs font-semibold text-gray-600 mb-1.5">Confirmar <span class="text-red-500">*</span></label>
                                <input type="password" name="password_confirmation" id="crear-password-confirm" required placeholder="Repetir"
                                    class="w-full px-3 py-2.5 pr-10 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                                    aria-required="true">
                                <button type="button" onclick="togglePass('crear-password-confirm')" class="absolute right-3 top-[34px] text-gray-400 hover:text-gray-600" aria-label="Mostrar contrase&ntilde;a">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200">

                {{-- Datos personales --}}
                <div>
                    <p class="text-xs font-bold text-indigo-600 uppercase tracking-wider mb-3">Datos personales</p>
                    <div class="space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="crear-nombre" class="block text-xs font-semibold text-gray-600 mb-1.5">Nombre <span class="text-red-500">*</span></label>
                                <input type="text" name="nombre" id="crear-nombre" required
                                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                                    aria-required="true">
                            </div>
                            <div>
                                <label for="crear-apellido" class="block text-xs font-semibold text-gray-600 mb-1.5">Apellido <span class="text-red-500">*</span></label>
                                <input type="text" name="apellido" id="crear-apellido" required
                                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                                    aria-required="true">
                            </div>
                        </div>
                        <div>
                            <label for="crear-titulo" class="block text-xs font-semibold text-gray-600 mb-1.5">T&iacute;tulo / Profesi&oacute;n</label>
                            <input type="text" name="nombre_titulo" id="crear-titulo" placeholder="Ej: Alba&ntilde;il, Electricista"
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="crear-dni" class="block text-xs font-semibold text-gray-600 mb-1.5">DNI</label>
                                <input type="text" name="dni" id="crear-dni" placeholder="12345678"
                                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            </div>
                            <div>
                                <label for="crear-telefono" class="block text-xs font-semibold text-gray-600 mb-1.5">Tel&eacute;fono</label>
                                <input type="text" name="telefono" id="crear-telefono" placeholder="3834000000"
                                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            </div>
                        </div>
                        <div>
                            <label for="crear-provincia" class="block text-xs font-semibold text-gray-600 mb-1.5">Provincia de preferencia</label>
                            <select name="provincia_preferencia_id" id="crear-provincia"
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">Sin especificar</option>
                                @foreach($provincias as $prov)
                                    <option value="{{ $prov->id }}">{{ $prov->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end gap-3 flex-shrink-0">
                <button type="button" onclick="cerrarModalCrear()"
                    class="px-4 py-2.5 border border-gray-300 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-100 transition">Cancelar</button>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Crear trabajador
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDITAR --}}
<div id="modalEditar" role="dialog" aria-modal="true" aria-labelledby="modal-editar-titulo" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-hidden flex flex-col">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4 flex items-center justify-between flex-shrink-0">
            <h3 id="modal-editar-titulo" class="font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Editar trabajador
            </h3>
            <button onclick="cerrarModalEditar()" class="text-white hover:text-indigo-200 transition" aria-label="Cerrar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="" id="form-editar" class="overflow-y-auto flex-1">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4">

                {{-- Datos personales --}}
                <div>
                    <p class="text-xs font-bold text-indigo-600 uppercase tracking-wider mb-3">Datos personales</p>
                    <div class="space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="editar-nombre" class="block text-xs font-semibold text-gray-600 mb-1.5">Nombre <span class="text-red-500">*</span></label>
                                <input type="text" name="nombre" id="editar-nombre" required
                                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                                    aria-required="true">
                            </div>
                            <div>
                                <label for="editar-apellido" class="block text-xs font-semibold text-gray-600 mb-1.5">Apellido <span class="text-red-500">*</span></label>
                                <input type="text" name="apellido" id="editar-apellido" required
                                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                                    aria-required="true">
                            </div>
                        </div>
                        <div>
                            <label for="editar-titulo" class="block text-xs font-semibold text-gray-600 mb-1.5">T&iacute;tulo / Profesi&oacute;n</label>
                            <input type="text" name="nombre_titulo" id="editar-titulo" placeholder="Ej: Alba&ntilde;il, Electricista"
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="editar-dni" class="block text-xs font-semibold text-gray-600 mb-1.5">DNI</label>
                                <input type="text" name="dni" id="editar-dni" placeholder="12345678"
                                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            </div>
                            <div>
                                <label for="editar-telefono" class="block text-xs font-semibold text-gray-600 mb-1.5">Tel&eacute;fono</label>
                                <input type="text" name="telefono" id="editar-telefono" placeholder="3834000000"
                                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            </div>
                        </div>
                        <div>
                            <label for="editar-provincia" class="block text-xs font-semibold text-gray-600 mb-1.5">Provincia de preferencia</label>
                            <select name="provincia_preferencia_id" id="editar-provincia"
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">Sin especificar</option>
                                @foreach($provincias as $prov)
                                    <option value="{{ $prov->id }}">{{ $prov->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200">

                {{-- Credenciales de acceso --}}
                <div>
                    <p class="text-xs font-bold text-indigo-600 uppercase tracking-wider mb-3">Credenciales de acceso</p>
                    <div class="space-y-3">
                        <div>
                            <label for="editar-email" class="block text-xs font-semibold text-gray-600 mb-1.5">Email de acceso <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="editar-email" required
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                                aria-required="true">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="relative">
                                <label for="editar-password" class="block text-xs font-semibold text-gray-600 mb-1.5">Nueva contrase&ntilde;a</label>
                                <input type="password" name="password" id="editar-password" placeholder="Dejar vac&iacute;o para no cambiar"
                                    class="w-full px-3 py-2.5 pr-10 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                                <button type="button" onclick="togglePass('editar-password')" class="absolute right-3 top-[34px] text-gray-400 hover:text-gray-600" aria-label="Mostrar contrase&ntilde;a">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                            <div class="relative">
                                <label for="editar-password-confirm" class="block text-xs font-semibold text-gray-600 mb-1.5">Confirmar</label>
                                <input type="password" name="password_confirmation" id="editar-password-confirm" placeholder="Repetir"
                                    class="w-full px-3 py-2.5 pr-10 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                                <button type="button" onclick="togglePass('editar-password-confirm')" class="absolute right-3 top-[34px] text-gray-400 hover:text-gray-600" aria-label="Mostrar contrase&ntilde;a">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400">Dej&aacute; los campos de contrase&ntilde;a vac&iacute;os si no quer&eacute;s modificarla.</p>
                    </div>
                </div>

            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end gap-3 flex-shrink-0">
                <button type="button" onclick="cerrarModalEditar()"
                    class="px-4 py-2.5 border border-gray-300 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-100 transition">Cancelar</button>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL TOGGLE --}}
<div id="modalToggle" role="dialog" aria-modal="true" aria-labelledby="toggle-titulo" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm">
        <div class="px-6 py-5 border-b border-gray-200 flex items-center gap-3">
            <div id="toggle-icon" class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"></div>
            <div>
                <h3 id="toggle-titulo" class="font-bold text-gray-900"></h3>
                <p id="toggle-nombre" class="text-xs text-gray-400 mt-0.5"></p>
            </div>
        </div>
        <div class="px-6 py-5">
            <p id="toggle-mensaje" class="text-sm text-gray-600"></p>
        </div>
        <form method="POST" action="" id="form-toggle">
            @csrf
            @method('PATCH')
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl flex justify-end gap-3">
                <button type="button" onclick="cerrarModalToggle()" class="px-4 py-2.5 border border-gray-300 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-100 transition">Cancelar</button>
                <button type="submit" id="toggle-btn" class="px-5 py-2.5 text-white text-sm font-semibold rounded-xl transition"></button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL ELIMINAR --}}
<div id="modalEliminar" role="dialog" aria-modal="true" aria-labelledby="eliminar-titulo" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <div class="px-6 py-5 border-b border-gray-200 flex items-center gap-3">
            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <div>
                <h3 id="eliminar-titulo" class="font-bold text-gray-900">Eliminar trabajador</h3>
                <p id="eliminar-nombre" class="text-xs text-gray-400"></p>
            </div>
        </div>
        <form method="POST" action="" id="form-eliminar">
            @csrf
            @method('DELETE')
            <div class="px-6 py-5">
                <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
                    <p class="text-xs text-red-700 font-semibold mb-1">Esta acci&oacute;n es permanente:</p>
                    <ul class="text-xs text-red-600 space-y-0.5 ml-3 list-disc">
                        <li>Se eliminar&aacute;n todas sus postulaciones</li>
                        <li>Se eliminar&aacute;n sus notificaciones</li>
                        <li>Se eliminar&aacute; su perfil y datos personales</li>
                        <li>Se eliminar&aacute; su cuenta de usuario</li>
                    </ul>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl flex justify-end gap-3">
                <button type="button" onclick="cerrarModalEliminar()" class="px-4 py-2.5 border border-gray-300 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-100 transition">Cancelar</button>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Eliminar permanentemente
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleDropdown(event, id) {
        event.stopPropagation();
        document.querySelectorAll('[id^="dropdown-"]').forEach(function(el) {
            if (el.id !== id) el.classList.add('hidden');
        });
        var d = document.getElementById(id);
        d.classList.toggle('hidden');
        var btn = d.previousElementSibling;
        if (btn) btn.setAttribute('aria-expanded', !d.classList.contains('hidden'));
    }
    window.onclick = function(event) {
        if (!event.target.closest('button')) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(function(el) { el.classList.add('hidden'); });
        }
    };

    function abrirModalCrear()  { document.getElementById('modalCrear').classList.remove('hidden'); document.body.style.overflow = 'hidden'; focusTrap('modalCrear'); }
    function cerrarModalCrear() { document.getElementById('modalCrear').classList.add('hidden');    document.body.style.overflow = 'auto';   }
    document.getElementById('modalCrear').addEventListener('click', function(e) { if (e.target === this) cerrarModalCrear(); });

    function abrirModalEditar(id, nombre, apellido, titulo, telefono, dni, provinciaId) {
        document.getElementById('form-editar').action = '{{ url('admin/trabajadores') }}/' + id;
        document.getElementById('editar-nombre').value      = nombre || '';
        document.getElementById('editar-apellido').value    = apellido || '';
        document.getElementById('editar-titulo').value      = titulo || '';
        document.getElementById('editar-telefono').value    = telefono || '';
        document.getElementById('editar-dni').value         = dni || '';
        var sel = document.getElementById('editar-provincia');
        if (sel) sel.value = provinciaId || '';
        document.getElementById('editar-password').value         = '';
        document.getElementById('editar-password-confirm').value = '';

        // Fetch current email via AJAX
        fetch('{{ url('admin/trabajadores') }}/' + id + '/edit')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.user && data.user.email) {
                    document.getElementById('editar-email').value = data.user.email;
                }
            })
            .catch(function() { /* ignore */ });

        document.getElementById('modalEditar').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        focusTrap('modalEditar');
    }
    function cerrarModalEditar() { document.getElementById('modalEditar').classList.add('hidden'); document.body.style.overflow = 'auto'; }
    document.getElementById('modalEditar').addEventListener('click', function(e) { if (e.target === this) cerrarModalEditar(); });

    function abrirModalToggle(id, nombre, esActivo) {
        document.getElementById('form-toggle').action = '{{ url('admin/trabajadores') }}/' + id + '/estado';
        var icon = document.getElementById('toggle-icon');
        icon.className = 'w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 ' + (esActivo ? 'bg-orange-100' : 'bg-green-100');
        icon.innerHTML = esActivo
            ? '<svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>'
            : '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        document.getElementById('toggle-titulo').textContent  = esActivo ? 'Desactivar trabajador' : 'Activar trabajador';
        document.getElementById('toggle-nombre').textContent  = nombre;
        document.getElementById('toggle-mensaje').innerHTML   = esActivo
            ? '&iquest;Est&aacute;s seguro de que quer&eacute;s <strong>desactivar</strong> a este trabajador?'
            : '&iquest;Est&aacute;s seguro de que quer&eacute;s <strong>activar</strong> a este trabajador?';
        var btn = document.getElementById('toggle-btn');
        btn.textContent = esActivo ? 'Desactivar' : 'Activar';
        btn.className   = 'px-5 py-2.5 text-white text-sm font-semibold rounded-xl transition ' + (esActivo ? 'bg-orange-500 hover:bg-orange-600' : 'bg-green-600 hover:bg-green-700');
        document.getElementById('modalToggle').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        focusTrap('modalToggle');
    }
    function cerrarModalToggle() { document.getElementById('modalToggle').classList.add('hidden'); document.body.style.overflow = 'auto'; }

    function abrirModalEliminar(id, nombre) {
        document.getElementById('form-eliminar').action = '{{ url('admin/trabajadores') }}/' + id;
        document.getElementById('eliminar-nombre').textContent = nombre;
        document.getElementById('modalEliminar').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        focusTrap('modalEliminar');
    }
    function cerrarModalEliminar() { document.getElementById('modalEliminar').classList.add('hidden'); document.body.style.overflow = 'auto'; }

    document.getElementById('modalToggle').addEventListener('click',   function(e) { if (e.target === document.getElementById('modalToggle'))   cerrarModalToggle();   });
    document.getElementById('modalEliminar').addEventListener('click', function(e) { if (e.target === document.getElementById('modalEliminar')) cerrarModalEliminar(); });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') { cerrarModalCrear(); cerrarModalEditar(); cerrarModalToggle(); cerrarModalEliminar(); }
    });

    function togglePass(id) {
        var i = document.getElementById(id);
        i.type = i.type === 'password' ? 'text' : 'password';
    }

    function focusTrap(modalId) {
        var modal = document.getElementById(modalId);
        var focusable = modal.querySelectorAll('button, input, select, textarea, [tabindex]:not([tabindex="-1"])');
        if (focusable.length === 0) return;
        var first = focusable[0];
        var last = focusable[focusable.length - 1];
        first.focus();
        function handler(e) {
            if (e.key === 'Tab') {
                if (e.shiftKey) {
                    if (document.activeElement === first) { e.preventDefault(); last.focus(); }
                } else {
                    if (document.activeElement === last) { e.preventDefault(); first.focus(); }
                }
            }
        }
        modal.addEventListener('keydown', handler);
        var observer = new MutationObserver(function() {
            if (modal.classList.contains('hidden')) {
                modal.removeEventListener('keydown', handler);
                observer.disconnect();
            }
        });
        observer.observe(modal, { attributes: true, attributeFilter: ['class'] });
    }
</script>
@endsection
