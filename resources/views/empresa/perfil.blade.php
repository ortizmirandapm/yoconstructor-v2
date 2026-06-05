<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Perfil de Empresa</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">

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

                <form action="{{ route('empresa.perfil.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Datos principales --}}
                    <h3 class="text-lg font-medium text-gray-700 mb-4 pb-2 border-b">Datos de la Empresa</h3>

                    <div class="mb-4">
                        <x-input-label for="nombre_empresa" value="Nombre de la empresa *" />
                        <x-text-input id="nombre_empresa" name="nombre_empresa" type="text"
                            class="mt-1 block w-full"
                            value="{{ old('nombre_empresa', $empresa->nombre_empresa) }}" required />
                        <x-input-error :messages="$errors->get('nombre_empresa')" class="mt-1" />
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="razon_social" value="Razón social" />
                            <x-text-input id="razon_social" name="razon_social" type="text"
                                class="mt-1 block w-full"
                                value="{{ old('razon_social', $empresa->razon_social) }}" />
                        </div>
                        <div>
                            <x-input-label for="cuit" value="CUIT" />
                            <x-text-input id="cuit" name="cuit" type="text"
                                class="mt-1 block w-full"
                                placeholder="30-12345678-9"
                                value="{{ old('cuit', $empresa->cuit) }}" />
                            <x-input-error :messages="$errors->get('cuit')" class="mt-1" />
                        </div>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="descripcion" value="Descripción" />
                        <textarea id="descripcion" name="descripcion" rows="4"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">{{ old('descripcion', $empresa->descripcion) }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Contá a qué se dedica tu empresa.</p>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="rubro_id" value="Rubro" />
                        <select name="rubro_id" id="rubro_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">-- Seleccioná --</option>
                            @foreach($rubros as $rubro)
                                <option value="{{ $rubro->id }}"
                                    {{ old('rubro_id', $empresa->rubro_id) == $rubro->id ? 'selected' : '' }}>
                                    {{ $rubro->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Contacto --}}
                    <h3 class="text-lg font-medium text-gray-700 mb-4 pb-2 border-b mt-8">Contacto</h3>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="telefono" value="Teléfono" />
                            <x-text-input id="telefono" name="telefono" type="text"
                                class="mt-1 block w-full"
                                value="{{ old('telefono', $empresa->telefono) }}" />
                        </div>
                        <div>
                            <x-input-label for="email_contacto" value="Email de contacto" />
                            <x-text-input id="email_contacto" name="email_contacto" type="email"
                                class="mt-1 block w-full"
                                value="{{ old('email_contacto', $empresa->email_contacto) }}" />
                        </div>
                    </div>

                    {{-- Ubicación --}}
                    <h3 class="text-lg font-medium text-gray-700 mb-4 pb-2 border-b mt-8">Ubicación</h3>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="domicilio" value="Domicilio" />
                            <x-text-input id="domicilio" name="domicilio" type="text"
                                class="mt-1 block w-full"
                                value="{{ old('domicilio', $empresa->domicilio) }}" />
                        </div>
                        <div>
                            <x-input-label for="provincia_id" value="Provincia" />
                            <select name="provincia_id" id="provincia_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">-- Seleccioná --</option>
                                @foreach($provincias as $provincia)
                                    <option value="{{ $provincia->id }}"
                                        {{ old('provincia_id', $empresa->provincia_id) == $provincia->id ? 'selected' : '' }}>
                                        {{ $provincia->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end mt-8">
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