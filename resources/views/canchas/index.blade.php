<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Canchas Disponibles para Reserva') }}
        </h2>
        <p class="mt-2 text-sm text-gray-600">Encuentra y reserva tu cancha en el sector Bosa.</p>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($canchas->isEmpty())
                <!-- Mensaje de no hay canchas -->
                <div class="bg-white p-6 rounded-xl shadow-lg text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">No hay canchas disponibles</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Vuelve a intentarlo más tarde o revisa el estado de tus canchas en la base de datos.
                    </p>
                </div>
            @else
                <!-- Lista de Canchas -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($canchas as $cancha)
                        <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform hover:scale-[1.02] transition duration-300 ease-in-out">
                            <!-- Imagen y Estado -->
                            <div class="relative">
                                <!-- Usamos la URL de la foto de la DB -->
                                <img class="w-full h-48 object-cover" src="{{ $cancha->foto }}" onerror="this.onerror=null;this.src='https://placehold.co/600x400/2563eb/ffffff?text=Cancha+GoSports';" alt="Foto de {{ $cancha->nombre_cancha }}">
                                <span class="absolute top-4 right-4 bg-green-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                    {{ __('DISPONIBLE') }}
                                </span>
                            </div>

                            <!-- Contenido de la Tarjeta -->
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $cancha->nombre_cancha }}</h3>
                                
                                <p class="text-sm text-gray-600 mb-4">
                                    📍 **{{ $cancha->barrio }}**, {{ $cancha->nombre_localidad }} ({{ $cancha->nombre_ciudad }})
                                </p>

                                <!-- Detalles de la cancha -->
                                <div class="space-y-1 text-gray-700 text-sm">
                                    <p>⚽ **Deporte:** {{ $cancha->nombre_deporte }}</p>
                                    <p>🏟️ **Tipo:** {{ $cancha->tipo_cancha }}</p>
                                    <p>👥 **Capacidad:** {{ $cancha->capacidad }} personas</p>
                                    <p class="font-semibold text-lg text-indigo-700">💰 **Precio/Hora:** ${{ number_format($cancha->precio_hora, 0, ',', '.') }}</p>
                                </div>
                                
                                <hr class="my-5 border-gray-200">

                                <!-- Botón de Reserva -->
                                <a href="{{ route('reservas.create.cancha', ['cancha' => $cancha->id_cancha]) }}" 
                                   class="block w-full text-center bg-indigo-600 text-white font-semibold py-3 rounded-xl hover:bg-indigo-700 transition duration-150 shadow-md hover:shadow-lg">
                                    Reservar Ahora
                                </a>

                                <!-- Enlace a Google Maps -->
                                <a href="{{ $cancha->coordenadas }}" target="_blank"
                                   class="block w-full text-center mt-2 text-indigo-600 hover:text-indigo-800 transition duration-150 text-sm">
                                    Ver ubicación en el mapa
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>