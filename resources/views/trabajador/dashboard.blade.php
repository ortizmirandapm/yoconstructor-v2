<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Dashboard Trabajador</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                Bienvenido, {{ auth()->user()->name }}
            </div>
              <br>
        <a href="{{ route('trabajador.perfil.edit') }}"
        class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
        Completar mi perfil
    </a>
        </div>
      
    </div>
   
    
</x-app-layout>