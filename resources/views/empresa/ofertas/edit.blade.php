<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Editar Oferta</h2>
            <a href="{{ route('empresa.ofertas.index') }}"
               class="text-sm text-gray-600 hover:underline">← Volver</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('empresa.ofertas.update', $oferta) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Título --}}
                    <div class="mb-4">
                        <x-input-label for="titulo" value="Título de la oferta *" />
                        <x-text-input id="titulo" name="titulo" type="text"
                            class="mt-1 block w-full" value="{{ old('titulo', $oferta->titulo) }}" required />
                        <x-input-error :messages="$errors->get('titulo')" class="mt-1" />
                    </div>

                    {{-- Descripción --}}
                    <div class="mb-4">
                        <x-input-label for="descripcion" value="Descripción *" />
                        <textarea id="descripcion" name="descripcion" rows="4"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200"
                            required>{{ old('descripcion', $oferta->descripcion) }}</textarea>
                        <x-input-error :messages="$errors->get('descripcion')" class="mt-1" />
                    </div>

                    {{-- Requisitos --}}
                    <div class="mb-4">
                        <x-input-label for="requisitos" value="Requisitos" />
                        <textarea id="requisitos" name="requisitos" rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">{{ old('requisitos', $oferta->requisitos) }}</textarea>
                    </div>

                    {{-- Especialidades --}}
                    <div class="mb-4">
                        <x-input-label value="Especialidades requeridas *" />
                        <p class="text-xs text-gray-500 mb-2">Seleccioná una o más.</p>
                        @php
                            $especialidadesSeleccionadas = old('especialidades', $oferta->especialidades->pluck('id')->toArray());
                            $principal = old('especialidad_principal', $oferta->especialidades->where('pivot.es_principal', 1)->first()?->id);
                        @endphp
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            @foreach($especialidades as $esp)
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="especialidades[]"
                                        value="{{ $esp->id }}"
                                        {{ in_array($esp->id, $especialidadesSeleccionadas) ? 'checked' : '' }}>
                                    {{ $esp->nombre }}
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('especialidades')" class="mt-1" />
                    </div>

                    {{-- Especialidad principal --}}
                    <div class="mb-4">
                        <x-input-label for="especialidad_principal" value="Especialidad principal" />
                        <select name="especialidad_principal" id="especialidad_principal"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">-- Seleccioná --</option>
                            @foreach($especialidades as $esp)
                                <option value="{{ $esp->id }}"
                                    {{ $principal == $esp->id ? 'selected' : '' }}>
                                    {{ $esp->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Rubro --}}
                    <div class="mb-4">
                        <x-input-label for="rubro_id" value="Rubro" />
                        <select name="rubro_id" id="rubro_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">-- Seleccioná --</option>
                            @foreach($rubros as $rubro)
                                <option value="{{ $rubro->id }}"
                                    {{ old('rubro_id', $oferta->rubro_id) == $rubro->id ? 'selected' : '' }}>
                                    {{ $rubro->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tipo contrato y Modalidad --}}
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="tipo_contrato" value="Tipo de contrato *" />
                            <select name="tipo_contrato" id="tipo_contrato"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @foreach(['Tiempo completo','Medio tiempo','Por proyecto','Pasantía'] as $tipo)
                                    <option value="{{ $tipo }}"
                                        {{ old('tipo_contrato', $oferta->tipo_contrato) == $tipo ? 'selected' : '' }}>
                                        {{ $tipo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="modalidad" value="Modalidad *" />
                            <select name="modalidad" id="modalidad"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @foreach(['Presencial','Remoto','Híbrido'] as $mod)
                                    <option value="{{ $mod }}"
                                        {{ old('modalidad', $oferta->modalidad) == $mod ? 'selected' : '' }}>
                                        {{ $mod }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Salario --}}
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="salario_min" value="Salario mínimo" />
                            <x-text-input id="salario_min" name="salario_min" type="number"
                                class="mt-1 block w-full" value="{{ old('salario_min', $oferta->salario_min) }}" />
                        </div>
                        <div>
                            <x-input-label for="salario_max" value="Salario máximo" />
                            <x-text-input id="salario_max" name="salario_max" type="number"
                                class="mt-1 block w-full" value="{{ old('salario_max', $oferta->salario_max) }}" />
                        </div>
                    </div>

                    {{-- Provincia --}}
                    <div class="mb-4">
                        <x-input-label for="provincia_id" value="Provincia" />
                        <select name="provincia_id" id="provincia_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">-- Seleccioná --</option>
                            @foreach($provincias as $provincia)
                                <option value="{{ $provincia->id }}"
                                    {{ old('provincia_id', $oferta->provincia_id) == $provincia->id ? 'selected' : '' }}>
                                    {{ $provincia->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Experiencia y fecha vencimiento --}}
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="experiencia_requerida" value="Años de experiencia" />
                            <x-text-input id="experiencia_requerida" name="experiencia_requerida" type="number"
                                class="mt-1 block w-full"
                                value="{{ old('experiencia_requerida', $oferta->experiencia_requerida) }}" min="0" />
                        </div>
                        <div>
                            <x-input-label for="fecha_vencimiento" value="Fecha de vencimiento" />
                            <x-text-input id="fecha_vencimiento" name="fecha_vencimiento" type="date"
                                class="mt-1 block w-full"
                                value="{{ old('fecha_vencimiento', $oferta->fecha_vencimiento?->format('Y-m-d')) }}" />
                        </div>
                    </div>

                    {{-- Estado --}}
                    <div class="mb-6">
                        <x-input-label for="estado" value="Estado" />
                        <select name="estado" id="estado"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach(['Activa','Borrador','Pausada','Cerrada'] as $est)
                                <option value="{{ $est }}"
                                    {{ old('estado', $oferta->estado) == $est ? 'selected' : '' }}>
                                    {{ $est }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('empresa.ofertas.index') }}"
                           class="px-4 py-2 text-sm text-gray-600 border rounded-md hover:bg-gray-50">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                            Guardar cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>