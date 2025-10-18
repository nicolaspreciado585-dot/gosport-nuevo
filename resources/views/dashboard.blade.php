<x-app-layout>
    <x-slot name="header">
        <br>
        <br>
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard GoSports - Bosa') }}
            </h2>

            <a href="{{ route('admin.reservas.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ __('Reservas') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-6 bg-gray-100 min-h-screen">
        @php
            // Helper function (Asegúrate de que esta función exista en tu código PHP)
            if (!function_exists('mapCanchaImage')) {
                function mapCanchaImage($id_cancha, $db_foto_url) {
                    if ($db_foto_url && !str_contains($db_foto_url, 'placehold.co')) {
                        return asset($db_foto_url); 
                    }
                    switch ($id_cancha) {
                        case 1: return asset('imagenes/Canchabosa1.jpeg');
                        case 2: return asset('imagenes/Canchabosa2.jpeg');
                        case 3: return asset('imagenes/Canchabosa3.jpeg');
                        case 4: return asset('imagenes/Tenisbosa1.jpeg');
                        case 5: return asset('imagenes/Basket1.jpeg');
                        case 6: return asset('imagenes/Tibanica.jpeg');
                        case 7: return asset('imagenes/elporvenir.jpeg');
                        default: return 'https://placehold.co/400x200/4f46e5/ffffff?text=Cancha+No+Mapeada';
                    }
                }
            }
        @endphp

        <!-- Tarjetas de Estado (Valores dinámicos del controlador) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
             <div class="bg-indigo-600 text-white rounded-xl p-6 shadow-lg"><h3 class="text-lg">Usuarios</h3><p class="text-3xl font-bold">{{ $totalUsuarios ?? 'N/A' }}</p></div>
             <div class="bg-gray-800 text-white rounded-xl p-6 shadow-lg"><h3 class="text-lg">Reservas</h3><p class="text-3xl font-bold">{{ $totalReservas ?? 'N/A' }}</p></div>
             <div class="bg-green-600 text-white rounded-xl p-6 shadow-lg"><h3 class="text-lg">Pagos</h3><p class="text-3xl font-bold">${{ $totalPagos ?? 'N/A' }}</p></div>
             <div class="bg-yellow-600 text-white rounded-xl p-6 shadow-lg"><h3 class="text-lg">Eventos</h3><p class="text-3xl font-bold">{{ $totalEventos ?? 'N/A' }}</p></div>
        </div>

        <!-- Gráficos (Los IDs de canvas son CRÍTICOS para el JS) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            <div class="bg-white p-6 rounded-xl shadow-lg"><h3 class="text-lg font-semibold mb-4">Reservas por deporte</h3><canvas id="reservasChart" height="120"></canvas></div>
            <div class="bg-white p-6 rounded-xl shadow-lg"><h3 class="text-lg font-semibold mb-4">Métodos de pago</h3><canvas id="pagosChart" height="120"></canvas></div>
            <div class="bg-white p-6 rounded-xl shadow-lg"><h3 class="text-lg font-semibold mb-4">Usuarios por edad</h3><canvas id="usuariosChart" height="120"></canvas></div>
            <div class="bg-white p-6 rounded-xl shadow-lg"><h3 class="text-lg font-semibold mb-4">Eventos por mes</h3><canvas id="eventosChart" height="120"></canvas></div>
        </div>

        <!-- Lista de Canchas -->
        <section>
            <h2 class="text-xl font-semibold mb-6 border-b pb-2 text-gray-700">Gestión de Canchas en Bosa</h2>
            <div id="canchas-lista" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($canchas as $cancha)
                    @php $imageUrl = mapCanchaImage($cancha->id_cancha, $cancha->foto ?? null); @endphp
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transition duration-300 ease-in-out hover:shadow-2xl">
                        <img src="{{ $imageUrl }}" alt="Foto de la cancha {{ $cancha->nombre }}" class="w-full h-40 object-cover"
                             onerror="this.onerror=null;this.src='https://placehold.co/400x200/4f46e5/ffffff?text=Cancha+Sin+Foto';">
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-lg font-bold text-gray-900">{{ $cancha->nombre }}</h3>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                    @if($cancha->estado == 'disponible') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($cancha->estado) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">⚽ Deporte: **{{ $cancha->deporte->nombre ?? 'N/A' }}**</p>
                            <p class="text-sm text-gray-600 mb-4">📍 Dirección: **{{ $cancha->direccion->calle ?? 'Dirección No Disponible' }}** ({{ $cancha->direccion->barrio ?? 'N/A' }})</p>
                            <a href="{{ route('reservas.create.cancha', ['cancha' => $cancha->id_cancha]) }}" 
                               class="block text-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-500 transition duration-150 text-sm font-medium">
                                Ver Detalles y Calendario
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-6 rounded-xl shadow-lg text-center text-gray-500">
                        Aún no tienes canchas registradas o no se encontraron canchas.
                    </div>
                @endforelse
            </div>
        </section>
        
        <!-- INYECCIÓN DE DATOS PARA EL JAVASCRIPT -->
        <!-- La variable $chartsConfig debe ser inyectada por el controlador PHP -->
        <script>
            window.chartsConfig = @json($chartsConfig ?? []);
        </script>
        
    </div>
</x-app-layout>
