<x-app-layout>
    <div class="max-w-5xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4 text-gray-800">{{ $liga->nombre }}</h1>
        <p class="text-gray-600 mb-4">🏅 Temporada: {{ $liga->temporada->nombre ?? 'N/A' }}</p>

        <div class="flex gap-3 mb-6">
            <a href="{{ route('ligas.calendario', $liga->id_liga) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">📅 Ver Calendario</a>
            <a href="{{ route('ligas.tabla', $liga->id_liga) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">📊 Tabla de Posiciones</a>
        </div>

        <div class="bg-white shadow p-4 rounded-lg">
            <h2 class="text-xl font-semibold mb-3 text-gray-700">Descripción</h2>
            <p class="text-gray-600">{{ $liga->descripcion ?? 'Sin descripción' }}</p>
        </div>
    </div>
</x-app-layout>
