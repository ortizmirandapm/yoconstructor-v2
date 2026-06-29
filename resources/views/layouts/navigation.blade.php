<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        Inicio
                    </x-nav-link>

                    @if ($navbarEsTrabajador)
                        <x-nav-link :href="route('ofertas.index')" :active="request()->routeIs('ofertas.*')">
                            Ofertas laborales
                        </x-nav-link>
                        <x-nav-link :href="route('trabajador.postulaciones.index')" :active="request()->routeIs('trabajador.postulaciones.*')">
                            Mis postulaciones
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="relative" x-data="{ dropdownOpen: false }">
                    <button @click="dropdownOpen = !dropdownOpen" class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 transition px-3 py-2 rounded-lg hover:bg-gray-50">
                        <img class="w-8 h-8 rounded-full object-cover border-2 border-gray-200 mr-2" src="{{ $navbarFotoPerfil }}" alt="foto">
                        <span class="hidden lg:block">{{ $navbarNombreCompleto }}</span>
                        <svg class="w-4 h-4 ml-1 transition-transform duration-200" :class="{ 'rotate-180': dropdownOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-cloak
                        class="absolute right-0 top-full mt-2 bg-white border border-gray-200 rounded-lg shadow-xl w-72 z-50">

                        <div class="p-3">
                            <div class="flex items-center px-3 py-2.5 space-x-3 text-sm bg-gray-50 rounded-lg border border-gray-100">
                                <img class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2 border-gray-200" src="{{ $navbarFotoPerfil }}" alt="foto">
                                <div class="flex-1 min-w-0">
                                    <div class="truncate text-gray-900 font-semibold">{{ $navbarNombreCompleto }}</div>
                                    <div class="truncate text-gray-500 text-xs">{{ $navbarTipoNombre }}</div>
                                    <div class="truncate text-gray-500 text-xs">{{ $navbarEmail }}</div>
                                </div>
                            </div>
                        </div>

                        <ul class="px-2 pb-2 text-sm text-gray-700 font-medium">
                            @if ($navbarEsTrabajador)

                                <li><a href="{{ route('trabajador.perfil.edit') }}" class="flex items-center w-full px-3 py-2.5 hover:bg-gray-100 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 mr-3 flex-shrink-0 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17v1a1 1 0 001 1h8a1 1 0 001-1v-1a3 3 0 00-3-3h-4a3 3 0 00-3 3zm8-9a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Mi perfil
                                </a></li>
                                <li><a href="{{ route('trabajador.postulaciones.index') }}" class="flex items-center w-full px-3 py-2.5 hover:bg-gray-100 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 mr-3 flex-shrink-0 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9h3m-3 3h3m-3 3h3m-6 1c-.306-.613-.933-1-1.618-1H7.618c-.685 0-1.312.387-1.618 1M4 5h16a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V6a1 1 0 011-1zm7 5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Mis postulaciones
                                </a></li>
                                <li><a href="{{ route('trabajador.notificaciones.index') }}" class="flex items-center w-full px-3 py-2.5 hover:bg-gray-100 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 mr-3 flex-shrink-0 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5.365V3m0 2.365a5.338 5.338 0 015.133 5.368v1.8c0 2.386 1.867 2.982 1.867 4.175 0 .593 0 1.292-.538 1.292H5.538C5 18 5 17.301 5 16.708c0-1.193 1.867-1.789 1.867-4.175v-1.8A5.338 5.338 0 0112 5.365zM8.733 18c.094.852.306 1.54.944 2.112a3.48 3.48 0 004.646 0c.638-.572 1.236-1.26 1.33-2.112h-6.92z"/>
                                    </svg>
                                    <span class="flex-1">Notificaciones</span>
                                    @if ($navbarNotisCount > 0)
                                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $navbarNotisCount }}</span>
                                    @endif
                                </a></li>
                            @endif
                        </ul>

                        <div class="border-t border-gray-200 px-2 py-2">
                            <button onclick="abrirModalSesion()" class="flex items-center w-full px-3 py-2.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors cursor-pointer">
                                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H8m12 0l-4 4m4-4-4-4M9 4H7a3 3 0 00-3 3v10a3 3 0 003 3h2"/>
                                </svg>
                                Cerrar sesión
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                Inicio
            </x-responsive-nav-link>
            @if ($navbarEsTrabajador)
                <x-responsive-nav-link :href="route('ofertas.index')" :active="request()->routeIs('ofertas.*')">
                    Ofertas laborales
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('trabajador.postulaciones.index')" :active="request()->routeIs('trabajador.postulaciones.*')">
                    Mis postulaciones
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="flex items-center space-x-3">
                    <img class="w-10 h-10 rounded-full object-cover border-2 border-gray-200" src="{{ $navbarFotoPerfil }}" alt="foto">
                    <div>
                        <div class="font-medium text-base text-gray-800">{{ $navbarNombreCompleto }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ $navbarEmail }}</div>
                    </div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                @if ($navbarEsTrabajador)
                    <x-responsive-nav-link :href="route('trabajador.perfil.edit')">
                        Mi perfil
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('trabajador.notificaciones.index')">
                        Notificaciones
                        @if ($navbarNotisCount > 0)
                            <span class="ml-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $navbarNotisCount }}</span>
                        @endif
                    </x-responsive-nav-link>
                @endif

                <button onclick="abrirModalSesion()"
                    class="flex items-center w-full px-4 py-2.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors cursor-pointer">
                    Cerrar sesión
                </button>
            </div>
        </div>
    </div>

    <x-logout-modal />

    <script>
    function abrirModalSesion() {
        document.getElementById('modalCerrarSesion').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function cerrarModalSesion() {
        document.getElementById('modalCerrarSesion').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    document.getElementById('modalCerrarSesion').addEventListener('click', function(e) {
        if (e.target === this) cerrarModalSesion();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') cerrarModalSesion();
    });
    </script>
</nav>