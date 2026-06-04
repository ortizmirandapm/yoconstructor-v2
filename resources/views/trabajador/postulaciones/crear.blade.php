<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Postularme</h2>
            <a href="{{ route('ofertas.show', $oferta) }}" class="text-sm text-gray-600 hover:underline">← Volver</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">

                <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-gray-500">Postulándote a</p>
                    <p class="font-semibold text-gray-800">{{ $oferta->titulo }}</p>
                    <p class="text-sm text-gray-600">{{ $oferta->empresa->nombre_empresa }}</p>
                </div>

                <form action="{{ route('trabajador.postulaciones.store', $oferta) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="mensaje" value="Mensaje para la empresa (opcional)" />
                        <textarea id="mensaje" name="mensaje" rows="5"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200"
                            placeholder="Contale a la empresa por qué sos el candidato ideal...">{{ old('mensaje') }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Máximo 1000 caracteres.</p>
                        <x-input-error :messages="$errors->get('mensaje')" class="mt-1" />
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('ofertas.show', $oferta) }}"
                            class="px-4 py-2 text-sm text-gray-600 border rounded-md hover:bg-gray-50">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                            Enviar postulación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>