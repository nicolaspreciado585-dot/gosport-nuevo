<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Eventos') }}
        </h2>
    </x-slot>

    <div class="py-8 px-6 bg-gray-100 min-h-screen">
        <!-- Botón Crear Evento siempre visible -->
        <div class="flex justify-end mb-4">
            <a href="{{ route('admin.eventos.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150 shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('Crear Evento') }}
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg border border-green-400">
                {{ session('success') }}
            </div>
        @endif

        @if($eventos->isEmpty())
            <div class="bg-white p-6 rounded-lg shadow text-center text-gray-500">
                <p>No hay eventos registrados.</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Deporte</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Fecha Inicio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Participantes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($eventos as $evento)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $evento->nombre }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $evento->deporte->nombre ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $evento->fecha_inicio->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full 
                                        @if($evento->estaLleno()) bg-red-100 text-red-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ $evento->contarParticipantes() }}/{{ $evento->capacidad_participantes }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full 
                                        @if($evento->estado === 'abierto') bg-blue-100 text-blue-800
                                        @elseif($evento->estado === 'cerrado') bg-red-100 text-red-800
                                        @elseif($evento->estado === 'en_progreso') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $evento->estado)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                    <a href="{{ route('admin.eventos.show', $evento) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">Ver</a>
                                    <a href="{{ route('admin.eventos.edit', $evento) }}" class="text-yellow-600 hover:text-yellow-900 font-semibold">Editar</a>
                                    <form action="{{ route('admin.eventos.destroy', $evento) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este evento?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-6">
                {{ $eventos->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
