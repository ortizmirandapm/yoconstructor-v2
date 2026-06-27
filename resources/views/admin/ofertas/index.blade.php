@extends('layouts.admin')

@section('title', 'Ofertas')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gesti&oacute;n de Ofertas</h1>
            <p class="text-sm text-gray-500 mt-1">Administrar ofertas laborales registradas</p>
        </div>
    </div>

    {{-- ERRORES DE VALIDACION --}}
    @if($errors->any())
        <div id="toast-error" role="alert" aria-live="assertive" aria-atomic="true" class="fixed top-6 right-6 z-[9999] flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl shadow-xl max-w-sm transition-all duration-500">
            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
            <div>
                <p class="text-sm font-semibold mb-1">Error al guardar:</p>
                <ul class="text-xs space-y-0.5 list-disc ml-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button onclick="this.parentElement.remove()" class="ml-auto text-red-400 hover:text-red-600 flex-shrink-0">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </button>
        </div>
        <script>setTimeout(function(){var t=document.getElementById("toast-error");if(t)t.remove();},6000);</script>
    @endif

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
        <form method="GET" action="{{ route('admin.ofertas.index') }}" class="flex flex-col lg:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Buscar</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/></svg>
                    <input type="text" name="buscar" value="{{ request('buscar') }}"
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition"
                        placeholder="Buscar por t&iacute;tulo o empresa...">
                </div>
            </div>
            <div class="w-full sm:w-44">
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Rubro</label>
                <select name="rubro"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition">
                    <option value="">Todos</option>
                    @foreach($rubros as $rubro)
                        <option value="{{ $rubro->id }}" {{ request('rubro') == $rubro->id ? 'selected' : '' }}>{{ $rubro->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-40">
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Modalidad</label>
                <select name="modalidad"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition">
                    <option value="">Todas</option>
                    <option value="Presencial" {{ request('modalidad') === 'Presencial' ? 'selected' : '' }}>Presencial</option>
                    <option value="Remoto" {{ request('modalidad') === 'Remoto' ? 'selected' : '' }}>Remoto</option>
                    <option value="Híbrido" {{ request('modalidad') === 'Híbrido' ? 'selected' : '' }}>H&iacute;brido</option>
                </select>
            </div>
            <button type="submit"
                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                Filtrar
            </button>
            @if(request()->anyFilled(['buscar', 'estado', 'rubro', 'modalidad']))
                <a href="{{ route('admin.ofertas.index') }}"
                    class="px-4 py-2.5 border border-gray-300 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition text-center">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    {{-- ESTADO PILLS --}}
    <div class="flex items-center gap-2 flex-wrap mb-4">
        <a href="{{ route('admin.ofertas.index', array_merge(request()->except('estado'), ['estado' => ''])) }}"
            class="px-4 py-1.5 text-xs font-semibold rounded-full transition {{ !request('estado') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            Todas
        </a>
        <a href="{{ route('admin.ofertas.index', array_merge(request()->except('estado'), ['estado' => 'Activa'])) }}"
            class="px-4 py-1.5 text-xs font-semibold rounded-full transition {{ request('estado') === 'Activa' ? 'bg-green-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            Activas ({{ $totalActivas }})
        </a>
        <a href="{{ route('admin.ofertas.index', array_merge(request()->except('estado'), ['estado' => 'Pausada'])) }}"
            class="px-4 py-1.5 text-xs font-semibold rounded-full transition {{ request('estado') === 'Pausada' ? 'bg-yellow-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            Pausadas ({{ $totalPausadas }})
        </a>
        <a href="{{ route('admin.ofertas.index', array_merge(request()->except('estado'), ['estado' => 'Cerrada'])) }}"
            class="px-4 py-1.5 text-xs font-semibold rounded-full transition {{ request('estado') === 'Cerrada' ? 'bg-red-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            Cerradas ({{ $totalCerradas }})
        </a>
    </div>

    {{-- TABLE --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Oferta</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Empresa</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Post.</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Creada</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($ofertas as $oferta)
                        @php
                            $estadoColors = [
                                'Activa' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-200', 'dot' => 'bg-green-500'],
                                'Pausada' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'border' => 'border-yellow-200', 'dot' => 'bg-yellow-500'],
                                'Cerrada' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200', 'dot' => 'bg-red-500'],
                                'Borrador' => ['bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'border' => 'border-gray-200', 'dot' => 'bg-gray-400'],
                                'Inactiva' => ['bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'border' => 'border-gray-200', 'dot' => 'bg-gray-400'],
                            ];
                            $c = $estadoColors[$oferta->estado->value] ?? $estadoColors['Inactiva'];
                            $rubroNombre = $oferta->rubro?->nombre;
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-4">
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-800 truncate max-w-[250px]">{{ $oferta->titulo }}</p>
                                    @if($rubroNombre)
                                        <p class="text-xs text-gray-400 truncate">{{ $rubroNombre }} &middot; {{ $oferta->modalidad->value }}</p>
                                    @else
                                        <p class="text-xs text-gray-400 truncate">{{ $oferta->modalidad->value }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4 hidden md:table-cell text-sm text-gray-600">
                                {{ $oferta->empresa?->nombre_empresa ?? '—' }}
                            </td>
                            <td class="px-4 py-4 text-center hidden lg:table-cell font-semibold text-gray-700">
                                {{ $oferta->postulaciones_count }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium {{ $c['bg'] }} {{ $c['text'] }} {{ $c['border'] }} border">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $c['dot'] }}"></span>
                                    {{ $oferta->estado->value }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center hidden sm:table-cell text-sm text-gray-400">
                                {{ $oferta->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="relative inline-block text-left">
                                    <button type="button"
                                        onclick="toggleDropdown(event, 'dropdown-{{ $oferta->id }}')"
                                        aria-haspopup="true" aria-expanded="false"
                                        class="flex items-center justify-center w-8 h-8 text-gray-500 rounded-full hover:bg-gray-100 focus:outline-none transition mx-auto"
                                        aria-label="Acciones">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                        </svg>
                                    </button>
                                    <div id="dropdown-{{ $oferta->id }}"
                                        class="hidden absolute right-0 z-[100] mt-2 w-44 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none"
                                        role="menu">
                                        <div class="py-1">
                                            <button type="button"
                                                onclick="abrirModalEditar({{ $oferta->id }})"
                                                class="flex w-full px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition text-left"
                                                role="menuitem">
                                                Editar
                                            </button>
                                            <button type="button"
                                                onclick="abrirModalToggle({{ $oferta->id }}, '{{ addslashes($oferta->titulo) }}', '{{ $oferta->estado->value }}')"
                                                class="flex w-full px-4 py-2 text-sm text-left transition {{ $oferta->estado->value === 'Activa' ? 'text-yellow-700 hover:bg-yellow-50' : 'text-green-700 hover:bg-green-50' }}"
                                                role="menuitem">
                                                {{ $oferta->estado->value === 'Activa' ? 'Pausar' : 'Activar' }}
                                            </button>
                                        </div>
                                        <div class="py-1">
                                            <button type="button"
                                                onclick="abrirModalEliminar({{ $oferta->id }}, '{{ addslashes($oferta->titulo) }}')"
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
                                <p class="text-gray-400 text-sm">No hay ofertas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($ofertas->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $ofertas->links() }}
            </div>
        @endif
    </div>
</div>

{{-- MODAL EDITAR --}}
<div id="modalEditar" role="dialog" aria-modal="true" aria-labelledby="modal-editar-titulo" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-hidden flex flex-col">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4 flex items-center justify-between flex-shrink-0">
            <h3 id="modal-editar-titulo" class="font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Editar oferta
            </h3>
            <button onclick="cerrarModalEditar()" class="text-white hover:text-indigo-200 transition" aria-label="Cerrar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="" id="form-editar" class="overflow-y-auto flex-1">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label for="editar-titulo" class="block text-xs font-semibold text-gray-600 mb-1.5">T&iacute;tulo <span class="text-red-500">*</span></label>
                    <input type="text" name="titulo" id="editar-titulo" required
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                </div>
                <div>
                    <label for="editar-descripcion" class="block text-xs font-semibold text-gray-600 mb-1.5">Descripci&oacute;n <span class="text-red-500">*</span></label>
                    <textarea name="descripcion" id="editar-descripcion" rows="2" required
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="editar-tipo-contrato" class="block text-xs font-semibold text-gray-600 mb-1.5">Tipo de contrato <span class="text-red-500">*</span></label>
                        <select name="tipo_contrato" id="editar-tipo-contrato"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="Tiempo completo">Tiempo completo</option>
                            <option value="Medio tiempo">Medio tiempo</option>
                            <option value="Por proyecto">Por proyecto</option>
                            <option value="Pasantía">Pasant&iacute;a</option>
                        </select>
                    </div>
                    <div>
                        <label for="editar-modalidad" class="block text-xs font-semibold text-gray-600 mb-1.5">Modalidad <span class="text-red-500">*</span></label>
                        <select name="modalidad" id="editar-modalidad"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="Presencial">Presencial</option>
                            <option value="Remoto">Remoto</option>
                            <option value="Híbrido">H&iacute;brido</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="editar-salario-min" class="block text-xs font-semibold text-gray-600 mb-1.5">Salario m&iacute;n.</label>
                        <input type="number" name="salario_min" id="editar-salario-min" step="0.01" min="0"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <div>
                        <label for="editar-salario-max" class="block text-xs font-semibold text-gray-600 mb-1.5">Salario m&aacute;x.</label>
                        <input type="number" name="salario_max" id="editar-salario-max" step="0.01" min="0"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                </div>
                <div>
                    <label for="editar-requisitos" class="block text-xs font-semibold text-gray-600 mb-1.5">Requisitos</label>
                    <textarea name="requisitos" id="editar-requisitos" rows="2"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="editar-estado" class="block text-xs font-semibold text-gray-600 mb-1.5">Estado</label>
                        <select name="estado" id="editar-estado"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="Activa">Activa</option>
                            <option value="Pausada">Pausada</option>
                            <option value="Cerrada">Cerrada</option>
                        </select>
                    </div>
                    <div>
                        <label for="editar-rubro" class="block text-xs font-semibold text-gray-600 mb-1.5">Rubro</label>
                        <select name="rubro_id" id="editar-rubro"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="">Sin rubro</option>
                            @foreach($rubros as $rubro)
                                <option value="{{ $rubro->id }}">{{ $rubro->nombre }}</option>
                            @endforeach
                        </select>
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
                <h3 id="eliminar-titulo" class="font-bold text-gray-900">Eliminar oferta</h3>
                <p id="eliminar-nombre" class="text-xs text-gray-400"></p>
            </div>
        </div>
        <form method="POST" action="" id="form-eliminar">
            @csrf
            @method('DELETE')
            <div class="px-6 py-5">
                <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
                    <p class="text-xs text-red-700 font-semibold mb-1">Esta acci&oacute;n enviar&aacute; la oferta a la papelera:</p>
                    <ul class="text-xs text-red-600 space-y-0.5 ml-3 list-disc">
                        <li>Se ocultar&aacute; de las b&uacute;squedas p&uacute;blicas</li>
                        <li>Se puede restaurar desde la empresa</li>
                    </ul>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl flex justify-end gap-3">
                <button type="button" onclick="cerrarModalEliminar()" class="px-4 py-2.5 border border-gray-300 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-100 transition">Cancelar</button>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Eliminar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleDropdown(event, id) {
        event.stopPropagation();
        document.querySelectorAll('[id^="dropdown-"]').forEach(function(el) { if (el.id !== id) el.classList.add('hidden'); });
        var d = document.getElementById(id);
        d.classList.toggle('hidden');
        var btn = d.previousElementSibling;
        if (btn) btn.setAttribute('aria-expanded', !d.classList.contains('hidden'));
    }
    window.onclick = function(event) {
        if (!event.target.closest('button')) { document.querySelectorAll('[id^="dropdown-"]').forEach(function(el) { el.classList.add('hidden'); }); }
    };

    function abrirModalEditar(id) {
        document.getElementById('form-editar').action = '{{ url("admin/ofertas") }}/' + id;
        fetch('{{ url("admin/ofertas") }}/' + id + '/edit')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                document.getElementById('editar-titulo').value = data.titulo || '';
                document.getElementById('editar-descripcion').value = data.descripcion || '';
                var tcSel = document.getElementById('editar-tipo-contrato');
                if (tcSel) tcSel.value = data.tipo_contrato || 'Tiempo completo';
                var modSel = document.getElementById('editar-modalidad');
                if (modSel) modSel.value = data.modalidad || 'Presencial';
                var salMin = document.getElementById('editar-salario-min');
                if (salMin) salMin.value = data.salario_min || '';
                var salMax = document.getElementById('editar-salario-max');
                if (salMax) salMax.value = data.salario_max || '';
                var req = document.getElementById('editar-requisitos');
                if (req) req.value = data.requisitos || '';
                var estadoSel = document.getElementById('editar-estado');
                if (estadoSel) estadoSel.value = data.estado || 'Activa';
                var rubroSel = document.getElementById('editar-rubro');
                if (rubroSel) rubroSel.value = data.rubro_id || '';
                document.getElementById('modalEditar').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                focusTrap('modalEditar');
            })
            .catch(function() { alert('Error al cargar datos de la oferta'); });
    }
    function cerrarModalEditar() { document.getElementById('modalEditar').classList.add('hidden'); document.body.style.overflow = 'auto'; }
    document.getElementById('modalEditar').addEventListener('click', function(e) { if (e.target === this) cerrarModalEditar(); });

    function abrirModalToggle(id, titulo, estadoActual) {
        document.getElementById('form-toggle').action = '{{ url("admin/ofertas") }}/' + id + '/estado';
        var esActiva = estadoActual === 'Activa';
        var icon = document.getElementById('toggle-icon');
        icon.className = 'w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 ' + (esActiva ? 'bg-yellow-100' : 'bg-green-100');
        icon.innerHTML = esActiva
            ? '<svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>'
            : '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        document.getElementById('toggle-titulo').textContent = esActiva ? 'Pausar oferta' : 'Activar oferta';
        document.getElementById('toggle-nombre').textContent = titulo;
        document.getElementById('toggle-mensaje').innerHTML = esActiva
            ? '&iquest;Est&aacute;s seguro de que quer&eacute;s <strong>pausar</strong> esta oferta?'
            : '&iquest;Est&aacute;s seguro de que quer&eacute;s <strong>activar</strong> esta oferta?';
        var btn = document.getElementById('toggle-btn');
        btn.textContent = esActiva ? 'Pausar' : 'Activar';
        btn.className = 'px-5 py-2.5 text-white text-sm font-semibold rounded-xl transition ' + (esActiva ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-600 hover:bg-green-700');
        document.getElementById('modalToggle').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        focusTrap('modalToggle');
    }
    function cerrarModalToggle() { document.getElementById('modalToggle').classList.add('hidden'); document.body.style.overflow = 'auto'; }

    function abrirModalEliminar(id, titulo) {
        document.getElementById('form-eliminar').action = '{{ url("admin/ofertas") }}/' + id;
        document.getElementById('eliminar-nombre').textContent = titulo;
        document.getElementById('modalEliminar').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        focusTrap('modalEliminar');
    }
    function cerrarModalEliminar() { document.getElementById('modalEliminar').classList.add('hidden'); document.body.style.overflow = 'auto'; }

    document.getElementById('modalToggle').addEventListener('click',   function(e) { if (e.target === document.getElementById('modalToggle'))   cerrarModalToggle();   });
    document.getElementById('modalEliminar').addEventListener('click', function(e) { if (e.target === document.getElementById('modalEliminar')) cerrarModalEliminar(); });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') { cerrarModalEditar(); cerrarModalToggle(); cerrarModalEliminar(); }
    });

    function focusTrap(modalId) {
        var modal = document.getElementById(modalId);
        var focusable = modal.querySelectorAll('button, input, select, textarea, [tabindex]:not([tabindex="-1"])');
        if (focusable.length === 0) return;
        var first = focusable[0], last = focusable[focusable.length - 1];
        first.focus();
        function handler(e) {
            if (e.key === 'Tab') {
                if (e.shiftKey) { if (document.activeElement === first) { e.preventDefault(); last.focus(); } }
                else { if (document.activeElement === last) { e.preventDefault(); first.focus(); } }
            }
        }
        modal.addEventListener('keydown', handler);
        new MutationObserver(function(muts, obs) {
            if (modal.classList.contains('hidden')) { modal.removeEventListener('keydown', handler); obs.disconnect(); }
        }).observe(modal, { attributes: true, attributeFilter: ['class'] });
    }
</script>
@endsection
