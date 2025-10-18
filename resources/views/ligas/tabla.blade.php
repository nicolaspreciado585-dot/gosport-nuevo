<x-app-layout>
    <div class="max-w-5xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">📊 Tabla de Posiciones - {{ $liga->nombre }}</h1>

        <table class="min-w-full bg-white border rounded-lg shadow">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2">Equipo</th>
                    <th class="px-4 py-2">PJ</th>
                    <th class="px-4 py-2">PG</th>
                    <th class="px-4 py-2">PE</th>
                    <th class="px-4 py-2">PP</th>
                    <th class="px-4 py-2">GF</th>
                    <th class="px-4 py-2">GC</th>
                    <th class="px-4 py-2">DG</th>
                    <th class="px-4 py-2">Puntos</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tabla as $fila)
                    <tr class="text-center border-t">
                        <td>{{ $fila->equipo->nombre_equipo ?? 'N/A' }}</td>
                        <td>{{ $fila->partidos_jugados }}</td>
                        <td>{{ $fila->partidos_ganados }}</td>
                        <td>{{ $fila->partidos_empatados }}</td>
                        <td>{{ $fila->partidos_perdidos }}</td>
                        <td>{{ $fila->goles_favor }}</td>
                        <td>{{ $fila->goles_contra }}</td>
                        <td>{{ $fila->diferencia_goles }}</td>
                        <td class="font-bold">{{ $fila->puntos }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-gray-500">No hay registros en la tabla.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
