<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Mi Perfil</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('trabajador.perfil.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Datos personales --}}
                    <h3 class="text-lg font-medium text-gray-700 mb-4 pb-2 border-b">Datos Personales</h3>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="nombre" value="Nombre *" />
                            <x-text-input id="nombre" name="nombre" type="text"
                                class="mt-1 block w-full"
                                value="{{ old('nombre', $trabajador->nombre) }}" required />
                            <x-input-error :messages="$errors->get('nombre')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="apellido" value="Apellido *" />
                            <x-text-input id="apellido" name="apellido" type="text"
                                class="mt-1 block w-full"
                                value="{{ old('apellido', $trabajador->apellido) }}" required />
                            <x-input-error :messages="$errors->get('apellido')" class="mt-1" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="dni" value="DNI" />
                            <x-text-input id="dni" name="dni" type="text"
                                class="mt-1 block w-full"
                                value="{{ old('dni', $trabajador->dni) }}" />
                            <x-input-error :messages="$errors->get('dni')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="telefono" value="Teléfono" />
                            <x-text-input id="telefono" name="telefono" type="text"
                                class="mt-1 block w-full"
                                value="{{ old('telefono', $trabajador->telefono) }}" />
                        </div>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="descripcion" value="Descripción personal" />
                        <textarea id="descripcion" name="descripcion" rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">{{ old('descripcion', $trabajador->descripcion) }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Contá brevemente tu experiencia y perfil profesional.</p>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="anios_experiencia" value="Años de experiencia" />
                        <x-text-input id="anios_experiencia" name="anios_experiencia" type="number"
                            class="mt-1 block w-40" min="0" max="50"
                            value="{{ old('anios_experiencia', $trabajador->anios_experiencia) }}" />
                    </div>

                    {{-- Ubicación --}}
                    <h3 class="text-lg font-medium text-gray-700 mb-4 pb-2 border-b mt-8">Ubicación Preferida</h3>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="provincia_preferencia_id" value="Provincia" />
                            <select name="provincia_preferencia_id" id="provincia_preferencia_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                onchange="cargarLocalidades(this.value)">
                                <option value="">-- Seleccioná --</option>
                                @foreach($provincias as $provincia)
                                    <option value="{{ $provincia->id }}"
                                        {{ old('provincia_preferencia_id', $trabajador->provincia_preferencia_id) == $provincia->id ? 'selected' : '' }}>
                                        {{ $provincia->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="localidad_preferencia_id" value="Localidad" />
                            <select name="localidad_preferencia_id" id="localidad_preferencia_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">-- Seleccioná --</option>
                                @foreach($localidades as $localidad)
                                    <option value="{{ $localidad->id }}"
                                        {{ old('localidad_preferencia_id', $trabajador->localidad_preferencia_id) == $localidad->id ? 'selected' : '' }}>
                                        {{ $localidad->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Especialidades --}}
                    <h3 class="text-lg font-medium text-gray-700 mb-4 pb-2 border-b mt-8">Mis Especialidades</h3>
                    <p class="text-sm text-gray-500 mb-4">Seleccioná tus especialidades e indicá tu nivel en cada una.</p>

                    @php
                        $misEspecialidades = $trabajador->especialidades->keyBy('id');
                        $principal = old('especialidad_principal', $misEspecialidades->firstWhere('pivot.es_principal', 1)?->id);
                    @endphp

                    <div class="space-y-3 mb-4">
                        @foreach($especialidades as $esp)
                            @php $seleccionada = $misEspecialidades->has($esp->id); @endphp
                            <div class="flex items-center gap-4 p-3 border rounded-md {{ $seleccionada ? 'bg-blue-50 border-blue-200' : 'bg-gray-50' }}">
                                <input type="checkbox" id="esp_{{ $esp->id }}"
                                    class="especialidad-check"
                                    data-id="{{ $esp->id }}"
                                    {{ $seleccionada ? 'checked' : '' }}
                                    onchange="toggleNivel(this)">
                                <label for="esp_{{ $esp->id }}" class="text-sm font-medium w-40 cursor-pointer">
                                    {{ $esp->nombre }}
                                </label>
                                <select name="especialidades[{{ $esp->id }}]"
                                    id="nivel_{{ $esp->id }}"
                                    class="border-gray-300 rounded-md text-sm {{ !$seleccionada ? 'opacity-40' : '' }}"
                                    {{ !$seleccionada ? 'disabled' : '' }}>
                                    @foreach(['Básico','Intermedio','Avanzado','Experto'] as $nivel)
                                        <option value="{{ $nivel }}"
                                            {{ old("especialidades.{$esp->id}", $misEspecialidades[$esp->id]->pivot->nivel_experiencia ?? 'Básico') == $nivel ? 'selected' : '' }}>
                                            {{ $nivel }}
                                        </option>
                                    @endforeach
                                </select>
                                <label class="flex items-center gap-1 text-sm text-gray-600 ml-auto">
                                    <input type="radio" name="especialidad_principal"
                                        value="{{ $esp->id }}"
                                        {{ $principal == $esp->id ? 'checked' : '' }}
                                        {{ !$seleccionada ? 'disabled' : '' }}>
                                    Principal
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-end mt-8">
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                            Guardar Perfil
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
    function toggleNivel(checkbox) {
        const id = checkbox.dataset.id;
        const select = document.getElementById('nivel_' + id);
        const radio = document.querySelector(`input[name="especialidad_principal"][value="${id}"]`);
        const card = checkbox.closest('div.flex');

        if (checkbox.checked) {
            select.disabled = false;
            select.classList.remove('opacity-40');
            if (radio) radio.disabled = false;
            card.classList.add('bg-blue-50', 'border-blue-200');
            card.classList.remove('bg-gray-50');
        } else {
            select.disabled = true;
            select.classList.add('opacity-40');
            if (radio) { radio.disabled = true; radio.checked = false; }
            card.classList.remove('bg-blue-50', 'border-blue-200');
            card.classList.add('bg-gray-50');
        }
    }

    function cargarLocalidades(provinciaId) {
        const select = document.getElementById('localidad_preferencia_id');
        select.innerHTML = '<option value="">Cargando...</option>';

        if (!provinciaId) {
            select.innerHTML = '<option value="">-- Seleccioná --</option>';
            return;
        }

        fetch(`/api/localidades/${provinciaId}`)
            .then(res => res.json())
            .then(data => {
                select.innerHTML = '<option value="">-- Seleccioná --</option>';
                data.forEach(loc => {
                    select.innerHTML += `<option value="${loc.id}">${loc.nombre}</option>`;
                });
            });
    }
    </script>
</x-app-layout>