@extends('layouts.trabajador')



@section('content')
    @php
        $trabajador->load('especialidades');

        $nombreCompleto = strtoupper(trim(($trabajador->nombre ?? '') . ' ' . ($trabajador->apellido ?? ''))) ?: 'Usuario';

        $fotoPerfil = !empty($trabajador->imagen_perfil) && file_exists(public_path('uploads/perfil/' . $trabajador->imagen_perfil))
            ? asset('uploads/perfil/' . $trabajador->imagen_perfil)
            : 'https://ui-avatars.com/api/?name=' . urlencode(($trabajador->nombre ?? '') . '+' . ($trabajador->apellido ?? '')) . '&background=2563eb&color=fff&size=128';

        $especialidadPrincipal = $trabajador->especialidades->firstWhere('pivot.es_principal', 1);
        $especialidadesList = $trabajador->especialidades;

        $cvUrl = !empty($trabajador->curriculum_pdf) && file_exists(public_path('uploads/cv/' . $trabajador->curriculum_pdf))
            ? asset('uploads/cv/' . $trabajador->curriculum_pdf)
            : null;
    @endphp

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ============================== VIEW MODE ============================== --}}
    <div id="vistaPerfil">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-900">Mi Perfil</h2>
                <p class="mt-1 text-sm text-gray-500">Información personal y configuración de tu cuenta.</p>
            </div>
            <button onclick="abrirModalEditar()"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition shadow-md hover:shadow-lg text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar datos
            </button>
        </div>

        {{-- Foto + datos básicos --}}
        <div class="flex flex-col md:flex-row gap-6 mb-8 pb-6 border-b border-gray-100">
            <div class="flex-shrink-0 flex flex-col items-center gap-2">
                <div class="w-32 h-32 rounded-2xl overflow-hidden border-2 border-gray-200 shadow-sm">
                    <img class="w-full h-full object-cover" src="{{ $fotoPerfil }}" alt="Foto de perfil">
                </div>
                <span class="text-xs text-gray-400">Foto de perfil</span>
            </div>

            <div class="flex-1">
                <h3 class="text-2xl font-extrabold text-gray-900">{{ $nombreCompleto }}</h3>
                @if(!empty($trabajador->nombre_titulo))
                    <p class="text-sm text-blue-600 font-semibold mt-1">{{ $trabajador->nombre_titulo }}</p>
                @endif

                @if($especialidadPrincipal)
                    <div class="mt-2 inline-flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-600 text-white text-xs font-bold rounded-full">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Especialidad principal
                        </span>
                        <span class="text-sm font-semibold text-gray-800">{{ $especialidadPrincipal->nombre }}</span>
                    </div>
                @endif

                <p class="text-sm text-gray-500 mt-3 leading-relaxed">{{ $trabajador->descripcion ?: 'Sin descripción' }}</p>
            </div>

            {{-- CV --}}
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 md:w-64">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Curriculum Vitae</label>
                @if($cvUrl)
                    <div class="flex items-center gap-3">
                        <svg class="w-8 h-8 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-700 truncate">{{ basename($trabajador->curriculum_pdf) }}</p>
                            <a href="{{ $cvUrl }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Ver PDF →</a>
                        </div>
                    </div>
                @else
                    <p class="text-gray-400 text-sm">No subiste tu CV aún</p>
                @endif
            </div>
        </div>

        {{-- Grid de datos --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Zona --}}
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Zona de búsqueda laboral</label>
                <div class="grid grid-cols-2 gap-4 mt-2">
                    <div>
                        <p class="text-xs text-gray-400">Provincia</p>
                        <p class="text-gray-900 font-semibold text-sm mt-0.5">{{ $trabajador->provincia?->nombre ?? 'No especificada' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Localidad</p>
                        <p class="text-gray-900 font-semibold text-sm mt-0.5">{{ $trabajador->localidad?->nombre ?? 'No especificada' }}</p>
                    </div>
                </div>
            </div>

            {{-- Experiencia --}}
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Años de experiencia</label>
                <p class="text-2xl font-extrabold text-blue-600">{{ $trabajador->anios_experiencia }} <span class="text-sm font-normal text-gray-500">años</span></p>
            </div>

            {{-- Especialidades --}}
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 md:col-span-2">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Especialidades</label>
                @if($especialidadesList->isNotEmpty())
                    <div class="flex flex-wrap gap-2">
                        @foreach($especialidadesList as $esp)
                            @if($esp->pivot->es_principal)
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 text-white rounded-full text-sm font-semibold shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    {{ $esp->nombre }}
                                </div>
                            @else
                                @php
                                    $badgeClass = match ($esp->pivot->nivel_experiencia) {
                                        'Intermedio' => 'bg-green-100 text-green-700',
                                        'Avanzado'   => 'bg-orange-100 text-orange-700',
                                        'Experto'    => 'bg-purple-100 text-purple-700',
                                        default      => 'bg-blue-100 text-blue-700',
                                    };
                                @endphp
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border-2 border-blue-200 rounded-full text-sm hover:border-blue-400 transition">
                                    <span class="font-semibold text-gray-900">{{ $esp->nombre }}</span>
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $badgeClass }}">{{ $esp->pivot->nivel_experiencia }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-400 text-sm">No agregaste especialidades aún</p>
                @endif
            </div>

            {{-- Fecha de nacimiento --}}
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Fecha de nacimiento</label>
                <p class="text-gray-900 font-medium text-sm">{{ $trabajador->fecha_nacimiento ? \Carbon\Carbon::parse($trabajador->fecha_nacimiento)->format('d/m/Y') : 'No especificada' }}</p>
            </div>

            {{-- DNI --}}
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">DNI</label>
                <p class="text-gray-900 font-medium text-sm">{{ $trabajador->dni ?: 'No especificado' }}</p>
            </div>

            {{-- Teléfono --}}
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Teléfono</label>
                <p class="text-gray-900 font-medium text-sm">{{ $trabajador->telefono ?: 'No especificado' }}</p>
            </div>

            {{-- Título --}}
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Título</label>
                <p class="text-gray-900 font-medium text-sm">{{ $trabajador->nombre_titulo ?: 'Sin título' }}</p>
            </div>

            {{-- Domicilio --}}
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 md:col-span-2">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Domicilio</label>
                <p class="text-gray-900 font-medium text-sm">{{ $trabajador->domicilio ?: 'No especificado' }}</p>
            </div>
        </div>
    </div>

    {{-- ============================== MODAL EDITAR ============================== --}}
    <div id="modalEditar" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">

            <div class="bg-blue-600 px-6 py-4 flex items-center justify-between flex-shrink-0">
                <h3 class="text-xl font-extrabold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar Perfil
                </h3>
                <button onclick="cerrarModalEditar()" class="text-white hover:text-blue-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('trabajador.perfil.update') }}" enctype="multipart/form-data" class="overflow-y-auto flex-1">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">

                    {{-- FOTO --}}
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <label class="block text-sm font-bold text-gray-700 mb-4">Foto de perfil</label>
                        <div class="flex items-center gap-6">
                            <div class="relative flex-shrink-0">
                                <div class="w-24 h-24 rounded-2xl overflow-hidden border-2 border-gray-200 shadow-sm">
                                    <img id="foto-preview" src="{{ $fotoPerfil }}" alt="Preview" class="w-full h-full object-cover">
                                </div>
                                <label for="imagen_perfil"
                                    class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 rounded-2xl opacity-0 hover:opacity-100 transition cursor-pointer">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </label>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="imagen_perfil" id="imagen_perfil"
                                    accept="image/jpeg,image/jpg,image/png,image/webp"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition"
                                    onchange="previewFoto(this)">
                                <p class="mt-2 text-xs text-gray-400">JPG, PNG o WEBP · Máximo 2MB</p>
                            </div>
                        </div>
                    </div>

                    {{-- DATOS PERSONALES --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nombre *</label>
                            <input type="text" name="nombre" required value="{{ old('nombre', $trabajador->nombre) }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Apellido *</label>
                            <input type="text" name="apellido" required value="{{ old('apellido', $trabajador->apellido) }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">DNI *</label>
                            <input type="text" name="dni" required value="{{ old('dni', $trabajador->dni) }}"
                                maxlength="8" pattern="[0-9]{7,8}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm"
                                placeholder="12345678" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,8)">
                            <p class="mt-1 text-xs text-gray-400">Solo números, 7 u 8 dígitos</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Fecha de nacimiento</label>
                            <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $trabajador->fecha_nacimiento) }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Teléfono</label>
                            <input type="tel" name="telefono" value="{{ old('telefono', $trabajador->telefono) }}"
                                maxlength="20" placeholder="+54 9 11 1234-5678"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Título</label>
                            <input type="text" name="nombre_titulo" value="{{ old('nombre_titulo', $trabajador->nombre_titulo) }}"
                                maxlength="50" placeholder="Ej: Maestro mayor de obras"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Domicilio</label>
                            <input type="text" name="domicilio" value="{{ old('domicilio', $trabajador->domicilio) }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm">
                        </div>
                    </div>

                    {{-- DESCRIPCIÓN --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Descripción personal</label>
                        <textarea name="descripcion" rows="3" maxlength="500"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm"
                            placeholder="Contá brevemente tu experiencia y perfil profesional.">{{ old('descripcion', $trabajador->descripcion) }}</textarea>
                        <p class="mt-1 text-xs text-gray-400">Máximo 500 caracteres</p>
                    </div>

                    {{-- CV --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Curriculum Vitae (PDF)</label>
                        @if($cvUrl)
                            <div class="mb-3 p-3 bg-gray-50 rounded-xl flex items-center justify-between border border-gray-100">
                                <div class="flex items-center gap-2">
                                    <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ basename($trabajador->curriculum_pdf) }}</span>
                                </div>
                                <a href="{{ $cvUrl }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Ver actual →</a>
                            </div>
                        @endif
                        <input type="file" name="curriculum_pdf" accept="application/pdf"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition">
                        <p class="mt-1 text-xs text-gray-400">Solo PDF · Máximo 5MB</p>
                    </div>

                    {{-- ZONA DE BÚSQUEDA --}}
                    <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                        <h4 class="text-sm font-bold text-gray-900 mb-1 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            ¿Dónde buscás trabajo?
                        </h4>
                        <p class="text-xs text-gray-500 mb-3">Las empresas podrán encontrarte según tu zona</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Provincia</label>
                                <select name="provincia_preferencia_id" id="id_provincia_preferencia"
                                    onchange="cargarLocalidades(this.value)"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white text-sm">
                                    <option value="">Seleccioná una provincia</option>
                                    @foreach($provincias as $provincia)
                                        <option value="{{ $provincia->id }}"
                                            {{ old('provincia_preferencia_id', $trabajador->provincia_preferencia_id) == $provincia->id ? 'selected' : '' }}>
                                            {{ $provincia->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Localidad</label>
                                <select name="localidad_preferencia_id" id="id_localidad_preferencia"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white text-sm">
                                    <option value="">Seleccioná primero una provincia</option>
                                    @foreach($localidades as $localidad)
                                        <option value="{{ $localidad->id }}"
                                            {{ old('localidad_preferencia_id', $trabajador->localidad_preferencia_id) == $localidad->id ? 'selected' : '' }}>
                                            {{ $localidad->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- AÑOS DE EXPERIENCIA --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Años de experiencia total</label>
                        <input type="number" name="anios_experiencia" min="0" max="50"
                            value="{{ old('anios_experiencia', $trabajador->anios_experiencia) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm">
                        <p class="mt-1 text-xs text-gray-400">Total de años trabajando en construcción</p>
                    </div>

                    {{-- ESPECIALIDADES --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Especialidades</label>
                        <p class="text-xs text-gray-400 mb-3">
                            Seleccioná tus especialidades, indicá el nivel y marcá cuál es tu <strong>especialidad principal</strong> (solo una).
                        </p>

                        <input type="hidden" name="especialidad_principal" id="id_especialidad_principal"
                            value="{{ old('especialidad_principal', $especialidadPrincipal?->id ?? '') }}">

                        @php
                            $misEspecialidadesIds = $especialidadesList->pluck('id')->toArray();
                            $espNiveles = [];
                            foreach ($especialidadesList as $esp) {
                                $espNiveles[$esp->id] = $esp->pivot->nivel_experiencia;
                            }
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-80 overflow-y-auto border border-gray-200 rounded-xl p-4 bg-gray-50">
                            @foreach($especialidades as $esp)
                                @php
                                    $checked = in_array($esp->id, $misEspecialidadesIds);
                                    $nivel = $espNiveles[$esp->id] ?? 'Básico';
                                    $isPrincipal = ($especialidadPrincipal && $esp->id == $especialidadPrincipal->id);
                                @endphp
                                <div class="bg-white border-2 rounded-xl p-3 transition {{ $isPrincipal ? 'border-blue-500' : 'border-gray-200 hover:border-blue-200' }}"
                                     id="card_esp_{{ $esp->id }}">

                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <input type="checkbox" name="especialidades[]"
                                                value="{{ $esp->id }}"
                                                id="esp_{{ $esp->id }}"
                                                {{ $checked ? 'checked' : '' }}
                                                onchange="toggleEspecialidad({{ $esp->id }})"
                                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            <label for="esp_{{ $esp->id }}" class="text-sm font-semibold text-gray-900 cursor-pointer">
                                                {{ $esp->nombre }}
                                            </label>
                                        </div>
                                        <button type="button"
                                            id="btn_principal_{{ $esp->id }}"
                                            onclick="marcarPrincipal({{ $esp->id }})"
                                            title="Marcar como principal"
                                            class="{{ $isPrincipal ? 'text-blue-600' : 'text-gray-300 hover:text-blue-400' }} transition {{ !$checked ? 'hidden' : '' }}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </button>
                                    </div>

                                    @if($isPrincipal)
                                        <div id="label_principal_{{ $esp->id }}"
                                            class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            Especialidad principal
                                        </div>
                                        <select name="nivel_{{ $esp->id }}" id="nivel_{{ $esp->id }}" class="hidden">
                                            <option value="Básico" {{ $nivel === 'Básico' ? 'selected' : '' }}>Básico</option>
                                            <option value="Intermedio" {{ $nivel === 'Intermedio' ? 'selected' : '' }}>Intermedio</option>
                                            <option value="Avanzado" {{ $nivel === 'Avanzado' ? 'selected' : '' }}>Avanzado</option>
                                            <option value="Experto" {{ $nivel === 'Experto' ? 'selected' : '' }}>Experto</option>
                                        </select>
                                    @else
                                        <div id="label_principal_{{ $esp->id }}"
                                            class="hidden inline-flex items-center gap-1 px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            Especialidad principal
                                        </div>
                                        <select name="nivel_{{ $esp->id }}" id="nivel_{{ $esp->id }}"
                                            {{ !$checked ? 'disabled' : '' }}
                                            class="w-full text-sm px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition {{ !$checked ? 'bg-gray-100' : 'bg-white' }}">
                                            <option value="Básico"     {{ $nivel === 'Básico'     ? 'selected' : '' }}>Básico</option>
                                            <option value="Intermedio" {{ $nivel === 'Intermedio' ? 'selected' : '' }}>Intermedio</option>
                                            <option value="Avanzado"   {{ $nivel === 'Avanzado'   ? 'selected' : '' }}>Avanzado</option>
                                            <option value="Experto"    {{ $nivel === 'Experto'    ? 'selected' : '' }}>Experto</option>
                                        </select>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-400 mt-2">Tocá la ⭐ de una especialidad seleccionada para marcarla como principal.</p>
                    </div>
                </div>

                {{-- Footer modal --}}
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-gray-100 flex-shrink-0">
                    <button type="button" onclick="cerrarModalEditar()"
                        class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-100 transition font-medium text-sm">
                        Descartar cambios
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition font-semibold shadow-md text-sm">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // ── Modal ──────────────────────────────────────────────────────────────
    function abrirModalEditar() {
        document.getElementById('modalEditar').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        const provinciaSelect = document.getElementById('id_provincia_preferencia');
        const localidadActual = {{ $trabajador->localidad_preferencia_id ?? 'null' }};
        if (provinciaSelect && provinciaSelect.value) {
            cargarLocalidades(provinciaSelect.value, localidadActual);
        }
    }

    function cerrarModalEditar() {
        document.getElementById('modalEditar').classList.add('hidden');
        document.body.style.overflow = 'auto';
        const inputFoto = document.getElementById('imagen_perfil');
        if (inputFoto) inputFoto.value = '';
        document.getElementById('foto-preview').src = '{{ $fotoPerfil }}';
    }

    // ── Foto preview ───────────────────────────────────────────────────────
    function previewFoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('foto-preview').src = e.target.result;
            reader.readAsDataURL(input.files[0]);
        }
    }

    // ── Localidades ────────────────────────────────────────────────────────
    function cargarLocalidades(idProvincia, localidadSeleccionada = null) {
        const select = document.getElementById('id_localidad_preferencia');
        select.innerHTML = '<option value="">Cargando...</option>';
        if (!idProvincia) {
            select.innerHTML = '<option value="">Seleccioná primero una provincia</option>';
            return;
        }
        fetch('/api/localidades/' + idProvincia)
            .then(r => r.ok ? r.json() : Promise.reject(r.status))
            .then(data => {
                select.innerHTML = '<option value="">Todas las localidades</option>';
                data.forEach(loc => {
                    const opt = document.createElement('option');
                    opt.value = loc.id;
                    opt.textContent = loc.nombre;
                    if (localidadSeleccionada && loc.id == localidadSeleccionada) opt.selected = true;
                    select.appendChild(opt);
                });
            })
            .catch(() => { select.innerHTML = '<option value="">Error al cargar localidades</option>'; });
    }

    // ── Especialidades ─────────────────────────────────────────────────────
    function toggleEspecialidad(id) {
        const cb = document.getElementById('esp_' + id);
        const sel = document.getElementById('nivel_' + id);
        const btn = document.getElementById('btn_principal_' + id);
        const card = document.getElementById('card_esp_' + id);

        if (cb.checked) {
            sel.disabled = false;
            sel.classList.replace('bg-gray-100', 'bg-white');
            btn.classList.remove('hidden');
        } else {
            const hiddenPrincipal = document.getElementById('id_especialidad_principal');
            if (hiddenPrincipal.value == id) hiddenPrincipal.value = '';
            sel.disabled = true;
            sel.classList.replace('bg-white', 'bg-gray-100');
            sel.value = 'Básico';
            btn.classList.add('hidden');
            card.classList.replace('border-blue-500', 'border-gray-200');
            document.getElementById('label_principal_' + id).classList.add('hidden');
            btn.classList.remove('text-blue-600');
            btn.classList.add('text-gray-300');
        }
    }

    function marcarPrincipal(id) {
        const cb = document.getElementById('esp_' + id);
        if (!cb.checked) return;

        const anterior = document.getElementById('id_especialidad_principal').value;

        if (anterior && anterior != id) {
            const cardAnt = document.getElementById('card_esp_' + anterior);
            const btnAnt = document.getElementById('btn_principal_' + anterior);
            const lblAnt = document.getElementById('label_principal_' + anterior);
            const selAnt = document.getElementById('nivel_' + anterior);
            if (cardAnt) cardAnt.classList.replace('border-blue-500', 'border-gray-200');
            if (btnAnt) { btnAnt.classList.remove('text-blue-600'); btnAnt.classList.add('text-gray-300'); }
            if (lblAnt) lblAnt.classList.add('hidden');
            if (selAnt) selAnt.classList.remove('hidden');
        }

        if (anterior == id) {
            document.getElementById('id_especialidad_principal').value = '';
            const card = document.getElementById('card_esp_' + id);
            const btn = document.getElementById('btn_principal_' + id);
            const lbl = document.getElementById('label_principal_' + id);
            const sel = document.getElementById('nivel_' + id);
            card.classList.replace('border-blue-500', 'border-gray-200');
            btn.classList.remove('text-blue-600'); btn.classList.add('text-gray-300');
            lbl.classList.add('hidden');
            sel.classList.remove('hidden');
            return;
        }

        document.getElementById('id_especialidad_principal').value = id;
        const card = document.getElementById('card_esp_' + id);
        const btn = document.getElementById('btn_principal_' + id);
        const lbl = document.getElementById('label_principal_' + id);
        const sel = document.getElementById('nivel_' + id);
        card.classList.replace('border-gray-200', 'border-blue-500');
        btn.classList.remove('text-gray-300'); btn.classList.add('text-blue-600');
        lbl.classList.remove('hidden');
        sel.classList.add('hidden');
    }

    // ── Escape / click fuera ───────────────────────────────────────────────
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') cerrarModalEditar(); });
    document.getElementById('modalEditar')?.addEventListener('click', function(e) {
        if (e.target === this) cerrarModalEditar();
    });
    </script>
@endsection
