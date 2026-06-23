<aside class="py-6 lg:col-span-3 bg-gray-50 rounded-l-2xl">
    <nav class="space-y-1 px-2">
        {{-- Mi perfil --}}
        <a href="{{ route('trabajador.perfil.edit') }}"
           class="{{ request()->routeIs('trabajador.perfil.*') ? 'bg-blue-50 border-blue-600 text-blue-700' : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-blue-600' }} group border-l-4 px-3 py-2 flex items-center text-sm font-semibold rounded-r-xl transition-all">
            <svg class="{{ request()->routeIs('trabajador.perfil.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-500' }} flex-shrink-0 -ml-1 mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="truncate">Mi perfil</span>
        </a>

        {{-- Mis postulaciones --}}
        <a href="{{ route('trabajador.postulaciones.index') }}"
           class="{{ request()->routeIs('trabajador.postulaciones.*') ? 'bg-blue-50 border-blue-600 text-blue-700' : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-blue-600' }} group border-l-4 px-3 py-2 flex items-center text-sm font-semibold rounded-r-xl transition-all">
            <svg class="{{ request()->routeIs('trabajador.postulaciones.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-500' }} flex-shrink-0 -ml-1 mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
            </svg>
            <span class="truncate">Mis postulaciones</span>
        </a>

        {{-- Notificaciones --}}
        <a href="{{ route('trabajador.notificaciones.index') }}"
           class="{{ request()->routeIs('trabajador.notificaciones.*') ? 'bg-blue-50 border-blue-600 text-blue-700' : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-blue-600' }} group border-l-4 px-3 py-2 flex items-center text-sm font-semibold rounded-r-xl transition-all">
            <svg class="{{ request()->routeIs('trabajador.notificaciones.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-500' }} flex-shrink-0 -ml-1 mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
            </svg>
            <span class="truncate">Notificaciones</span>
            @auth
                @php $badgeCount = auth()->user()->unreadNotifications->count(); @endphp
                @if($badgeCount > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $badgeCount }}</span>
                @endif
            @endauth
        </a>

        {{-- Configuración --}}
        <a href="{{ route('trabajador.configuracion.edit') }}"
           class="{{ request()->routeIs('trabajador.configuracion.*') ? 'bg-blue-50 border-blue-600 text-blue-700' : 'border-transparent text-gray-700 hover:bg-gray-100 hover:text-blue-600' }} group border-l-4 px-3 py-2 flex items-center text-sm font-semibold rounded-r-xl transition-all">
            <svg class="{{ request()->routeIs('trabajador.configuracion.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-500' }} flex-shrink-0 -ml-1 mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="truncate">Configuración</span>
        </a>
    </nav>
</aside>
