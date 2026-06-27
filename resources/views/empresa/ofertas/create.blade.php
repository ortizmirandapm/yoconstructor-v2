@php
    $tiposContrato = ['Tiempo completo', 'Medio tiempo', 'Por proyecto', 'Pasantía'];
    $modalidades   = ['Presencial', 'Remoto', 'Híbrido'];
@endphp

@extends('layouts.empresa')

@section('title', 'Nueva Oferta')
@section('subtitle', 'Completá los campos para publicar una nueva oferta laboral')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: '¡Oferta publicada!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#4f46e5',
                timer: 3000,
                timerProgressBar: true,
            });
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonColor: '#4f46e5',
            });
        });
    </script>
    @endif

    <form action="{{ route('empresa.ofertas.store') }}" method="POST" id="formNuevaOferta">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

            {{-- COLUMNA IZQUIERDA (2/3) --}}
            <div class="xl:col-span-2 space-y-6">

                {{-- INFORMACIÓN PRINCIPAL --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Información principal
                        </h3>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <div>
                            <label for="titulo" class="block text-sm font-semibold text-gray-700 mb-1">Título de la oferta *</label>
                            <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" required
                                class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition text-sm px-4 py-2.5 @error('titulo') border-red-400 @enderror"
                                placeholder="Ej: Maestro Mayor de Obra">
                            @error('titulo')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="descripcion" class="block text-sm font-semibold text-gray-700 mb-1">Descripción *</label>
                            <textarea name="descripcion" id="descripcion" rows="5" required
                                class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition text-sm px-4 py-2.5 @error('descripcion') border-red-400 @enderror"
                                placeholder="Describí las tareas y responsabilidades del puesto">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="requisitos" class="block text-sm font-semibold text-gray-700 mb-1">Requisitos</label>
                            <textarea name="requisitos" id="requisitos" rows="3"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition text-sm px-4 py-2.5"
                                placeholder="Requisitos excluyentes y no excluyentes">{{ old('requisitos') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- ESPECIALIDAD REQUERIDA --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            Especialidad requerida
                        </h3>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <div>
                            <label for="especialidad_principal" class="block text-sm font-semibold text-gray-700 mb-1">Especialidad *</label>
                            <select name="especialidad_principal" id="especialidad_principal" required
                                onchange="document.getElementById('especialidades-hidden').value = this.value"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition text-sm px-4 py-2.5 @error('especialidad_principal') border-red-400 @enderror">
                                <option value="">Seleccioná una especialidad</option>
                                @foreach($especialidades as $esp)
                                    <option value="{{ $esp->id }}" {{ old('especialidad_principal') == $esp->id ? 'selected' : '' }}>
                                        {{ $esp->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="especialidades[]" id="especialidades-hidden" value="{{ old('especialidad_principal') }}">
                            <p class="mt-1 text-xs text-gray-400">Seleccioná la especialidad principal que mejor se ajuste al puesto.</p>
                            @error('especialidad_principal')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="experiencia_requerida" class="block text-sm font-semibold text-gray-700 mb-1">Años de experiencia requeridos</label>
                                <input type="number" name="experiencia_requerida" id="experiencia_requerida" value="{{ old('experiencia_requerida') }}" min="0" max="50"
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition text-sm px-4 py-2.5"
                                    placeholder="0">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CONDICIONES LABORALES --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Condiciones laborales
                        </h3>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="tipo_contrato" class="block text-sm font-semibold text-gray-700 mb-1">Tipo de contrato *</label>
                                <select name="tipo_contrato" id="tipo_contrato" required
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition text-sm px-4 py-2.5 @error('tipo_contrato') border-red-400 @enderror">
                                    <option value="">Seleccioná</option>
                                    @foreach($tiposContrato as $tipo)
                                        <option value="{{ $tipo }}" {{ old('tipo_contrato') == $tipo ? 'selected' : '' }}>
                                            {{ $tipo }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipo_contrato')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="modalidad" class="block text-sm font-semibold text-gray-700 mb-1">Modalidad *</label>
                                <select name="modalidad" id="modalidad" required
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition text-sm px-4 py-2.5 @error('modalidad') border-red-400 @enderror">
                                    <option value="">Seleccioná</option>
                                    @foreach($modalidades as $mod)
                                        <option value="{{ $mod }}" {{ old('modalidad') == $mod ? 'selected' : '' }}>
                                            {{ $mod }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('modalidad')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="salario_min" class="block text-sm font-semibold text-gray-700 mb-1">Salario mínimo</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm font-medium">$</span>
                                    </div>
                                    <input type="number" name="salario_min" id="salario_min" value="{{ old('salario_min') }}" min="0"
                                        class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition text-sm pl-8 pr-4 py-2.5 @error('salario_min') border-red-400 @enderror"
                                        placeholder="0">
                                </div>
                                @error('salario_min')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="salario_max" class="block text-sm font-semibold text-gray-700 mb-1">Salario máximo</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm font-medium">$</span>
                                    </div>
                                    <input type="number" name="salario_max" id="salario_max" value="{{ old('salario_max') }}" min="0"
                                        class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition text-sm pl-8 pr-4 py-2.5 @error('salario_max') border-red-400 @enderror"
                                        placeholder="0">
                                </div>
                                @error('salario_max')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                                <p id="salario-error" class="mt-1 text-xs text-red-500 hidden">El salario máximo debe ser mayor al mínimo.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- COLUMNA DERECHA (1/3) --}}
            <div class="space-y-6">

                {{-- UBICACIÓN --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Ubicación
                        </h3>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <div>
                            <label for="provincia_id" class="block text-sm font-semibold text-gray-700 mb-1">Provincia</label>
                            <select name="provincia_id" id="provincia_id" onchange="cargarLocalidades(this.value)"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition text-sm px-4 py-2.5">
                                <option value="">Seleccioná una provincia</option>
                                @foreach($provincias as $provincia)
                                    <option value="{{ $provincia->id }}" {{ old('provincia_id') == $provincia->id ? 'selected' : '' }}>
                                        {{ $provincia->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="localidad_id" class="block text-sm font-semibold text-gray-700 mb-1">Localidad</label>
                            <select name="localidad_id" id="localidad_id"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition text-sm px-4 py-2.5 @error('localidad_id') border-red-400 @enderror">
                                <option value="">Primero seleccioná una provincia</option>
                            </select>
                            @error('localidad_id')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- FECHAS --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Fecha de vencimiento
                        </h3>
                    </div>
                    <div class="px-6 py-5">
                        <div>
                            <label for="fecha_vencimiento" class="block text-sm font-semibold text-gray-700 mb-1">Vencimiento de la oferta</label>
                            <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" value="{{ old('fecha_vencimiento') }}"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition text-sm px-4 py-2.5 @error('fecha_vencimiento') border-red-400 @enderror">
                            <p class="mt-1 text-xs text-gray-400">Dejá en blanco si no tiene vencimiento.</p>
                            @error('fecha_vencimiento')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- PANEL PUBLICAR --}}
                <div class="bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-6">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-white">¿Listo para publicar?</h4>
                            <p class="text-sm text-white/80 mt-1">Revisá todos los campos antes de enviar.</p>
                        </div>
                        <button type="submit" id="btnPublicar"
                            class="w-full bg-white hover:bg-gray-100 text-indigo-700 font-bold py-3 px-4 rounded-xl transition duration-200 flex items-center justify-center gap-2 text-sm shadow-md">
                            <span id="btn-texto">Publicar Oferta</span>
                            <svg id="btn-spinner" class="hidden animate-spin h-4 w-4 text-indigo-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function cargarLocalidades(provinciaId) {
        const selectLocalidad = document.getElementById('localidad_id');
        selectLocalidad.innerHTML = '<option value="">Cargando...</option>';
        selectLocalidad.disabled = true;

        if (!provinciaId) {
            selectLocalidad.innerHTML = '<option value="">Primero seleccioná una provincia</option>';
            selectLocalidad.disabled = false;
            return;
        }

        fetch('/api/localidades/' + provinciaId)
            .then(function(res) {
                if (!res.ok) throw new Error('Error al cargar localidades');
                return res.json();
            })
            .then(function(data) {
                selectLocalidad.innerHTML = '<option value="">Seleccioná una localidad</option>';
                data.forEach(function(loc) {
                    var opt = document.createElement('option');
                    opt.value = loc.id;
                    opt.textContent = loc.nombre;
                    selectLocalidad.appendChild(opt);
                });
                var oldLocalidad = '{{ old('localidad_id') }}';
                if (oldLocalidad && selectLocalidad.querySelector('option[value="' + oldLocalidad + '"]')) {
                    selectLocalidad.value = oldLocalidad;
                }
                selectLocalidad.disabled = false;
            })
            .catch(function() {
                selectLocalidad.innerHTML = '<option value="">Error al cargar localidades</option>';
                selectLocalidad.disabled = false;
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        var provinciaSelect = document.getElementById('provincia_id');
        if (provinciaSelect.value) {
            cargarLocalidades(provinciaSelect.value);
        }

        var salarioMin = document.getElementById('salario_min');
        var salarioMax = document.getElementById('salario_max');
        var salarioError = document.getElementById('salario-error');

        function validarSalarios() {
            var min = parseFloat(salarioMin.value) || 0;
            var max = parseFloat(salarioMax.value) || 0;
            if (min > 0 && max > 0 && min > max) {
                salarioError.classList.remove('hidden');
                return false;
            }
            salarioError.classList.add('hidden');
            return true;
        }

        salarioMin.addEventListener('input', validarSalarios);
        salarioMax.addEventListener('input', validarSalarios);

        document.getElementById('formNuevaOferta').addEventListener('submit', function(e) {
            if (!validarSalarios()) {
                e.preventDefault();
                salarioMax.focus();
                return;
            }
            var btn = document.getElementById('btnPublicar');
            var texto = document.getElementById('btn-texto');
            var spinner = document.getElementById('btn-spinner');
            btn.disabled = true;
            texto.textContent = 'Publicando...';
            spinner.classList.remove('hidden');
        });
    });
</script>
@endpush
