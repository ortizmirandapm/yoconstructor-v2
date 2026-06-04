<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Mis Notificaciones</h2>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('trabajador.notificaciones.leerTodas') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-blue-600 hover:underline">
                        Marcar todas como leídas
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-3">
                @forelse($notificaciones as $notif)
                    @php $data = $notif->data; @endphp
                    <div class="bg-white shadow-sm rounded-lg p-4 border-l-4
                        {{ is_null($notif->read_at) ? 'border-blue-500' : 'border-gray-200' }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $data['empresa'] }} publicó una oferta que coincide con tu perfil
                                </p>
                                <p class="text-base font-semibold text-blue-700 mt-1">
                                    {{ $data['titulo'] }}
                                </p>
                                <div class="flex gap-4 mt-2 text-xs text-gray-500">
                                    <span>{{ $data['modalidad'] }}</span>
                                    @if($data['provincia'])
                                        <span>{{ $data['provincia'] }}</span>
                                    @endif
                                    <span>{{ $notif->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                @if(is_null($notif->read_at))
                                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">Nueva</span>
                                    <form action="{{ route('trabajador.notificaciones.leer', $notif->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs text-gray-400 hover:text-gray-600">
                                            Marcar leída
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white shadow-sm rounded-lg p-8 text-center text-gray-400">
                        No tenés notificaciones todavía.
                    </div>
                @endforelse
            </div>

            @if($notificaciones->hasPages())
                <div class="mt-4">
                    {{ $notificaciones->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>