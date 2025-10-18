<x-app-layout>
    <div class="max-w-6xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">📅 Calendario de la {{ $liga->nombre }}</h1>

        <table class="min-w-full bg-white border rounded-lg shadow">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2">Jornada</th>
                    <th class="px-4 py-2">Local</th>
                    <th class="px-4 py-2">Visitante</th>
                    <th class="px-4 py-2">Fecha</th>
                    <th class="px-4 py-2">Hora</th>
                    <th class="px-4 py-2">Resultado</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($partidos as $p)
                    <tr class="text-center border-t">
                        <td>{{ $p->jornada }}</td>
                        <td>{{ $p->local->nombre_equipo ?? 'N/A' }}</td>
                        <td>{{ $p->visitante->nombre_equipo ?? 'N/A' }}</td>
                        <td>{{ $p->fecha }}</td>
                        <td>{{ $p->hora }}</td>
                        <td>
                            @if($p->estado === 'Finalizado')
                                {{ $p->goles_local }} - {{ $p->goles_visitante }}
                            @else
                                <span class="text-gray-400">Pendiente</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">No hay partidos programados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
