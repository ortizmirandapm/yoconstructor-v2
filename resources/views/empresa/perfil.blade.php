@php
    $page = 'perfil-empresa';
    $pageTitle = 'Perfil de Empresa';
@endphp

@extends('layouts.empresa')

@section('title', 'Empresa')
@section('subtitle', '')

@section('content')
<div class="min-h-screen bg-gray-50">

    {{-- Header --}}
    <div class="bg-gray-50 border-b border-gray-200 px-6 py-5">
        <div class="flex items-center justify-between max-w-5xl">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Perfil de Empresa</h1>
                <p class="text-sm text-gray-500 mt-0.5">Información visible para los trabajadores</p>
            </div>
            <button onclick="abrirModalEditar()"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar perfil
            </button>
        </div>
    </div>

    <div class="px-6 py-6 max-w-5xl space-y-5">

        {{-- Card identidad --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
            <div class="flex flex-col sm:flex-row sm:items-center gap-5">
                @if ($empresa->logo)
                <img src="{{ asset($empresa->logo) }}"
                     class="w-20 h-20 rounded-2xl object-cover border border-gray-200 flex-shrink-0" alt="Logo">
                @else
                <div class="w-20 h-20 rounded-2xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-10 h-10 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                @endif
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $empresa->nombre_empresa ?? '' }}</h2>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $empresa->razon_social ?? 'Sin razón social' }}</p>
                    @if ($empresa->rubro)
                    <span class="mt-2 inline-block text-xs font-semibold bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-full">
                        {{ $empresa->rubro->nombre }}
                    </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Card datos --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-5">Información general</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-5">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">CUIT</p>
                    <p class="text-sm text-gray-800">{{ $empresa->cuit ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Provincia</p>
                    <p class="text-sm text-gray-800">{{ $empresa->provincia?->nombre ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Teléfono</p>
                    <p class="text-sm text-gray-800">{!! $empresa->telefono ? e($empresa->telefono) : '<span class="text-gray-400 italic">No especificado</span>' !!}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Email de contacto</p>
                    <p class="text-sm text-gray-800">{!! $empresa->email_contacto ? e($empresa->email_contacto) : '<span class="text-gray-400 italic">No especificado</span>' !!}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Miembro desde</p>
                    <p class="text-sm text-gray-800">{{ $empresa->created_at ? $empresa->created_at->format('d/m/Y') : '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Domicilio</p>
                    <p class="text-sm text-gray-800">{!! $empresa->domicilio ? e($empresa->domicilio) : '<span class="text-gray-400 italic">No especificado</span>' !!}</p>
                </div>
            </div>
        </div>

        {{-- Card descripción --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Descripción de la empresa</h3>
            @if ($empresa->descripcion)
                <p class="text-sm text-gray-800 leading-relaxed whitespace-pre-line">{{ $empresa->descripcion }}</p>
            @else
                <p class="text-sm text-gray-400 italic">Sin descripción. Agregá una para que los trabajadores conozcan tu empresa.</p>
            @endif
        </div>

    </div>
</div>

{{-- Modal de Edición --}}
<div id="modalEditar" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden flex flex-col">

        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4 flex items-center justify-between flex-shrink-0">
            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar Perfil de Empresa
            </h3>
            <button onclick="cerrarModalEditar()" class="text-white hover:text-indigo-200 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('empresa.perfil.update') }}" enctype="multipart/form-data" class="overflow-y-auto flex-1">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">

                {{-- Logo upload --}}
                <div class="flex items-center gap-5 pb-5 border-b border-gray-100">
                    <div class="relative flex-shrink-0">
                        <img id="preview-logo"
                             src="{{ $empresa->logo ? asset($empresa->logo) : 'https://ui-avatars.com/api/?name=' . urlencode($empresa->nombre_empresa ?? 'E') . '&background=4f46e5&color=fff' }}"
                             class="w-20 h-20 rounded-2xl object-cover border-2 border-gray-200" alt="Logo preview">
                        <label for="logo" class="absolute -bottom-1 -right-1 bg-indigo-600 hover:bg-indigo-700 text-white p-1.5 rounded-full cursor-pointer shadow transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </label>
                        <input type="file" id="logo" name="logo" accept="image/*" class="hidden" onchange="previewImagen(event)">
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Logo de la Empresa</p>
                        <p class="text-xs text-gray-400 mt-0.5">JPG, PNG o GIF — máx. 5MB</p>
                        <button type="button" onclick="document.getElementById('logo').click()"
                            class="mt-1.5 text-xs text-indigo-600 hover:text-indigo-800 font-medium">Cambiar imagen</button>
                    </div>
                </div>

                {{-- Campos --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nombre de la Empresa <span class="text-red-500">*</span></label>
                        <input type="text" name="nombre_empresa" required
                               value="{{ old('nombre_empresa', $empresa->nombre_empresa) }}"
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        @error('nombre_empresa') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Razón Social</label>
                        <input type="text" name="razon_social"
                               value="{{ old('razon_social', $empresa->razon_social) }}"
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">CUIT <span class="text-red-500">*</span></label>
                        <input type="text" name="cuit" id="cuit-input" required
                               value="{{ old('cuit', $empresa->cuit) }}"
                               maxlength="13"
                               placeholder="30-12345678-9"
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        @error('cuit') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Rubro <span class="text-red-500">*</span></label>
                        <select name="rubro_id" required class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="">Seleccioná un rubro</option>
                            @foreach ($rubros as $rubro)
                                <option value="{{ $rubro->id }}" {{ old('rubro_id', $empresa->rubro_id) == $rubro->id ? 'selected' : '' }}>{{ $rubro->nombre }}</option>
                            @endforeach
                        </select>
                        @error('rubro_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Provincia <span class="text-red-500">*</span></label>
                        <select name="provincia_id" required class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="">Seleccioná una provincia</option>
                            @foreach ($provincias as $provincia)
                                <option value="{{ $provincia->id }}" {{ old('provincia_id', $empresa->provincia_id) == $provincia->id ? 'selected' : '' }}>{{ $provincia->nombre }}</option>
                            @endforeach
                        </select>
                        @error('provincia_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Teléfono</label>
                        <input type="tel" name="telefono"
                               value="{{ old('telefono', $empresa->telefono) }}"
                               placeholder="011-4567-8901"
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email de contacto</label>
                        <input type="email" name="email_contacto"
                               value="{{ old('email_contacto', $empresa->email_contacto) }}"
                               placeholder="contacto@empresa.com"
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        @error('email_contacto') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Domicilio</label>
                        <input type="text" name="domicilio"
                               value="{{ old('domicilio', $empresa->domicilio) }}"
                               placeholder="Av. Siempre Viva 742, Buenos Aires"
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Descripción de la empresa</label>
                        <textarea name="descripcion" rows="4"
                                  class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition resize-none"
                                  placeholder="Contá brevemente a qué se dedica tu empresa...">{{ old('descripcion', $empresa->descripcion) }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Visible para los trabajadores en el perfil público de tu empresa.</p>
                        @error('descripcion') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end gap-3 flex-shrink-0">
                <button type="button" onclick="cerrarModalEditar()"
                    class="px-4 py-2.5 border border-gray-300 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-100 transition">
                    Descartar cambios
                </button>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Toast container --}}
<div id="toast-container" class="fixed bottom-6 right-6 z-[9999] flex flex-col gap-3 items-end pointer-events-none [&>*]:pointer-events-auto"></div>

@endsection

@push('scripts')
<script>
// ── Toast ─────────────────────────────────────────────────────────────────────
function showToast(msg, type = 'success') {
    const id  = 'toast-' + Date.now();
    const cfg = {
        success: { border: 'border-green-200', bar: 'bg-green-500', icon: `<svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>` },
        error:   { border: 'border-red-200',   bar: 'bg-red-400',   icon: `<svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>` }
    };
    const c = cfg[type] || cfg.success;
    const t = document.createElement('div');
    t.id        = id;
    t.className = `flex items-center gap-3 bg-white border ${c.border} rounded-2xl shadow-lg px-4 py-3.5 min-w-[280px] max-w-sm translate-x-full opacity-0 transition-all duration-300 ease-out relative overflow-hidden`;
    t.innerHTML = `${c.icon}<p class="text-sm font-medium text-gray-800 flex-1">${msg}</p>
    <button onclick="removeToast('${id}')" class="text-gray-400 hover:text-gray-600 ml-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="absolute bottom-0 left-0 h-0.5 w-full ${c.bar} origin-left" id="bar-${id}"></div>`;
    document.getElementById('toast-container').appendChild(t);
    requestAnimationFrame(() => requestAnimationFrame(() => {
        t.classList.replace('translate-x-full', 'translate-x-0');
        t.classList.replace('opacity-0', 'opacity-100');
    }));
    document.getElementById('bar-' + id).style.cssText = 'transition:transform 4s linear;transform:scaleX(0)';
    setTimeout(() => removeToast(id), 4200);
}
function removeToast(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.add('translate-x-full', 'opacity-0');
    setTimeout(() => el.remove(), 300);
}

// ── Modal editar ──────────────────────────────────────────────────────────────
function abrirModalEditar() {
    document.getElementById('modalEditar').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function cerrarModalEditar() {
    document.getElementById('modalEditar').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
document.getElementById('modalEditar').addEventListener('click', function(e) { if (e.target === this) cerrarModalEditar(); });

// ── Preview logo ──────────────────────────────────────────────────────────────
function previewImagen(event) {
    const file = event.target.files[0];
    if (!file) return;
    if (file.size > 5*1024*1024) { showToast('La imagen supera los 5MB', 'error'); event.target.value = ''; return; }
    if (!['image/jpeg','image/jpg','image/png','image/gif'].includes(file.type)) { showToast('Formato no permitido', 'error'); event.target.value = ''; return; }
    const reader = new FileReader();
    reader.onload = e => { document.getElementById('preview-logo').src = e.target.result; };
    reader.readAsDataURL(file);
}

// ── Formato CUIT ──────────────────────────────────────────────────────────────
document.getElementById('cuit-input')?.addEventListener('input', function(e) {
    let v = e.target.value.replace(/\D/g, '');
    if (v.length > 2)  v = v.slice(0, 2)  + '-' + v.slice(2);
    if (v.length > 11) v = v.slice(0, 11) + '-' + v.slice(11);
    e.target.value = v.slice(0, 13);
});

// ── Escape global ─────────────────────────────────────────────────────────────
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { cerrarModalEditar(); }
});

// ── Toast desde session flash ─────────────────────────────────────────────────
window.addEventListener('DOMContentLoaded', () => {
    @if (session('toast'))
    showToast('{{ session('toast') }}', '{{ session('toast_tipo', 'success') }}');
    @endif
    @if ($errors->any())
    showToast('Corregí los errores del formulario.', 'error');
    @endif
});
</script>
@endpush
