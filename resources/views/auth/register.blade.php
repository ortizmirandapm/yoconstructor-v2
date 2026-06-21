<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registro - YoConstructor</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-start md:items-center justify-center p-4 py-8">

    @if ($errors->any())
        <div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
                <div class="border-b px-6 py-4 bg-red-50 rounded-t-xl">
                    <h5 class="text-lg font-semibold text-red-700 flex items-center gap-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        Error
                    </h5>
                </div>
                <div class="px-6 py-5">
                    <ul class="text-gray-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="border-t px-6 py-4 flex justify-end bg-gray-50 rounded-b-xl">
                    <button onclick="document.getElementById('errorModal').remove()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-5 py-2 rounded-lg transition-colors">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    @endif

    <div class="w-full max-w-3xl mx-auto">

        {{-- Selección de tipo --}}
        <div id="seleccion" class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
            <div class="flex justify-center mb-7">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 border border-gray-300 rounded-lg hover:border-gray-400 text-blue-600 hover:bg-gray-50 py-2 px-4 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                    Volver al inicio
                </a>
            </div>
            <h1 class="text-2xl md:text-4xl font-bold text-center text-gray-800 mb-3">Crea tu cuenta</h1>
            <p class="text-center text-gray-600 mb-10">Selecciona el tipo de cuenta que deseas crear.</p>
            <div class="grid md:grid-cols-2 gap-6">
                <button type="button" onclick="mostrarFormulario('empresa')"
                    class="group bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl p-6 md:p-8 transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h2 class="text-2xl font-bold mb-2">Empresa</h2>
                    <p class="text-blue-100">Busca profesionales cualificados</p>
                </button>
                <button type="button" onclick="mostrarFormulario('trabajador')"
                    class="group bg-gradient-to-br from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white rounded-xl p-6 md:p-8 transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                    <svg class="w-10 h-10 md:w-16 md:h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <h2 class="text-2xl font-bold mb-2">Trabajador</h2>
                    <p class="text-indigo-100">Encuentra oportunidades laborales</p>
                </button>
            </div>
        </div>

        {{-- Formulario Empresa --}}
        <div id="formEmpresa" class="bg-white rounded-2xl shadow-xl p-5 md:p-10 hidden overflow-y-auto">
            <button type="button" onclick="volverSeleccion()" class="text-gray-600 hover:text-gray-800 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Volver
            </button>
            <div class="flex items-center gap-3 mb-8">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl md:text-3xl font-bold text-gray-800">Registro Empresa</h2>
                    <p class="text-gray-500 text-sm">Completá los datos de tu empresa</p>
                </div>
            </div>

            <form method="POST" action="{{ route('register') }}" onsubmit="return validarFormEmpresa()">
                @csrf
                <input type="hidden" name="tipo" value="empresa">

                {{-- Datos de la empresa --}}
                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-4 pb-1 border-b border-blue-100">Datos de la empresa</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nombre de la Empresa <span class="text-red-500">*</span></label>
                        <input type="text" name="nombre_empresa" required value="{{ old('nombre_empresa') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm @error('nombre_empresa') border-red-500 @enderror"
                            placeholder="Ej: Construcciones García">
                        @error('nombre_empresa') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Razón Social <span class="text-gray-400 font-normal text-xs">(Opcional)</span></label>
                        <input type="text" name="razon_social" value="{{ old('razon_social') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm"
                            placeholder="Ej: Construcciones García S.R.L.">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email de acceso <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required value="{{ old('email') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm @error('email') border-red-500 @enderror"
                            placeholder="Para iniciar sesión">
                        @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">CUIT <span class="text-red-500">*</span></label>
                        <input type="text" id="cuit" name="cuit" required value="{{ old('cuit') }}"
                            maxlength="13"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm @error('cuit') border-red-500 @enderror"
                            placeholder="30-12345678-9">
                        @error('cuit') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Provincia <span class="text-red-500">*</span></label>
                        <select name="provincia_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm @error('provincia_id') border-red-500 @enderror">
                            <option value="">Seleccioná una provincia</option>
                            @foreach ($provincias as $prov)
                                <option value="{{ $prov->id }}" {{ old('provincia_id') == $prov->id ? 'selected' : '' }}>{{ $prov->nombre }}</option>
                            @endforeach
                        </select>
                        @error('provincia_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Rubro Principal <span class="text-red-500">*</span></label>
                        <select name="rubro_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm @error('rubro_id') border-red-500 @enderror">
                            <option value="">Seleccioná un rubro</option>
                            @foreach ($rubros as $rubro)
                                <option value="{{ $rubro->id }}" {{ old('rubro_id') == $rubro->id ? 'selected' : '' }}>{{ $rubro->nombre }}</option>
                            @endforeach
                        </select>
                        @error('rubro_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Contacto --}}
                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-4 pb-1 border-b border-blue-100">Contacto</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Teléfono <span class="text-gray-400 font-normal text-xs">(Opcional)</span></label>
                        <input type="tel" name="telefono" value="{{ old('telefono') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm"
                            placeholder="Ej: 011-4567-8901">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email de contacto <span class="text-gray-400 font-normal text-xs">(Opcional)</span></label>
                        <input type="email" name="email_contacto" value="{{ old('email_contacto') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm"
                            placeholder="Visible para trabajadores">
                    </div>
                </div>

                {{-- Contraseña empresa --}}
                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-4 pb-1 border-b border-blue-100">Contraseña</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Contraseña <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" name="password" id="password-empresa" required
                                oninput="validarPass('password-empresa','password_confirmation-empresa','req-empresa','match-empresa')"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all pr-12 text-sm @error('password') border-red-500 @enderror"
                                placeholder="Mín. 6 caracteres y una letra">
                            <button type="button" onclick="toggleVer('password-empresa')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                        @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        <div id="req-empresa" class="mt-2 space-y-1 hidden">
                            <div id="req-empresa-len" class="flex items-center gap-2 text-xs text-gray-400">
                                <span class="req-icon w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center flex-shrink-0"></span>
                                Mínimo 6 caracteres
                            </div>
                            <div id="req-empresa-letra" class="flex items-center gap-2 text-xs text-gray-400">
                                <span class="req-icon w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center flex-shrink-0"></span>
                                Al menos una letra (a-z)
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Repetir contraseña <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation-empresa" required
                                oninput="validarPass('password-empresa','password_confirmation-empresa','req-empresa','match-empresa')"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all pr-12 text-sm"
                                placeholder="Repetir contraseña">
                            <button type="button" onclick="toggleVer('password_confirmation-empresa')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                        <p id="match-empresa" class="text-xs mt-1 hidden"></p>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3.5 rounded-lg transition-all duration-300 transform hover:scale-[1.01] shadow-lg hover:shadow-xl text-sm">
                    Registrar Empresa
                </button>

                <p class="text-center mt-4 text-sm text-gray-600">
                    ¿Ya tenés cuenta?
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">Iniciá sesión</a>
                </p>
            </form>
        </div>

        {{-- Formulario Trabajador --}}
        <div id="formTrabajador" class="bg-white rounded-2xl shadow-xl p-5 md:p-10 hidden overflow-y-auto">
            <button type="button" onclick="volverSeleccion()" class="text-gray-600 hover:text-gray-800 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Volver
            </button>
            <div class="flex items-center gap-3 mb-8">
                <div class="bg-indigo-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl md:text-3xl font-bold text-gray-800">Registro Trabajador</h2>
                    <p class="text-gray-500 text-sm">Completá tu perfil profesional</p>
                </div>
            </div>

            <form method="POST" action="{{ route('register') }}" onsubmit="return validarFormTrabajador()">
                @csrf
                <input type="hidden" name="tipo" value="trabajador">

                {{-- Datos personales --}}
                <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-4 pb-1 border-b border-indigo-100">Datos personales</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nombre <span class="text-red-500">*</span></label>
                        <input type="text" name="nombre" required value="{{ old('nombre') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-sm @error('nombre') border-red-500 @enderror"
                            placeholder="Juan">
                        @error('nombre') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Apellido <span class="text-red-500">*</span></label>
                        <input type="text" name="apellido" required value="{{ old('apellido') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-sm @error('apellido') border-red-500 @enderror"
                            placeholder="Pérez García">
                        @error('apellido') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">DNI <span class="text-red-500">*</span></label>
                        <input type="text" id="dni" name="dni" required value="{{ old('dni') }}"
                            maxlength="8"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-sm @error('dni') border-red-500 @enderror"
                            placeholder="12345678">
                        @error('dni') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Especialidad principal <span class="text-red-500">*</span></label>
                        <select name="especialidad_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-sm @error('especialidad_id') border-red-500 @enderror">
                            <option value="">Seleccioná una especialidad</option>
                            @foreach ($especialidades as $esp)
                                <option value="{{ $esp->id }}" {{ old('especialidad_id') == $esp->id ? 'selected' : '' }}>{{ $esp->nombre }}</option>
                            @endforeach
                        </select>
                        @error('especialidad_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Cuenta --}}
                <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-4 pb-1 border-b border-indigo-100">Cuenta</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Correo electrónico <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required value="{{ old('email') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-sm @error('email') border-red-500 @enderror"
                            placeholder="example@mail.com">
                        @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Contraseña trabajador --}}
                <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-4 pb-1 border-b border-indigo-100">Contraseña</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Contraseña <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" name="password" id="password-trabajador" required
                                oninput="validarPass('password-trabajador','password_confirmation-trabajador','req-trabajador','match-trabajador')"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all pr-12 text-sm @error('password') border-red-500 @enderror"
                                placeholder="Mín. 6 caracteres y una letra">
                            <button type="button" onclick="toggleVer('password-trabajador')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                        @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        <div id="req-trabajador" class="mt-2 space-y-1 hidden">
                            <div id="req-trabajador-len" class="flex items-center gap-2 text-xs text-gray-400">
                                <span class="req-icon w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center flex-shrink-0"></span>
                                Mínimo 6 caracteres
                            </div>
                            <div id="req-trabajador-letra" class="flex items-center gap-2 text-xs text-gray-400">
                                <span class="req-icon w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center flex-shrink-0"></span>
                                Al menos una letra (a-z)
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Repetir contraseña <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation-trabajador" required
                                oninput="validarPass('password-trabajador','password_confirmation-trabajador','req-trabajador','match-trabajador')"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all pr-12 text-sm"
                                placeholder="Repetir contraseña">
                            <button type="button" onclick="toggleVer('password_confirmation-trabajador')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                        <p id="match-trabajador" class="text-xs mt-1 hidden"></p>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-semibold py-3.5 rounded-lg transition-all duration-300 transform hover:scale-[1.01] shadow-lg hover:shadow-xl text-sm">
                    Registrarme
                </button>

                <p class="text-center mt-4 text-sm text-gray-600">
                    ¿Ya tenés cuenta?
                    <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Iniciá sesión</a>
                </p>
            </form>
        </div>

    </div>

    <script>
    function mostrarFormulario(tipo) {
        document.getElementById('seleccion').classList.add('hidden');
        document.getElementById(tipo === 'empresa' ? 'formEmpresa' : 'formTrabajador').classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    function volverSeleccion() {
        document.getElementById('formEmpresa').classList.add('hidden');
        document.getElementById('formTrabajador').classList.add('hidden');
        document.getElementById('seleccion').classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    function toggleVer(inputId) {
        const input = document.getElementById(inputId);
        input.type = input.type === 'password' ? 'text' : 'password';
    }
    function validarPass(passId, confId, reqId, matchId) {
        const pass    = document.getElementById(passId).value;
        const conf    = document.getElementById(confId).value;
        const reqEl   = document.getElementById(reqId);
        const matchEl = document.getElementById(matchId);
        const okLen   = pass.length >= 6;
        const okLetra = /[a-zA-Z]/.test(pass);
        pass.length > 0 ? reqEl.classList.remove('hidden') : reqEl.classList.add('hidden');
        actualizarReq(reqId + '-len',   okLen);
        actualizarReq(reqId + '-letra', okLetra);
        if (conf.length > 0) {
            matchEl.classList.remove('hidden');
            if (pass === conf) {
                matchEl.textContent = '✓ Las contraseñas coinciden';
                matchEl.className   = 'text-xs mt-1 text-green-600 font-medium';
            } else {
                matchEl.textContent = '✗ Las contraseñas no coinciden';
                matchEl.className   = 'text-xs mt-1 text-red-500 font-medium';
            }
        } else {
            matchEl.classList.add('hidden');
        }
    }
    function actualizarReq(rowId, cumple) {
        const row = document.getElementById(rowId);
        if (!row) return;
        const icon = row.querySelector('.req-icon');
        if (cumple) {
            row.classList.remove('text-gray-400'); row.classList.add('text-green-600', 'font-medium');
            icon.classList.remove('border-gray-300'); icon.classList.add('border-green-500', 'bg-green-500');
            icon.innerHTML = '<svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>';
        } else {
            row.classList.add('text-gray-400'); row.classList.remove('text-green-600', 'font-medium');
            icon.classList.add('border-gray-300'); icon.classList.remove('border-green-500', 'bg-green-500');
            icon.innerHTML = '';
        }
    }
    function validarFormEmpresa()   { return validarAlSubmit('password-empresa',    'password_confirmation-empresa'); }
    function validarFormTrabajador(){ return validarAlSubmit('password-trabajador', 'password_confirmation-trabajador'); }
    function validarAlSubmit(passId, confId) {
        const pass = document.getElementById(passId).value;
        const conf = document.getElementById(confId).value;
        if (pass.length < 6 || !/[a-zA-Z]/.test(pass)) { alert('La contraseña debe tener al menos 6 caracteres y contener una letra.'); return false; }
        if (pass !== conf) { alert('Las contraseñas no coinciden.'); return false; }
        return true;
    }
    document.addEventListener('DOMContentLoaded', function() {
        const cuit = document.getElementById('cuit');
        if (cuit) {
            cuit.addEventListener('input', function(e) {
                let v = e.target.value.replace(/\D/g, '');
                if (v.length > 2)  v = v.substring(0,2)  + '-' + v.substring(2);
                if (v.length > 11) v = v.substring(0,11) + '-' + v.substring(11);
                e.target.value = v.substring(0,13);
            });
        }
        const dni = document.getElementById('dni');
        if (dni) {
            dni.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\D/g,'').substring(0,8);
            });
        }
    });
    </script>
</body>
</html>