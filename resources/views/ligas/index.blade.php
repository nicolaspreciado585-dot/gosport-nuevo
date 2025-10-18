<x-app-layout>
    <div class="max-w-6xl mx-auto py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">🏆 Ligas Disponibles</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($ligas as $liga)
                <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-xl transition">
                    <div class="p-4">
                        <h2 class="text-lg font-semibold text-gray-800">{{ $liga->nombre }}</h2>
                        <p class="text-sm text-gray-500 mb-2">Temporada: {{ $liga->temporada->nombre ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600 mb-4">{{ Str::limit($liga->descripcion, 80) }}</p>
                        <a href="{{ route('ligas.show', $liga->id_liga) }}" 
                           class="block text-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-500 transition">
                           Ver Detalles
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $ligas->links() }}
        </div>
    </div>
</x-app-layout>
