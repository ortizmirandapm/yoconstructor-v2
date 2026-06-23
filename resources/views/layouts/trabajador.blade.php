<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
</head>

<body class="bg-gray-50 font-sans antialiased">

    @include('layouts.public-navbar')

    <main class="min-h-screen py-8">
        <div class="mx-auto max-w-screen-xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white shadow-sm border border-gray-200">
                <div class="lg:grid lg:grid-cols-12 lg:divide-x lg:divide-gray-100">
                    @include('trabajador.layouts.sidebar')
                    <div class="lg:col-span-9 p-6">
                        @hasSection('header')
                            <div class="mb-6">
                                @yield('header')
                            </div>
                        @endif
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </main>

    @stack('scripts')
</body>

</html>
