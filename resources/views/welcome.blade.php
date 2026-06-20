@extends('layouts.public')

@section('content')
<div class="relative flex flex-col items-center max-w-screen-xl px-4 mx-auto md:flex-row sm:px-6 p-8">
    <div class="flex items-center py-5 md:w-1/2 md:pb-20 md:pt-10 md:pr-10">
        <div class="text-left">
            <h2 class="text-4xl font-extrabold leading-tight tracking-tight text-gray-900 sm:text-5xl md:text-6xl">
                Conectamos talento con <span class="text-blue-600">oportunidades.</span>
            </h2>
            <p class="max-w-md mx-auto mt-3 text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                Regístrate hoy para encontrar empleo o publicar tu oferta laboral en el sector de la construcción.
            </p>
        </div>
    </div>
    <div class="flex items-center py-5 md:w-1/2 md:pb-20 md:pt-10 md:pl-10">
        <div class="relative w-full p-3 rounded md:p-8">
            <div class="rounded-2xl overflow-hidden shadow-2xl">
                <img src="img/trabajador-masculino-hablando-telefono-fabrica_107420-96556.avif" alt="Trabajador" class="w-full object-cover" />
            </div>
        </div>
    </div>
</div>

<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">

            <div class="text-center mb-14">
                <span class="inline-block text-xs font-bold tracking-widest text-blue-600 uppercase bg-blue-50 border border-blue-100 px-4 py-1.5 rounded-full mb-4">
                    Rubros disponibles
                </span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight">
                    Especialidades para cada<br class="hidden md:block">
                    <span class="text-blue-600">tipo de obra</span>
                </h2>
                <p class="mt-4 text-gray-500 text-lg max-w-xl mx-auto">
                    Cubrimos todos los rubros del sector. Encontrá ofertas según tu especialidad o publicá lo que necesitás.
                </p>
            </div>

            @php
            $iconos_rubros = [
                'building'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-3h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12a2.25 2.25 0 0 1 2.25 2.25V21h3.75V3"/>',
                'bolt'        => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z"/>',
                'wrench'      => '<path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75a4.5 4.5 0 0 1-4.884 4.484c-1.076-.091-2.264.071-2.95.904l-7.152 8.684a2.548 2.548 0 1 1-3.586-3.586l8.684-7.152c.833-.686.995-1.874.904-2.95a4.5 4.5 0 0 1 6.336-4.486l-3.276 3.276a3.004 3.004 0 0 0 2.25 2.25l3.276-3.276c.256.565.398 1.192.398 1.852Z"/>',
                'hammer'      => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z"/>',
                'paint-brush' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 0 0-5.78 1.128 2.25 2.25 0 0 1-2.4 2.245 4.5 4.5 0 0 0 8.4-2.245c0-.399-.078-.78-.22-1.128Zm0 0a15.998 15.998 0 0 0 3.388-1.62m-5.043-.025a15.994 15.994 0 0 1 1.622-3.395m3.42 3.42a15.995 15.995 0 0 0 4.764-4.648l3.876-5.814a1.151 1.151 0 0 0-1.597-1.597L14.146 6.32a15.996 15.996 0 0 0-4.649 4.763m3.42 3.42a6.776 6.776 0 0 0-3.42-3.42"/>',
                'shield'      => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>',
                'leaf'        => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.406 14.406 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 1 0-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/>',
                'snowflake'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m0-18L8.25 6.75M12 3l3.75 3.75M12 21l-3.75-3.75M12 21l3.75-3.75M3 12h18M3 12l3.75-3.75M3 12l3.75 3.75M21 12l-3.75-3.75M21 12l-3.75 3.75"/>',
                'house'       => '<path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>',
                'truck'       => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>',
                'compass'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z"/>',
                'clipboard'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/>',
                'default'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-3h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12a2.25 2.25 0 0 1 2.25 2.25V21h3.75V3"/>',
            ];

            function get_icono($icono_bd, $iconos) {
                return $iconos[$icono_bd] ?? $iconos['default'];
            }
            @endphp

            @if ($rubros->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($rubros as $rubro)
                @php
                    $visibles  = array_slice($rubro->especialidades_list, 0, 3);
                    $restantes = max(0, count($rubro->especialidades_list) - 3);
                    $icono     = get_icono($rubro->icono, $iconos_rubros);
                @endphp
                <a href="{{ route('ofertas.index', ['rubro' => $rubro->id]) }}"
                   class="group bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 flex flex-col gap-4">

                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            {!! $icono !!}
                        </svg>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900 text-base leading-tight group-hover:text-blue-600 transition-colors">
                            {{ $rubro->nombre }}
                        </h3>
                        <span class="inline-block mt-2 text-xs text-gray-400">{{ $rubro->descripcion }}</span>
                    </div>

                    @if (!empty($visibles))
                    <div class="flex flex-wrap gap-2 mt-auto">
                        @foreach ($visibles as $esp)
                            <span class="text-xs font-medium bg-blue-50 text-blue-700 px-3 py-1 rounded-full">
                                {{ trim($esp) }}
                            </span>
                        @endforeach
                        @if ($restantes > 0)
                            <span class="text-xs font-medium bg-gray-100 text-gray-500 px-3 py-1 rounded-full">
                                +{{ $restantes }} más
                            </span>
                        @endif
                    </div>
                    @endif

                </a>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('ofertas.index') }}"
                   class="inline-flex items-center gap-2 px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg">
                    Ver todas las ofertas
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
            @else
            <div class="text-center py-16 text-gray-400 border border-dashed border-gray-200 rounded-2xl">
                <p>No hay rubros registrados aún.</p>
            </div>
            @endif

        </div>
    </div>
</section>

<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-7xl mx-auto">

            <div class="text-center mb-14">
                <span class="inline-block text-xs font-bold tracking-widest text-blue-600 uppercase bg-blue-50 border border-blue-100 px-4 py-1.5 rounded-full mb-4">
                    Cobertura sectorial
                </span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight">
                    Todo el sector construcción,<br class="hidden md:block">
                    <span class="text-blue-600">en un solo lugar</span>
                </h2>
                <p class="mt-4 text-gray-500 text-lg max-w-xl mx-auto">
                    Cubrimos todas las especialidades y rubros del sector. Encontrá la oferta que se adapte a tu perfil o publicá la tuya.
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-14">
                @php
                $stats_cob = [
                    ['value' => $statRubros, 'label' => 'Rubros', 'path' => 'M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-3h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12a2.25 2.25 0 0 1 2.25 2.25V21h3.75V3'],
                    ['value' => $statEsps, 'label' => 'Especializaciones', 'path' => 'M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z'],
                    ['value' => $statOfertas, 'label' => 'Ofertas activas', 'path' => 'M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z'],
                    ['value' => $statEmpresas, 'label' => 'Empresas registradas', 'path' => 'M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21'],
                ];
                @endphp
                @foreach ($stats_cob as $s)
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5 text-center hover:border-blue-200 hover:bg-blue-50/40 transition-all duration-300">
                    <div class="flex justify-center mb-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $s['path'] }}" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-extrabold text-gray-900">{{ $s['value'] }}+</p>
                    <p class="text-sm text-gray-500 mt-1 font-medium">{{ $s['label'] }}</p>
                </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <a href="{{ route('ofertas.index') }}"
                    class="group relative bg-white border border-gray-200 rounded-2xl p-6 hover:border-blue-300 hover:shadow-lg transition-all duration-300 flex items-center gap-5 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                    <div class="relative flex-shrink-0 w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607z" />
                        </svg>
                    </div>
                    <div class="relative min-w-0">
                        <p class="text-xs font-bold text-blue-600 uppercase tracking-wide mb-1">Soy trabajador</p>
                        <h3 class="font-bold text-gray-900 text-base leading-tight">Explorá las ofertas laborales</h3>
                        <p class="text-sm text-gray-500 mt-1">Encontrá trabajo en construcción según tu especialidad y zona.</p>
                    </div>
                    <svg class="relative flex-shrink-0 w-5 h-5 text-gray-300 group-hover:text-blue-500 transition-colors ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <a href="{{ route('register') }}"
                    class="group relative bg-white border border-gray-200 rounded-2xl p-6 hover:border-blue-300 hover:shadow-lg transition-all duration-300 flex items-center gap-5 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                    <div class="relative flex-shrink-0 w-14 h-14 bg-gray-900 rounded-xl flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                        </svg>
                    </div>
                    <div class="relative min-w-0">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Soy empresa</p>
                        <h3 class="font-bold text-gray-900 text-base leading-tight">Registrá tu empresa gratis</h3>
                        <p class="text-sm text-gray-500 mt-1">Publicá ofertas y encontrá el perfil ideal para tu obra.</p>
                    </div>
                    <svg class="relative flex-shrink-0 w-5 h-5 text-gray-300 group-hover:text-blue-500 transition-colors ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

        </div>
    </div>
</section>

<div class="bg-gray-50 p-4">
    <div class="container mx-auto pt-12 pb-20">
        <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 text-center mb-12">
            Cree su oferta laboral en <span class="text-blue-600">3 simples pasos.</span>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-md transition-shadow">
                <div class="text-blue-600 font-black text-4xl mb-4">01</div>
                <h3 class="font-bold text-gray-900 text-xl mb-4">Cree una cuenta gratis</h3>
                <p class="text-gray-500 leading-relaxed">Solo necesita su dirección de email para registar su empresa y comenzar a darle forma a su busqueda.</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-md transition-shadow">
                <div class="text-blue-600 font-black text-4xl mb-4">02</div>
                <h3 class="font-bold text-gray-900 text-xl mb-4">Cree su publicación</h3>
                <p class="text-gray-500 leading-relaxed">Luego agregue el título, la descripción, requisitos, provincia y localidad de su publicación de empleo, ¡y listo!</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-md transition-shadow">
                <div class="text-blue-600 font-black text-4xl mb-4">03</div>
                <h3 class="font-bold text-gray-900 text-xl mb-4">Publique su empleo</h3>
                <p class="text-gray-500 leading-relaxed">Luego de publicar el empleo, use nuestras herramientas para encontrar y filtrar a los mejores talentos.</p>
            </div>
        </div>
    </div>
</div>

<section class="py-20 bg-white relative overflow-hidden" id="empresas">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-50 rounded-full opacity-60 blur-3xl"></div>
        <div class="absolute bottom-0 -left-20 w-72 h-72 bg-blue-100 rounded-full opacity-50 blur-3xl"></div>
    </div>

    <div class="container mx-auto px-4 relative">
        <div class="text-center mb-14">
            <span class="inline-block text-xs font-bold tracking-widest text-blue-600 uppercase bg-blue-50 border border-blue-100 px-4 py-1.5 rounded-full mb-4">
                Empresas activas
            </span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight">
                Empresas que buscan talento<br class="hidden md:block">
                <span class="text-blue-600">en nuestra plataforma</span>
            </h2>
            <p class="mt-4 text-gray-500 text-lg max-w-xl mx-auto">
                Conéctate con las mejores empresas del sector construcción de Argentina.
            </p>
        </div>

        @if ($empresasDestacadas->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($empresasDestacadas as $emp)
            @php
                $inicial = strtoupper(substr($emp->nombre_empresa, 0, 1));
            @endphp
            <a href="{{ url('/empresas/' . $emp->id) }}"
                class="group relative bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col gap-4 overflow-hidden">

                <div class="absolute top-0 left-0 right-0 h-1 bg-[#2563eb] scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left rounded-t-2xl"></div>

                <div class="flex items-center gap-4">
                    @if (!empty($emp->logo))
                    <img src="{{ asset($emp->logo) }}"
                        alt="{{ $emp->nombre_empresa }}"
                        class="w-14 h-14 rounded-xl object-contain border border-gray-100 bg-white p-1 flex-shrink-0 shadow-sm">
                    @else
                    <div class="w-14 h-14 rounded-xl flex items-center justify-center text-xl font-bold flex-shrink-0 bg-blue-600 text-white">
                        {{ $inicial }}
                    </div>
                    @endif

                    <div class="min-w-0">
                        <h3 class="font-bold text-gray-900 text-base leading-tight truncate group-hover:text-blue-600 transition-colors">
                            {{ $emp->nombre_empresa }}
                        </h3>
                        @if ($emp->rubro?->nombre)
                        <span class="inline-block mt-1 text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                            {{ $emp->rubro->nombre }}
                        </span>
                        @endif
                    </div>
                </div>

                <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed">
                    @if (!empty($emp->descripcion))
                        {{ $emp->descripcion }}
                    @else
                        <span class="italic text-gray-400">Sin descripción disponible.</span>
                    @endif
                </p>

                <div class="flex items-center justify-between mt-auto pt-3 border-t border-gray-100">
                    @if ($emp->ofertas_activas > 0)
                    <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                        {{ $emp->ofertas_activas }} oferta{{ $emp->ofertas_activas > 1 ? 's' : '' }}
                    </span>
                    @else
                    <span class="text-xs text-gray-400">Sin ofertas activas</span>
                    @endif

                    <span class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 group-hover:bg-[#2563eb] group-hover:text-white text-gray-400 transition-all duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                </div>
            </a>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('ofertas.index') }}"
                class="inline-flex items-center gap-2 px-8 py-3.5 bg-[#2563eb] hover:bg-blue-700 text-white font-semibold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg">
                Ver todas las ofertas
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>
        @else
        <div class="text-center py-16 text-gray-400 border border-dashed border-gray-200 rounded-2xl">
            <p>No hay empresas registradas aún.</p>
        </div>
        @endif
    </div>
</section>

<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-5">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-gray-900">¿Por qué elegirnos?</h2>
            <p class="text-gray-500 mt-3 max-w-xl mx-auto">
                YoConstructor es la plataforma pensada específicamente para el sector de la construcción en Argentina.
            </p>
        </div>
        <div class="flex flex-wrap text-center justify-center">
            @php
            $razones = [
                [
                    'Tecnología moderna',
                    'Plataforma rápida e intuitiva, accesible desde cualquier dispositivo.',
                    '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" class="w-12 h-12">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                </svg>'
                ],
                [
                    'Tarifas accesibles',
                    'Registrarse es gratis. Tanto empresas como trabajadores pueden usarla sin barreras.',
                    '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" class="w-12 h-12">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75m0 1.5v.75m0 1.5v.75m1.5-1.5h.75m-1.5 1.5h.75m1.5-1.5h.75m1.5 1.5h.75m1.5-1.5h.75m1.5 1.5h.75m1.5-1.5h.75M3.75 1.5h16.5a2.25 2.25 0 0 1 2.25 2.25v16.5a2.25 2.25 0 0 1-2.25 2.25H3.75a2.25 2.25 0 0 1-2.25-2.25V3.75A2.25 2.25 0 0 1 3.75 1.5Zm12.75 12a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>'
                ],
                [
                    'Rapidez y eficiencia',
                    'Conectamos empresas con el talento adecuado de forma directa y sin vueltas.',
                    '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" class="w-12 h-12">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                </svg>'
                ],
                [
                    'Experiencia sectorial',
                    'Diseñada para la construcción: especialidades adaptadas a la realidad local.',
                    '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" class="w-12 h-12">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-3h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12a2.25 2.25 0 0 1 2.25 2.25V21h3.75V3" />
                </svg>'
                ]
            ];
            @endphp
            @foreach ($razones as $razon)
            <div class="p-4 md:w-1/4 sm:w-1/2">
                <div class="px-4 py-8 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 group">
                    <div class="flex justify-center mb-4 group-hover:scale-110 transition-transform">
                        {!! $razon[2] !!}
                    </div>
                    <h3 class="font-bold text-xl text-gray-900 mb-2">{{ $razon[0] }}</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ $razon[1] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
