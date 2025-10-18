<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Evento') }}
        </h2>
    </x-slot>

    <div class="py-8 px-6 bg-gray-100 min-h-screen">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-8">
            
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg border border-red-400">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.eventos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre del Evento *</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required
                           class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500 @error('nombre') border-red-500 @enderror">
                    @error('nombre')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="4"
                              class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500 @error('descripcion') border-red-500 @enderror">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha Inicio *</label>
                        <input type="datetime-local" name="fecha_inicio" id="fecha_inicio" value="{{ old('fecha_inicio') }}" required
                               class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500 @error('fecha_inicio') border-red-500 @enderror">
                        @error('fecha_inicio')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="fecha_fin" class="block text-sm font-medium text-gray-700">Fecha Fin *</label>
                        <input type="datetime-local" name="fecha_fin" id="fecha_fin" value="{{ old('fecha_fin') }}" required
                               class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500 @error('fecha_fin') border-red-500 @enderror">
                        @error('fecha_fin')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="capacidad_participantes" class="block text-sm font-medium text-gray-700">Capacidad de Participantes *</label>
                        <input type="number" name="capacidad_participantes" id="capacidad_participantes" value="{{ old('capacidad_participantes') }}" required min="1"
                               class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500 @error('capacidad_participantes') border-red-500 @enderror">
                        @error('capacidad_participantes')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="precio_inscripcion" class="block text-sm font-medium text-gray-700">Precio Inscripción ($)</label>
                        <input type="number" name="precio_inscripcion" id="precio_inscripcion" value="{{ old('precio_inscripcion') }}" step="0.01" min="0"
                               class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500 @error('precio_inscripcion') border-red-500 @enderror">
                        @error('precio_inscripcion')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="id_deporte" class="block text-sm font-medium text-gray-700">Deporte</label>
                        <select name="id_deporte" id="id_deporte"
                                class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500 @error('id_deporte') border-red-500 @enderror">
                            <option value="">-- Seleccionar --</option>
                            @foreach($deportes as $deporte)
                                <option value="{{ $deporte->id_deporte }}" {{ old('id_deporte') == $deporte->id_deporte ? 'selected' : '' }}>
                                    {{ $deporte->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_deporte')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="id_cancha" class="block text-sm font-medium text-gray-700">Cancha</label>
                        <select name="id_cancha" id="id_cancha"
                                class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500 @error('id_cancha') border-red-500 @enderror">
                            <option value="">-- Seleccionar --</option>
                            @foreach($canchas as $cancha)
                                <option value="{{ $cancha->id_cancha }}" {{ old('id_cancha') == $cancha->id_cancha ? 'selected' : '' }}>
                                    {{ $cancha->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_cancha')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="ubicacion" class="block text-sm font-medium text-gray-700">Ubicación</label>
                    <input type="text" name="ubicacion" id="ubicacion" value="{{ old('ubicacion') }}"
                           class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500 @error('ubicacion') border-red-500 @enderror">
                    @error('ubicacion')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700">Estado *</label>
                    <select name="estado" id="estado" required
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500 @error('estado') border-red-500 @enderror">
                        <option value="">-- Seleccionar --</option>
                        <option value="abierto" {{ old('estado') == 'abierto' ? 'selected' : '' }}>Abierto</option>
                        <option value="cerrado" {{ old('estado') == 'cerrado' ? 'selected' : '' }}>Cerrado</option>
                        <option value="en_progreso" {{ old('estado') == 'en_progreso' ? 'selected' : '' }}>En Progreso</option>
                        <option value="finalizado" {{ old('estado') == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                    </select>
                    @error('estado')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="foto" class="block text-sm font-medium text-gray-700">Foto</label>
                    <input type="file" name="foto" id="foto" accept="image/*"
                           class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500 @error('foto') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Máximo 2MB. Formatos: JPEG, PNG, JPG, GIF</p>
                    @error('foto')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4 pt-4">
                    <a href="{{ route('admin.eventos.index') }}" 
                       class="flex-1 text-center px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-semibold">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold">
                        Crear Evento
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
