@php
    $currentRoute = request()->route()->getName();
    $adminUser = auth()->user();
    $userRoutes = ['admin.empresas.*', 'admin.usuarios.*', 'admin.trabajadores.*', 'admin.administradores.*'];
    $contentRoutes = ['admin.ofertas.*', 'admin.rubros.*', 'admin.especialidades.*'];
@endphp
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - YoConstructor Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>

<body class="bg-gray-100">

    {{-- SIDEBAR --}}
    <aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full md:translate-x-0 bg-white shadow-lg">
        <div class="h-full px-3 py-4 overflow-y-auto">

            <div class="mb-8 px-3">
                <h2 class="text-2xl font-bold text-indigo-600">YoConstructor</h2>
                <p class="text-xs text-gray-500 mt-1">
                    Panel de Administracion
                </p>
            </div>

            <ul class="space-y-2 font-medium">

                {{-- Dashboard --}}
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center p-3 text-gray-900 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 group transition-all {{ request()->route()->named('admin.dashboard') ? 'bg-indigo-50 text-indigo-600 border-l-4 border-indigo-600 font-semibold' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 group-hover:text-indigo-600 {{ request()->route()->named('admin.dashboard') ? 'text-indigo-600' : 'text-gray-500' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                        </svg>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </li>

                {{-- Usuarios --}}
                <li>
                    @php
                        $userActive = collect($userRoutes)->contains(fn ($r) => request()->routeIs($r));
                    @endphp
                    <button type="button" onclick="toggleSubmenu('usuarios')"
                        class="flex items-center w-full p-3 text-gray-900 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 group transition-all">
                        <svg class="w-5 h-5 transition duration-75 group-hover:text-indigo-600 {{ $userActive ? 'text-indigo-600' : 'text-gray-500' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                        </svg>
                        <span class="flex-1 ml-3 text-left">Usuarios</span>
                        <svg id="arrow-usuarios" class="w-4 h-4 transition-transform {{ $userActive ? 'rotate-180' : '' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <ul id="submenu-usuarios" class="{{ $userActive ? '' : 'hidden' }} py-2 space-y-2">
                        <li>
                            <a href="{{ route('admin.empresas.index') }}"
                                class="flex items-center w-full p-2 pl-11 text-gray-600 rounded-lg hover:bg-gray-100 hover:text-indigo-600 transition duration-75 {{ request()->route()->named('admin.empresas.*') ? 'bg-gray-100 text-indigo-600 font-medium' : '' }}">
                                Empresas
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center w-full p-2 pl-11 text-gray-600 rounded-lg hover:bg-gray-100 hover:text-indigo-600 transition duration-75">
                                Reclutadores
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.trabajadores.index') }}"
                                class="flex items-center w-full p-2 pl-11 text-gray-600 rounded-lg hover:bg-gray-100 hover:text-indigo-600 transition duration-75 {{ request()->route()->named('admin.trabajadores.*') ? 'bg-gray-100 text-indigo-600 font-medium' : '' }}">
                                Trabajadores
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.administradores.index') }}"
                                class="flex items-center w-full p-2 pl-11 text-gray-600 rounded-lg hover:bg-gray-100 hover:text-indigo-600 transition duration-75 {{ request()->route()->named('admin.administradores.*') ? 'bg-gray-100 text-indigo-600 font-medium' : '' }}">
                                Administradores
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Contenido --}}
                <li>
                    @php
                        $contentActive = collect($contentRoutes)->contains(fn ($r) => request()->routeIs($r));
                    @endphp
                    <button type="button" onclick="toggleSubmenu('contenido')"
                        class="flex items-center w-full p-3 text-gray-900 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 group transition-all">
                        <svg class="w-5 h-5 transition duration-75 group-hover:text-indigo-600 {{ $contentActive ? 'text-indigo-600' : 'text-gray-500' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="flex-1 ml-3 text-left">Contenido</span>
                        <svg id="arrow-contenido" class="w-4 h-4 transition-transform {{ $contentActive ? 'rotate-180' : '' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <ul id="submenu-contenido" class="{{ $contentActive ? '' : 'hidden' }} py-2 space-y-2">
                        <li>
                            <a href="{{ route('admin.ofertas.index') }}"
                                class="flex items-center w-full p-2 pl-11 text-gray-600 rounded-lg hover:bg-gray-100 hover:text-indigo-600 transition duration-75 {{ request()->route()->named('admin.ofertas.*') ? 'bg-gray-100 text-indigo-600 font-medium' : '' }}">
                                Ofertas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.rubros.index') }}"
                                class="flex items-center w-full p-2 pl-11 text-gray-600 rounded-lg hover:bg-gray-100 hover:text-indigo-600 transition duration-75 {{ request()->route()->named('admin.rubros.*') ? 'bg-gray-100 text-indigo-600 font-medium' : '' }}">
                                Rubros
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.especialidades.index') }}"
                                class="flex items-center w-full p-2 pl-11 text-gray-600 rounded-lg hover:bg-gray-100 hover:text-indigo-600 transition duration-75 {{ request()->route()->named('admin.especialidades.*') ? 'bg-gray-100 text-indigo-600 font-medium' : '' }}">
                                Especialidades
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>

            <div class="my-4 border-t border-gray-200"></div>

            <ul class="space-y-2 font-medium">

                <li>
                    <a href="#" onclick="abrirModalSesion(); return false;"
                        class="flex items-center p-3 text-red-600 rounded-lg hover:bg-red-50 group transition-all">
                        <svg class="w-5 h-5 transition duration-75" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-3">Cerrar Sesion</span>
                    </a>
                </li>
            </ul>

        </div>
    </aside>

    {{-- OVERLAY --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden" onclick="toggleSidebar()"></div>

    {{-- MAIN CONTENT --}}
    <div class="md:ml-64">

        {{-- MOBILE NAV --}}
        <nav class="bg-white border-b border-gray-200 px-4 py-3 md:hidden">
            <div class="flex items-center justify-between">
                <button onclick="toggleSidebar()" class="text-gray-500 hover:bg-gray-100 p-2 rounded-lg">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <h1 class="text-xl font-bold text-indigo-600">YoConstructor</h1>
                <div class="w-10"></div>
            </div>
        </nav>

        {{-- CONTENT --}}
        <main>
            @yield('content')
        </main>

    </div>

    {{-- MODAL CERRAR SESION --}}
    <div id="modalCerrarSesion" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm">
            <div class="px-6 py-5 border-b border-gray-200 flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">Cerrar sesion</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Esta accion cerrara tu cuenta</p>
                </div>
            </div>
            <div class="px-6 py-5">
                <p class="text-sm text-gray-600">Estas seguro que queres cerrar sesion?</p>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl flex justify-end gap-3">
                <button type="button" onclick="cerrarModalSesion()"
                    class="px-4 py-2.5 border border-gray-300 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-100 transition">
                    Cancelar
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Si, cerrar sesion
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
            document.getElementById('sidebar-overlay').classList.toggle('hidden');
        }

        function toggleSubmenu(id) {
            const s = document.getElementById('submenu-' + id);
            const a = document.getElementById('arrow-' + id);
            if (s) s.classList.toggle('hidden');
            if (a) a.classList.toggle('rotate-180');
        }

        document.addEventListener('click', function(e) {
            const sb = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const btn = e.target.closest('button');
            if (btn && btn.onclick && btn.onclick.toString().includes('toggleSidebar')) return;
            if (!sb.contains(e.target) && window.innerWidth < 768) {
                sb.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        });

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

    @stack('scripts')
</body>
</html>