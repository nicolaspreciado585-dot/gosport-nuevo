<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Reserva #') . $reserva->id_reserva }}
        </h2>
    </x-slot>

    <div class="py-8 px-6 bg-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto bg-white p-6 rounded-2xl shadow-lg">

            {{-- Info de la reserva --}}
            <div class="mb-6 p-4 bg-indigo-50 rounded-xl shadow-inner border-l-4 border-indigo-500">
                <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $reserva->cancha->nombre ?? 'N/A' }}</h3>
                <p class="text-sm text-gray-600">👤 Usuario: {{ $reserva->usuario->nombre ?? 'N/A' }}</p>
                <p class="text-sm text-gray-600">📅 Fecha: {{ \Carbon\Carbon::parse($reserva->fecha_inicio)->format('d/M/Y H:i') }} - {{ \Carbon\Carbon::parse($reserva->fecha_fin)->format('H:i') }}</p>
                <p class="text-sm text-gray-600">📍 Dirección: {{ $reserva->cancha->direccion->calle ?? 'N/A' }}</p>
            </div>

            <form action="{{ route('admin.reservas.update', $reserva->id_reserva) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Cambiar Estado --}}
                <div>
                    <label for="estado" class="block text-gray-700 font-semibold mb-1">Cambiar Estado</label>
                    <select name="estado" id="estado"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                            required>
                        @foreach ($estados as $estado)
                            <option value="{{ $estado }}" 
                                    {{ $reserva->estado == $estado ? 'selected' : '' }}
                                    class="capitalize">
                                {{ $estado }}
                            </option>
                        @endforeach
                    </select>
                    @error('estado')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Editar horario --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="fecha_inicio" class="block text-gray-700 font-semibold mb-1">Fecha y hora inicio</label>
                        <input type="datetime-local" name="fecha_inicio" id="fecha_inicio"
                               value="{{ $reserva->fecha_inicio->format('Y-m-d\TH:i') }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('fecha_inicio')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="fecha_fin" class="block text-gray-700 font-semibold mb-1">Fecha y hora fin</label>
                        <input type="datetime-local" name="fecha_fin" id="fecha_fin"
                               value="{{ $reserva->fecha_fin->format('Y-m-d\TH:i') }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('fecha_fin')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Botones --}}
                <div class="flex justify-between items-center">
                    <a href="{{ route('admin.reservas.index') }}" class="text-gray-600 hover:text-gray-900 transition">
                        ← Volver al Listado
                    </a>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition font-bold shadow-md">
                        Actualizar Reserva
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
