@extends('layouts.admin')

@section('title', 'Empresas')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gesti&oacute;n de Empresas</h1>
            <p class="text-sm text-gray-500 mt-1">Administrar empresas registradas en la plataforma</p>
        </div>
        <button onclick="abrirModalCrear()"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nueva Empresa
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
        <form method="GET" action="{{ route('admin.empresas.index') }}" class="flex flex-col lg:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Buscar</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/></svg>
                    <input type="text" name="buscar" value="{{ request('buscar') }}"
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition"
                        placeholder="Buscar empresas...">
                </div>
            </div>
            <div class="w-full sm:w-48">
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Rubro</label>
                <select name="rubro"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition">
                    <option value="">Todos los rubros</option>
                    @foreach($rubros as $rubro)
                        <option value="{{ $rubro->id }}" {{ request('rubro') == $rubro->id ? 'selected' : '' }}>{{ $rubro->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                Filtrar
            </button>
            @if(request()->anyFilled(['buscar', 'estado', 'rubro']))
                <a href="{{ route('admin.empresas.index') }}"
                    class="w-full sm:w-auto px-4 py-2.5 border border-gray-300 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition text-center">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    {{-- ESTADO PILLS + RESULTS COUNT --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-3">
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.empresas.index', array_merge(request()->except('estado'), ['estado' => ''])) }}"
                class="px-4 py-1.5 text-xs font-semibold rounded-full transition {{ !request('estado') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Todas
            </a>
            <a href="{{ route('admin.empresas.index', array_merge(request()->except('estado'), ['estado' => 'activas'])) }}"
                class="px-4 py-1.5 text-xs font-semibold rounded-full transition {{ request('estado') === 'activas' ? 'bg-green-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Activas
            </a>
            <a href="{{ route('admin.empresas.index', array_merge(request()->except('estado'), ['estado' => 'inactivas'])) }}"
                class="px-4 py-1.5 text-xs font-semibold rounded-full transition {{ request('estado') === 'inactivas' ? 'bg-red-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                De baja
            </a>
        </div>
        <p class="text-sm text-gray-500">
            Resultados encontrados: <span class="font-semibold text-gray-700">{{ $empresas->total() }}</span>
        </p>
    </div>

    {{-- TABLE --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Empresa</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rubro</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Ofertas</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Postulaciones</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Reclutadores</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($empresas as $empresa)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-sm font-bold text-indigo-600">{{ strtoupper(substr($empresa->nombre_empresa, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">{{ $empresa->nombre_empresa }}</p>
                                        <p class="text-xs text-gray-400">{{ $empresa->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                @if($empresa->rubro)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs font-medium">
                                        {{ $empresa->rubro->nombre }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">&mdash;</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 text-center font-medium">{{ $empresa->ofertas_count }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 text-center font-medium">{{ $postulacionCounts[$empresa->id] ?? 0 }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 text-center font-medium">{{ $reclutadoresCounts[$empresa->id] ?? 0 }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($empresa->estado === 'activo')
                                    <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 bg-green-100 text-green-700 rounded-full font-medium">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                        Activa
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 bg-red-100 text-red-700 rounded-full font-medium">
                                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                        De baja
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="relative inline-block text-left">
                                    <button onclick="toggleDropdown({{ $empresa->id }})"
                                        aria-label="Men&uacute; de acciones" aria-haspopup="true" aria-expanded="false"
                                        class="w-9 h-9 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"/></svg>
                                    </button>
                                    <div id="dropdown-{{ $empresa->id }}"
                                        class="hidden absolute right-0 mt-1 w-48 bg-white border border-gray-200 rounded-xl shadow-xl z-50 py-1 origin-top-right">
                                        <button onclick="abrirModalEditar({{ $empresa->id }})"
                                            class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Editar
                                        </button>
                                        @if($empresa->estado === 'activo')
                                            <button onclick="abrirModalEstado({{ $empresa->id }}, '{{ $empresa->nombre_empresa }}', 'inactivo')"
                                                class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-amber-600 hover:bg-amber-50 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                Desactivar
                                            </button>
                                        @else
                                            <button onclick="abrirModalEstado({{ $empresa->id }}, '{{ $empresa->nombre_empresa }}', 'activo')"
                                                class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-green-600 hover:bg-green-50 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Activar
                                            </button>
                                        @endif
                                        <button onclick="abrirModalEliminar({{ $empresa->id }}, '{{ $empresa->nombre_empresa }}')"
                                            class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <p class="text-gray-500 text-sm">No se encontraron empresas</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($empresas->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $empresas->links() }}
            </div>
        @endif
    </div>
</div>

{{-- MODAL CREAR --}}
<div id="modalCrear" role="dialog" aria-modal="true" aria-labelledby="modalCrear-title" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                    <h3 id="modalCrear-title" class="font-bold text-gray-900">Nueva Empresa</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Complet&aacute; los datos de la nueva empresa</p>
                </div>
            </div>
            <button onclick="cerrarModal('modalCrear')" aria-label="Cerrar" class="p-2 hover:bg-gray-100 rounded-lg transition text-gray-400">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.empresas.store') }}">
            @csrf
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Raz&oacute;n social <span class="text-red-500">*</span></label>
                    <input type="text" name="razon_social" required aria-required="true"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition"
                        placeholder="Nombre de la empresa">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rubro <span class="text-red-500">*</span></label>
                    <select name="rubro_id" required aria-required="true"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition">
                        <option value="">Seleccionar rubro</option>
                        @foreach($rubros as $rubro)
                            <option value="{{ $rubro->id }}">{{ $rubro->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
                        <input type="text" name="nombre" required aria-required="true"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition"
                            placeholder="Nombre del usuario">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required aria-required="true"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition"
                            placeholder="correo@ejemplo.com">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contrase&ntilde;a <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" name="password" required aria-required="true" id="create-password"
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition pr-10"
                                placeholder="M&iacute;n. 8 caracteres">
                            <button type="button" onclick="togglePassword('create-password', 'create-eye')"
                                aria-label="Mostrar contrase&ntilde;a"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg id="create-eye" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar contrase&ntilde;a <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" required aria-required="true"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition"
                            placeholder="Repetir contrase&ntilde;a">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">CUIT</label>
                    <input type="text" name="cuit" id="create-cuit" maxlength="13"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition"
                        placeholder="XX-XXXXXXXX-X">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email de contacto</label>
                        <input type="email" name="email_contacto"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition"
                            placeholder="contacto@ejemplo.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tel&eacute;fono</label>
                        <input type="text" name="telefono"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition"
                            placeholder="+54 11 1234-5678">
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl flex justify-end gap-3">
                <button type="button" onclick="cerrarModal('modalCrear')"
                    class="px-4 py-2.5 border border-gray-300 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-100 transition">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                    Crear Empresa
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDITAR --}}
<div id="modalEditar" role="dialog" aria-modal="true" aria-labelledby="modalEditar-title" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <h3 id="modalEditar-title" class="font-bold text-gray-900">Editar Empresa</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Modific&aacute; los datos de la empresa</p>
                </div>
            </div>
            <button onclick="cerrarModal('modalEditar')" aria-label="Cerrar" class="p-2 hover:bg-gray-100 rounded-lg transition text-gray-400">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </button>
        </div>
        <form id="formEditar" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Raz&oacute;n social <span class="text-red-500">*</span></label>
                    <input type="text" name="razon_social" id="edit-razon_social" required aria-required="true"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rubro <span class="text-red-500">*</span></label>
                    <select name="rubro_id" id="edit-rubro_id" required aria-required="true"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition">
                        <option value="">Seleccionar rubro</option>
                        @foreach($rubros as $rubro)
                            <option value="{{ $rubro->id }}">{{ $rubro->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
                        <input type="text" name="nombre" id="edit-nombre" required aria-required="true"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="edit-email" required aria-required="true"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition">
                    </div>
                </div>
                <div>
                    <button type="button" onclick="toggleCredentialsSection()"
                        class="text-sm text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1.5">
                        <svg id="creds-arrow" class="w-4 h-4 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        Cambiar contrase&ntilde;a
                    </button>
                </div>
                <div id="creds-section" class="hidden space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nueva contrase&ntilde;a</label>
                            <div class="relative">
                                <input type="password" name="password" id="edit-password"
                                    class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition pr-10">
                                <button type="button" onclick="togglePassword('edit-password', 'edit-eye')"
                                    aria-label="Mostrar contrase&ntilde;a"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg id="edit-eye" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar contrase&ntilde;a</label>
                            <input type="password" name="password_confirmation" id="edit-password_confirmation"
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition">
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">CUIT</label>
                    <input type="text" name="cuit" id="edit-cuit" maxlength="13"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition"
                        placeholder="XX-XXXXXXXX-X">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email de contacto</label>
                        <input type="email" name="email_contacto" id="edit-email_contacto"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tel&eacute;fono</label>
                        <input type="text" name="telefono" id="edit-telefono"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Domicilio</label>
                    <input type="text" name="domicilio" id="edit-domicilio"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition"
                        placeholder="Direcci&oacute;n">
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl flex justify-end gap-3">
                <button type="button" onclick="cerrarModal('modalEditar')"
                    class="px-4 py-2.5 border border-gray-300 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-100 transition">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL CAMBIAR ESTADO --}}
<div id="modalEstado" role="dialog" aria-modal="true" aria-labelledby="modalEstado-title" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <div class="px-6 py-5 border-b border-gray-200 flex items-center gap-3">
            <div id="estado-icon" class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0">
            </div>
            <div>
                <h3 class="font-bold text-gray-900" id="modalEstado-title"></h3>
                <p class="text-xs text-gray-400 mt-0.5">Cambiar estado de la empresa</p>
            </div>
        </div>
        <div class="px-6 py-5">
            <p class="text-sm text-gray-600" id="estado-text"></p>
            <div id="estado-warning" class="hidden mt-3 flex items-start gap-2.5 p-3 bg-amber-50 border border-amber-200 rounded-xl">
                <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <p class="text-xs text-amber-700">Se pausar&aacute;n las ofertas activas de esta empresa.</p>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl flex justify-end gap-3">
            <button type="button" onclick="cerrarModal('modalEstado')"
                class="px-4 py-2.5 border border-gray-300 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-100 transition">
                Cancelar
            </button>
            <form id="formEstado" method="POST" action="">
                @csrf
                @method('PATCH')
                <button type="submit" id="estado-btn"
                    class="px-5 py-2.5 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                </button>
            </form>
        </div>
    </div>
</div>

{{-- MODAL ELIMINAR --}}
<div id="modalEliminar" role="dialog" aria-modal="true" aria-labelledby="modalEliminar-title" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <div class="px-6 py-5 border-b border-gray-200 flex items-center gap-3">
            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <div>
                <h3 id="modalEliminar-title" class="font-bold text-gray-900">Eliminar Empresa</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Esta acci&oacute;n no se puede deshacer</p>
            </div>
        </div>
        <div class="px-6 py-5">
            <p class="text-sm text-gray-600">¿Est&aacute;s seguro de que quer&eacute;s eliminar <strong id="eliminar-nombre"></strong>?</p>
            <div class="mt-3 flex items-start gap-2.5 p-3 bg-red-50 border border-red-200 rounded-xl">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                <p class="text-xs text-red-700">Se eliminar&aacute;n todas las ofertas, postulaciones, reclutadores y el usuario asociado.</p>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl flex justify-end gap-3">
            <button type="button" onclick="cerrarModal('modalEliminar')"
                class="px-4 py-2.5 border border-gray-300 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-100 transition">
                Cancelar
            </button>
            <form id="formEliminar" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                    Eliminar
                </button>
            </form>
        </div>
    </div>
</div>

<style>
@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}
</style>

@push('scripts')
<script>
// DROPDOWN
function toggleDropdown(id) {
    var d = document.getElementById('dropdown-' + id);
    if (!d) return;
    var btn = d.parentElement.querySelector('button[aria-haspopup="true"]');
    var isHidden = d.classList.contains('hidden');
    document.querySelectorAll('[id^="dropdown-"]').forEach(function(el) { el.classList.add('hidden'); });
    document.querySelectorAll('button[aria-haspopup="true"]').forEach(function(b) { b.setAttribute('aria-expanded', 'false'); });
    if (isHidden) {
        d.classList.remove('hidden');
        if (btn) btn.setAttribute('aria-expanded', 'true');
    }
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('[id^="dropdown-"]') && !e.target.closest('button[onclick*="toggleDropdown"]')) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(function(el) { el.classList.add('hidden'); });
        document.querySelectorAll('button[aria-haspopup="true"]').forEach(function(b) { b.setAttribute('aria-expanded', 'false'); });
    }
});

// MODALS — focus trap
function abrirModal(id) {
    var modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    var focusable = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
    if (focusable.length > 0) setTimeout(function() { focusable[0].focus(); }, 50);
    var firstEl = focusable[0];
    var lastEl = focusable[focusable.length - 1];
    modal._focusTrap = function(e) {
        if (e.key === 'Tab') {
            if (e.shiftKey && document.activeElement === firstEl) {
                e.preventDefault();
                if (lastEl) lastEl.focus();
            } else if (!e.shiftKey && document.activeElement === lastEl) {
                e.preventDefault();
                if (firstEl) firstEl.focus();
            }
        }
    };
    document.addEventListener('keydown', modal._focusTrap);
}
function cerrarModal(id) {
    var modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    if (modal._focusTrap) {
        document.removeEventListener('keydown', modal._focusTrap);
        delete modal._focusTrap;
    }
}
document.querySelectorAll('[id^="modal"]').forEach(function(m) {
    m.addEventListener('click', function(e) { if (e.target === this) cerrarModal(this.id); });
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('[id^="modal"]').forEach(function(m) {
            if (!m.classList.contains('hidden')) cerrarModal(m.id);
        });
    }
});

// CREATE
function abrirModalCrear() {
    abrirModal('modalCrear');
}

// EDIT - fetch empresa data and populate form
function abrirModalEditar(id) {
    var url = '{{ url("admin/empresas") }}/' + id + '/edit';
    fetch(url)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            document.getElementById('edit-razon_social').value = data.razon_social || '';
            document.getElementById('edit-rubro_id').value = data.rubro_id || '';
            document.getElementById('edit-nombre').value = data.user ? data.user.name : '';
            document.getElementById('edit-email').value = data.user ? data.user.email : '';
            document.getElementById('edit-cuit').value = data.cuit || '';
            document.getElementById('edit-email_contacto').value = data.email_contacto || '';
            document.getElementById('edit-telefono').value = data.telefono || '';
            document.getElementById('edit-domicilio').value = data.domicilio || '';
            document.getElementById('formEditar').action = '{{ url("admin/empresas") }}/' + id;
            document.getElementById('edit-password').value = '';
            document.getElementById('edit-password_confirmation').value = '';
            document.getElementById('creds-section').classList.add('hidden');
            abrirModal('modalEditar');
        })
        .catch(function() { alert('Error al cargar datos de la empresa'); });
}

function toggleCredentialsSection() {
    var s = document.getElementById('creds-section');
    var a = document.getElementById('creds-arrow');
    s.classList.toggle('hidden');
    a.classList.toggle('rotate-180');
}

// TOGGLE ESTADO
function abrirModalEstado(id, nombre, accion) {
    var esActivar = accion === 'activo';
    var icon = document.getElementById('estado-icon');
    icon.className = 'w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 ' + (esActivar ? 'bg-green-100' : 'bg-amber-100');
    icon.innerHTML = esActivar
        ? '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        : '<svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>';
    document.getElementById('modalEstado-title').textContent = esActivar ? 'Activar Empresa' : 'Desactivar Empresa';
    document.getElementById('estado-text').innerHTML = '\u00bfEst\u00e1s seguro de que quer\u00e9s ' + (esActivar ? 'activar' : 'desactivar') + ' <strong>' + nombre + '</strong>?';
    document.getElementById('estado-warning').classList.toggle('hidden', esActivar);
    var btn = document.getElementById('estado-btn');
    btn.textContent = esActivar ? 'Activar' : 'Desactivar';
    btn.className = 'px-5 py-2.5 text-white text-sm font-semibold rounded-xl transition shadow-sm ' + (esActivar ? 'bg-green-600 hover:bg-green-700' : 'bg-amber-600 hover:bg-amber-700');
    document.getElementById('formEstado').action = '{{ url("admin/empresas") }}/' + id + '/estado';
    abrirModal('modalEstado');
}

// DELETE
function abrirModalEliminar(id, nombre) {
    document.getElementById('eliminar-nombre').textContent = nombre;
    document.getElementById('formEliminar').action = '{{ url("admin/empresas") }}/' + id;
    abrirModal('modalEliminar');
}

// CUIT MASK
(function() {
    document.querySelectorAll('input[name="cuit"]').forEach(function(input) {
        input.addEventListener('input', function() {
            var v = this.value.replace(/[^0-9]/g, '');
            if (v.length > 2) v = v.slice(0, 2) + '-' + v.slice(2);
            if (v.length > 11) v = v.slice(0, 11) + '-' + v.slice(11, 12);
            this.value = v;
        });
    });
})();

// PASSWORD TOGGLE
function togglePassword(inputId, eyeId) {
    var i = document.getElementById(inputId);
    var e = document.getElementById(eyeId);
    var btn = e ? e.closest('button') : null;
    if (!i || !e) return;
    if (i.type === 'password') {
        i.type = 'text';
        e.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>';
        if (btn) btn.setAttribute('aria-label', 'Ocultar contrase\u00f1a');
    } else {
        i.type = 'password';
        e.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
        if (btn) btn.setAttribute('aria-label', 'Mostrar contrase\u00f1a');
    }
}
</script>
@endpush
@endsection
