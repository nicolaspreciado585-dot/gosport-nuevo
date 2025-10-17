<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Informe de Reservas') }}
        </h2>
    </x-slot>

    <div class="py-10 px-6 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white p-8 rounded-2xl shadow-xl">

                <div class="flex justify-between items-center mb-6 border-b pb-4">
                    <h1 class="text-3xl font-bold text-gray-800">Reporte de Todas las Reservas</h1>
                    <span class="text-sm text-gray-500">Generado el: {{ $fechaGeneracion->format('Y-m-d H:i:s') }}</span>
                </div>
                
                <!-- Botón de Exportar (Funcionalidad pendiente) -->
                <div class="mb-6">
                    <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150 shadow-md">
                        Imprimir / Guardar como PDF
                    </button>
                    <a href="{{ route('admin.reservas.index') }}" class="ml-4 text-indigo-600 hover:text-indigo-800 font-semibold">
                        < Volver a Gestión de Reservas
                    </a>
                </div>

                <!-- Tabla de Datos del Informe -->
                <div class="overflow-x-auto shadow-lg rounded-xl">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Inicio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cancha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($datosInforme as $dato)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $dato['ID'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $dato['Fecha Inicio'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $dato['Cancha'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $dato['Usuario'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if ($dato['Estado'] == 'confirmada') bg-green-100 text-green-800
                                            @elseif ($dato['Estado'] == 'pendiente') bg-yellow-100 text-yellow-800
                                            @elseif ($dato['Estado'] == 'cancelada') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($dato['Estado']) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No se encontraron reservas para el informe.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>