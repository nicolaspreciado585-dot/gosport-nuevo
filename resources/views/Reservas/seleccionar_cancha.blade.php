<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Seleccionar Cancha para Reservar') }}
        </h2>
    </x-slot>

    <div class="py-10 px-6 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Canchas Disponibles</h1>

            @if ($canchas->isEmpty())
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg shadow-md">
                    <p class="font-bold">Aviso:</p>
                    <p>En este momento no hay canchas disponibles para reserva.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($canchas as $cancha)
                        <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform hover:scale-[1.02] transition duration-300">
                            <!-- Imagen de la cancha -->
                            <img src="{{ asset($cancha->foto) }}"
                                 alt="{{ $cancha->nombre }}"
                                 class="w-full h-48 object-cover"
                                 onerror="this.onerror=null;this.src='https://placehold.co/600x400/374151/FFFFFF?text=Cancha%20{{ $cancha->id_cancha }}';">
                            
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $cancha->nombre }}</h3>
                                
                                <p class="text-gray-600 text-sm mb-3">
                                    <span class="font-semibold">Deporte:</span> {{ $cancha->deporte->nombre ?? 'N/A' }}
                                </p>
                                
                                <div class="flex justify-between items-center mb-4">
                                    <p class="text-2xl font-extrabold text-indigo-600">
                                        ${{ number_format($cancha->precio_hora, 0, ',', '.') }} / hr
                                    </p>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ ucfirst($cancha->estado) }}
                                    </span>
                                </div>

                                <a href="{{ route('reservas.create.cancha', $cancha->id_cancha) }}" 
                                   class="block text-center bg-indigo-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">
                                    Reservar Ahora
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>