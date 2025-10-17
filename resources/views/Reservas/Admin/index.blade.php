<x-app-layout>
<x-slot name="header">
{{-- Título y botón de Crear Reserva --}}
<div class="flex justify-between items-center">
<h2 class="font-semibold text-xl text-white leading-tight">
{{ __('Gestión de Reservas') }}
</h2>
<a href="{{ route('reservas.create') }}"
class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
Crear Reserva
</a>
</div>
</x-slot>

{{-- Contenedor principal y fondo oscuro general --}}
<div class="py-8 px-6 bg-[#32373dff] min-h-screen">
    <div class="max-w-7xl mx-auto">

        @if (session('success'))
            <div class="mb-6 p-4 border border-green-400 bg-green-100 text-green-700 rounded-xl shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Contenedor de la tabla, también oscuro --}}
        <div x-data="{ open: false, targetUrl: '' }" class="bg-[#32373dff] shadow-xl sm:rounded-lg overflow-hidden p-6">
            
            {{-- ESTILOS PERSONALIZADOS PARA DATATABLES Y TEMA OSCURO (MONOCROMÁTICO) --}}
            <style>
                /* 1. Reset para el fondo del contenedor principal de la tabla */
                .bg-white {
                    background-color: #32373dff !important;
                    color: white !important;
                }

                /* 2. Estilos generales de DataTables (texto y wrapper) */
                .dataTables_wrapper {
                    color: white; 
                }
                .dataTables_wrapper table {
                    width: 100% !important;
                }
                .dataTables_wrapper label,
                .dataTables_wrapper .dataTables_info {
                    color: #ccc !important;
                }
                
                /* 3. Estilo para inputs (Search, Length) */
                .dataTables_wrapper input[type="search"], 
                .dataTables_wrapper select {
                    background-color: #4b5563 !important; /* Gris oscuro para inputs */
                    color: white !important;
                    border: 1px solid #6b7280 !important;
                    border-radius: 0.375rem !important;
                    padding: 0.5rem 0.75rem !important;
                    box-shadow: none !important;
                }
                
                /* 4. Estilo para BOTONES de DataTables (Exportar) */
                .dataTables_wrapper .dt-button {
                    /* Gris sólido para el botón de acción */
                    display: inline-flex !important;
                    align-items: center !important;
                    padding: 0.5rem 1rem !important;
                    background-color: #4b5563 !important; /* Gray-600 */
                    border: none !important;
                    border-radius: 0.5rem !important; /* rounded-lg */
                    font-weight: 600 !important;
                    font-size: 0.75rem !important;
                    color: white !important;
                    text-transform: uppercase !important;
                    letter-spacing: 0.05em !important;
                    transition: background-color 150ms ease-in-out !important;
                    margin-right: 0.5rem !important;
                    box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.3), 0 1px 2px -1px rgb(0 0 0 / 0.3) !important;
                }
                .dataTables_wrapper .dt-button:hover {
                    background-color: #374151 !important; /* Gray-700 hover */
                }

                /* 5. Paginación */
                .dataTables_wrapper .paginate_button {
                    color: white !important;
                    background-color: #374151 !important; /* gray-800 */
                    border: 1px solid #4b5563 !important; /* gray-700 */
                    margin-left: 2px;
                    margin-right: 2px;
                    border-radius: 0.375rem !important;
                }
                .dataTables_wrapper .paginate_button.current, 
                .dataTables_wrapper .paginate_button.current:hover {
                    /* Botón activo en tono de gris (Gray-600) para destacar */
                    background-color: #4b5563 !important;
                    border-color: #4b5563 !important;
                    color: white !important;
                }
                .dataTables_wrapper .paginate_button:hover:not(.current) {
                    background-color: #1f2937 !important; /* dark hover */
                }
                .dataTables_wrapper .dataTables_paginate {
                    padding-top: 1rem;
                }
                /* Espaciado de DataTables */
                #reservasTable_wrapper {
                    padding-top: 1rem;
                }
            </style>
            {{-- FIN DE ESTILOS PERSONALIZADOS --}}


            <div class="overflow-x-auto">
                {{-- Encabezado de la tabla en gris muy oscuro (casi negro) --}}
                <table id="reservasTable" class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-900 text-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Cancha & Deporte</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Horario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    {{-- Cuerpo de la tabla en gris oscuro --}}
                    <tbody class="bg-gray-800 divide-y divide-gray-700">
                        @forelse ($reservas as $reserva)
                        <tr class="hover:bg-gray-700 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">#{{ $reserva->id_reserva }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{-- Texto de cancha en color blanco/gris para ser neutral --}}
                                <div class="text-sm font-semibold text-gray-300">{{ $reserva->cancha->nombre ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-400">{{ $reserva->cancha->deporte->nombre ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $reserva->cancha->direccion->calle ?? 'Dirección No Disp.' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                <span class="font-bold block">{{ \Carbon\Carbon::parse($reserva->fecha_inicio)->format('d/M/Y') }}</span>
                                <span class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($reserva->fecha_inicio)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($reserva->fecha_fin)->format('H:i') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                <div class="font-medium">{{ $reserva->usuario->nombre ?? 'Usuario Eliminado' }}</div>
                                <div class="text-xs text-gray-500">{{ $reserva->usuario->email ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    // Mantenemos estos colores funcionales para el estado
                                    $colorMap = [
                                        'pendiente' => ['bg' => 'bg-yellow-800', 'text' => 'text-yellow-100'],
                                        'confirmada' => ['bg' => 'bg-green-800', 'text' => 'text-green-100'],
                                        'cancelada' => ['bg' => 'bg-red-800', 'text' => 'text-red-100'],
                                        'finalizada' => ['bg' => 'bg-gray-700', 'text' => 'text-gray-300'],
                                    ];
                                    $style = $colorMap[$reserva->estado] ?? $colorMap['finalizada'];
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $style['bg'] }} {{ $style['text'] }} capitalize">
                                    {{ $reserva->estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                {{-- Enlace de edición en color blanco/gris --}}
                                <a href="{{ route('admin.reservas.edit', $reserva->id_reserva) }}" class="text-white hover:text-gray-400 transition mr-3">Editar</a>
                                
                                {{-- Botón de eliminar en rojo (color funcional de peligro) --}}
                                <button type="button" 
                                        @click="targetUrl = '{{ route('admin.reservas.destroy', $reserva->id_reserva) }}'; open = true;"
                                        class="text-red-400 hover:text-red-200 transition">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-lg text-gray-400">No hay reservas registradas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Custom Confirmation Modal --}}
            <div x-show="open" 
                 class="fixed inset-0 bg-gray-900 bg-opacity-75 z-50 flex items-center justify-center transition-opacity duration-300"
                 x-cloak 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click.away="open = false">
                
                <div class="bg-gray-800 rounded-xl shadow-2xl p-6 w-full max-w-sm transform transition-all duration-300"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-90"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-90"
                     @click.stop>
                    
                    <h3 class="text-xl font-bold text-white mb-4">Confirmar Eliminación</h3>
                    <p class="text-gray-300 mb-6">¿Estás seguro de que deseas **eliminar** esta reserva? Esta acción es irreversible.</p>
                    
                    <div class="flex justify-end gap-3">
                        <button @click="open = false" 
                                type="button" 
                                class="px-4 py-2 border border-gray-600 rounded-lg text-gray-300 hover:bg-gray-700 transition">
                            Cancelar
                        </button>
                        
                        {{-- Formulario de eliminación real --}}
                        <form :action="targetUrl" method="POST" class="inline" id="deleteForm">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-600 rounded-lg text-white font-semibold hover:bg-red-700 transition shadow-md">
                                Sí, Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>