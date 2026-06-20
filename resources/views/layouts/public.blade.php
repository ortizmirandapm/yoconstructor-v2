<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'YoConstructor - Bolsa de Trabajo')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                    },
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
    @stack('scripts')
</head>

<body class="bg-white text-gray-800 font-sans antialiased">

    @include('layouts.public-navbar')

    <main>
        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-200 text-gray-600 py-8 px-3">
        <div class="container mx-auto flex flex-wrap items-center justify-between">
            <div class="w-full md:w-1/2 text-center md:text-left mb-4 md:mb-0">
                <p class="text-sm font-medium">Copyright 2026 &copy; YoConstructor</p>
            </div>
            <div class="w-full md:w-1/2">
                <ul class="flex justify-center md:justify-end gap-6 text-sm font-semibold">
                    <li><a href="#" class="hover:text-blue-600">Contacto</a></li>
                    <li><a href="#" class="hover:text-blue-600">Privacidad</a></li>
                    <li><a href="#" class="hover:text-blue-600">Términos</a></li>
                </ul>
            </div>
        </div>
    </footer>

</body>

</html>
