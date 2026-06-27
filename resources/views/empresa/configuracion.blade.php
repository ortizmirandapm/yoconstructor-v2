@php
    $currentRoute = request()->route()->getName();
@endphp

@extends('layouts.empresa')

@section('title', 'Configuración')
@section('subtitle', 'Administrá las preferencias de tu cuenta de empresa')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">

    {{-- Encabezado --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Configuración</h1>
        <p class="text-gray-500 mt-1 text-sm">Administrá las preferencias de tu cuenta de empresa</p>
    </div>

    {{-- Alertas --}}
    @if(session('success'))
        <div class="flex items-center gap-3 mb-6 px-5 py-3.5 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-medium">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center gap-3 mb-6 px-5 py-3.5 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm font-medium">
            <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="flex flex-col gap-2 mb-6 px-5 py-3.5 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm font-medium">
            @foreach($errors->all() as $error)
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ $error }}
                </div>
            @endforeach
        </div>
    @endif

    <div class="max-w-3xl space-y-5">

        {{-- ═══ CAMBIAR CONTRASEÑA ═══════════════════════════════════════════ --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <button type="button" onclick="toggleSection('pass')"
                class="w-full flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition text-left">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Cambiar contraseña</p>
                        <p class="text-xs text-gray-400">Actualizá la contraseña de acceso a tu cuenta</p>
                    </div>
                </div>
                <svg id="arrow-pass" class="w-5 h-5 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div id="section-pass" class="hidden border-t border-gray-100">
                <form method="POST" action="{{ route('empresa.configuracion.password') }}" class="px-6 py-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Contraseña actual</label>
                        <input type="password" name="current_password" required
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                            placeholder="••••••••">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nueva contraseña</label>
                            <input type="password" name="password" required minlength="8"
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                                placeholder="Mínimo 8 caracteres">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Confirmar contraseña</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                                placeholder="Repetí la contraseña">
                        </div>
                    </div>
                    <div class="flex justify-end pt-1">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Actualizar contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ═══ PRIVACIDAD ═══════════════════════════════════════════════════ --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <button type="button" onclick="toggleSection('priv')"
                class="w-full flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition text-left">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-cyan-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Privacidad</p>
                        <p class="text-xs text-gray-400">Controlá la visibilidad de tu empresa</p>
                    </div>
                </div>
                <svg id="arrow-priv" class="w-5 h-5 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div id="section-priv" class="hidden border-t border-gray-100">
                <form method="POST" action="{{ route('empresa.configuracion.privacidad') }}" class="px-6 py-5">
                    @csrf
                    <div class="flex items-start justify-between gap-6 py-2">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">Perfil de empresa público</p>
                            <p class="text-xs text-gray-400 mt-1 leading-relaxed">
                                Cuando está activo, los trabajadores pueden ver tu perfil, logo, descripción y ofertas publicadas.
                                Si lo desactivás, tu empresa no será visible para ningún trabajador.
                            </p>
                            <div class="mt-3">
                                @if($empresa->perfil_publico ?? true)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full border border-green-200">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                        Visible para trabajadores
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gray-100 text-gray-500 text-xs font-semibold rounded-full border border-gray-200">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                        No visible
                                    </span>
                                @endif
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 mt-0.5">
                            <input type="checkbox" name="perfil_publico" value="1"
                                {{ ($empresa->perfil_publico ?? true) ? 'checked' : '' }}
                                class="sr-only peer" onchange="this.form.submit()">
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer
                                peer-checked:after:translate-x-full peer-checked:after:border-white
                                after:content-[''] after:absolute after:top-[2px] after:start-[2px]
                                after:bg-white after:border-gray-300 after:border after:rounded-full
                                after:h-5 after:w-5 after:transition-all peer-checked:bg-cyan-500"></div>
                        </label>
                    </div>
                </form>
            </div>
        </div>

        {{-- ═══ ZONA PELIGROSA ══════════════════════════════════════════════ --}}
        <div class="bg-white border border-red-200 rounded-2xl shadow-sm overflow-hidden">
            <button type="button" onclick="toggleSection('danger')"
                class="w-full flex items-center justify-between px-6 py-4 hover:bg-red-50 transition text-left">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-red-700">Zona peligrosa</p>
                        <p class="text-xs text-red-400">Acciones irreversibles sobre tu cuenta</p>
                    </div>
                </div>
                <svg id="arrow-danger" class="w-5 h-5 text-red-300 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div id="section-danger" class="hidden border-t border-red-100">
                <div class="px-6 py-5">

                    <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <p class="text-sm font-semibold text-red-800 mb-2">Al dar de baja tu cuenta:</p>
                        <ul class="text-xs text-red-700 space-y-1 ml-4 list-disc">
                            <li>Todas tus ofertas publicadas pasarán a estado <strong>Inactiva</strong></li>
                            <li>Los trabajadores no podrán ver tu perfil ni postularse</li>
                            <li>Tus reclutadores perderán acceso al sistema</li>
                            <li>Esta acción <strong>no se puede deshacer</strong> sin contactar soporte</li>
                        </ul>
                    </div>

                    <form method="POST" action="{{ route('empresa.configuracion.eliminar') }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                    Para confirmar, escribí <span class="font-mono bg-red-100 text-red-700 px-1.5 py-0.5 rounded text-xs">ELIMINAR</span>
                                </label>
                                <input type="text" name="confirm_text" id="confirmText" required
                                    placeholder="ELIMINAR"
                                    autocomplete="off"
                                    class="w-full sm:w-56 px-3 py-2.5 border border-red-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-400 transition font-mono tracking-widest">
                            </div>
                            <button type="button" onclick="confirmarBaja()"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Dar de baja la cuenta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Modal confirmación baja --}}
<div id="modalBaja" class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <div class="px-6 py-5 border-b border-gray-200 flex items-center gap-3">
            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-900">¿Dar de baja la cuenta?</h3>
                <p class="text-xs text-gray-400">Esta acción no se puede deshacer</p>
            </div>
        </div>
        <div class="px-6 py-5">
            <p class="text-sm text-gray-600 leading-relaxed">Tu cuenta quedará desactivada, todas tus ofertas pasarán a inactivo y perderás acceso al sistema. Para reactivarla deberás contactar a soporte.</p>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl flex justify-end gap-3">
            <button onclick="cerrarModalBaja()"
                class="px-4 py-2.5 border border-gray-300 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-100 transition">
                Cancelar
            </button>
            <button onclick="ejecutarBaja()"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Sí, dar de baja
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ── Toggle secciones ──────────────────────────────────────────────────
    function toggleSection(id) {
        var section = document.getElementById('section-' + id);
        var arrow   = document.getElementById('arrow-' + id);
        var isOpen  = !section.classList.contains('hidden');
        section.classList.toggle('hidden', isOpen);
        arrow.classList.toggle('rotate-180', !isOpen);
    }

    // ── Zona peligrosa ────────────────────────────────────────────────────
    function confirmarBaja() {
        var txt = document.getElementById('confirmText').value.trim();
        if (txt !== 'ELIMINAR') {
            alert('Primero escribí ELIMINAR en el campo de confirmación.');
            return;
        }
        document.getElementById('modalBaja').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function cerrarModalBaja() {
        document.getElementById('modalBaja').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function ejecutarBaja() {
        document.getElementById('modalBaja').querySelector('form')
            || document.querySelector('#section-danger form').submit();
        var form = document.querySelector('#section-danger form');
        if (form) form.submit();
    }

    document.getElementById('modalBaja').addEventListener('click', function(e) {
        if (e.target === this) cerrarModalBaja();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') cerrarModalBaja();
    });

    // ── Abrir zona peligrosa si hay error ─────────────────────────────────
    @if($errors->has('confirm_text'))
        document.getElementById('section-danger').classList.remove('hidden');
        document.getElementById('arrow-danger').classList.add('rotate-180');
        document.getElementById('section-danger').scrollIntoView({ behavior: 'smooth', block: 'center' });
    @endif
</script>
@endpush