@extends('layouts.trabajador')

@section('header')

@endsection

@section('content')
    @if(session('success'))
        <div class="mb-5 flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-medium">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 flex items-center gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-medium">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 flex flex-col gap-2 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-medium">
            @foreach($errors->all() as $error)
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    {{ $error }}
                </div>
            @endforeach
        </div>
    @endif

    <div class="space-y-6">

        {{-- Card Header --}}
        <div class="mb-2">
            <h2 class="text-2xl font-extrabold text-gray-900">Configuraciones de cuenta</h2>
            <p class="mt-1 text-sm text-gray-500">Administrá tu visibilidad, contraseña y opciones de cuenta.</p>
        </div>

        {{-- Card Visibilidad --}}
        <div class="bg-gray-50 border border-gray-200 rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-800">Visibilidad en búsquedas</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Controlá si las empresas pueden encontrarte</p>
                </div>
            </div>
            <div class="px-5 py-5">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-700 mb-1">Aparecer en búsqueda de empresas</p>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            Cuando está activado, las empresas pueden encontrar tu perfil. Desactivalo si no querés ser contactado por el momento.
                        </p>
                        <div class="mt-3">
                            @if($user->visible_busqueda)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full border border-green-200">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                    Visible para empresas
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gray-100 text-gray-500 text-xs font-semibold rounded-full border border-gray-200">
                                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                    No visible
                                </span>
                            @endif
                        </div>
                    </div>
                    <form method="POST" action="{{ route('trabajador.configuracion.visibilidad') }}">
                        @csrf
                        <input type="hidden" name="visible_busqueda" value="{{ $user->visible_busqueda ? 0 : 1 }}">
                        <button type="submit"
                            class="relative inline-flex h-7 items-center rounded-full transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex-shrink-0 {{ $user->visible_busqueda ? 'bg-blue-600' : 'bg-gray-300' }}"
                            style="width:52px;">
                            <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow-md transition-transform duration-300 {{ $user->visible_busqueda ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Card Cambiar contraseña --}}
        <div class="bg-gray-50 border border-gray-200 rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 flex items-center gap-3">
                <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-800">Cambiar contraseña</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Actualizá tu contraseña de acceso</p>
                </div>
            </div>
            <div class="px-5 py-5">
                <form method="POST" action="{{ route('trabajador.configuracion.password') }}">
                    @csrf
                    <div class="grid grid-cols-1 gap-4 max-w-md">
                        <div>
                            <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-1">Contraseña actual</label>
                            <div class="relative">
                                <input type="password" name="current_password" id="current_password" required
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white transition">
                            </div>
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Nueva contraseña</label>
                            <input type="password" name="password" id="password" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white transition">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Repetir nueva contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white transition">
                        </div>
                        <div class="pt-1">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/>
                                </svg>
                                Actualizar contraseña
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Card Eliminar cuenta --}}
        <div class="bg-gray-50 border border-red-100 rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-red-100 flex items-center gap-3">
                <div class="w-9 h-9 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-red-700">Eliminar cuenta</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Esta acción desactiva tu acceso permanentemente</p>
                </div>
            </div>
            <div class="px-5 py-5">
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-5">
                    <p class="text-sm text-red-700 font-medium mb-2">¿Qué pasa cuando eliminás tu cuenta?</p>
                    <ul class="text-xs text-red-600 space-y-1.5">
                        <li class="flex items-start gap-2"><svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>Tu cuenta quedará desactivada y no podrás iniciar sesión</li>
                        <li class="flex items-start gap-2"><svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>Tu perfil dejará de aparecer en búsquedas de empresas</li>
                        <li class="flex items-start gap-2"><svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>Tus postulaciones activas quedarán sin efecto</li>
                        <li class="flex items-start gap-2"><svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg><span class="text-amber-700 font-medium">Podés reactivarla contactando al soporte</span></li>
                    </ul>
                </div>
                <button onclick="abrirModalEliminar()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Eliminar mi cuenta
                </button>
            </div>
        </div>

    </div>

    {{-- Modal eliminar cuenta --}}
    <div id="modalEliminar" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-extrabold text-gray-900">¿Estás seguro?</h3>
                        <p class="text-sm text-gray-500">Esta acción desactivará tu cuenta</p>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-4">
                    Para confirmar, escribí <span class="font-bold text-red-600 bg-red-50 px-1.5 py-0.5 rounded-lg">ELIMINAR</span> en el campo de abajo:
                </p>
                <form method="POST" action="{{ route('trabajador.configuracion.eliminar') }}">
                    @csrf
                    <input type="text" name="confirmar_texto" id="confirmarTexto"
                        placeholder="Escribí ELIMINAR" autocomplete="off"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-400 mb-4 transition bg-gray-50">
                    <div class="flex gap-3">
                        <button type="button" onclick="cerrarModalEliminar()"
                            class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition text-sm font-medium">
                            Cancelar
                        </button>
                        <button type="submit" id="btnConfirmarEliminar" disabled
                            class="flex-1 px-4 py-2.5 bg-red-300 text-white rounded-xl text-sm font-semibold cursor-not-allowed transition">
                            Confirmar eliminación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function abrirModalEliminar() {
        document.getElementById('modalEliminar').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        document.getElementById('confirmarTexto').value = '';
        const btn = document.getElementById('btnConfirmarEliminar');
        btn.disabled = true;
        btn.className = 'flex-1 px-4 py-2.5 bg-red-300 text-white rounded-xl text-sm font-semibold cursor-not-allowed transition';
        setTimeout(() => document.getElementById('confirmarTexto').focus(), 100);
    }
    function cerrarModalEliminar() {
        document.getElementById('modalEliminar').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    document.getElementById('confirmarTexto').addEventListener('input', function() {
        const btn = document.getElementById('btnConfirmarEliminar');
        if (this.value === 'ELIMINAR') {
            btn.disabled = false;
            btn.className = 'flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-semibold transition cursor-pointer';
        } else {
            btn.disabled = true;
            btn.className = 'flex-1 px-4 py-2.5 bg-red-300 text-white rounded-xl text-sm font-semibold cursor-not-allowed transition';
        }
    });
    document.getElementById('modalEliminar').addEventListener('click', function(e) {
        if (e.target === this) cerrarModalEliminar();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') cerrarModalEliminar();
    });
    </script>
    @endpush
@endsection
