<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $evento->nombre }}
            </h2>
            <a href="{{ route('admin.eventos.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150">
                {{ __('Volver') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-6 bg-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                <!-- Foto del evento -->
                @if($evento->foto)
                    <img src="{{ asset('storage/' . $evento->foto) }}" alt="{{ $evento->nombre }}" 
                          class="w-full h-80 object-cover">
                @else
                    <div class="w-full h-80 bg-gray-200 flex items-center justify-center">
                        <p class="text-gray-500 text-lg font-medium">Sin foto</p>
                    </div>
                @endif

                <!-- Contenido -->
                <div class="p-8">
                    <!-- Tarjetas de estado -->
                    <div class="grid md:grid-cols-3 gap-4 mb-8">
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
                            <p class="text-sm text-gray-600">Estado</p>
                            <p class="text-2xl font-extrabold text-blue-700 mt-1">{{ ucfirst(str_replace('_', ' ', $evento->estado ?? 'N/A')) }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-xl border border-green-200">
                            <p class="text-sm text-gray-600">Participantes</p>
                            <p class="text-2xl font-extrabold text-green-700 mt-1">{{ $evento->contarParticipantes() ?? 0 }}/{{ $evento->capacidad_participantes ?? 0 }}</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                            <p class="text-sm text-gray-600">Lugares Disponibles</p>
                            <p class="text-2xl font-extrabold text-yellow-700 mt-1">{{ $evento->lugaresDisponibles() ?? 0 }}</p>
                        </div>
                    </div>

                    <!-- Información general -->
                    <div class="grid md:grid-cols-2 gap-8 mb-8">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-1">Descripción</h3>
                            <p class="text-gray-600 text-base leading-relaxed">{{ $evento->descripcion ?? 'Sin descripción detallada.' }}</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-1">Detalles del Evento</h3>
                            <ul class="space-y-3 text-sm text-gray-700">
                                <li class="flex items-center gap-2"><span class="font-bold w-24">Deporte:</span> <span>{{ $evento->deporte->nombre ?? 'N/A' }}</span></li>
                                <li class="flex items-center gap-2"><span class="font-bold w-24">Cancha:</span> <span>{{ $evento->cancha->nombre ?? 'N/A' }}</span></li>
                                <li class="flex items-center gap-2"><span class="font-bold w-24">Ubicación:</span> <span>{{ $evento->ubicacion ?? 'No especificada' }}</span></li>
                                <li class="flex items-center gap-2"><span class="font-bold w-24">Precio:</span> <span class="text-green-600 font-bold">${{ number_format($evento->precio_inscripcion ?? 0, 2) }}</span></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Fechas -->
                    <div class="grid md:grid-cols-2 gap-8 mb-8">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-1">Fecha de Inicio</h3>
                            <p class="text-xl text-gray-900 font-extrabold">
                                {{ optional($evento->fecha_inicio)->format('d/m/Y H:i') ?? 'No definida' }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-1">Fecha de Fin</h3>
                            <p class="text-xl text-gray-900 font-extrabold">
                                {{ optional($evento->fecha_fin)->format('d/m/Y H:i') ?? 'No definida' }}
                            </p>
                        </div>
                    </div>

                    <!-- Administrador -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-1">Administrador del Evento</h3>
                        <p class="text-gray-600 text-base font-medium">{{ $evento->admin->nombre ?? 'N/A' }}</p>
                    </div>

                    <!-- Tabla de participantes -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-700 mb-4">Participantes Inscritos ({{ $evento->contarParticipantes() ?? 0 }})</h3>
                        @if($evento->participante_eventos && $evento->participante_eventos->count() > 0)
                            <div class="overflow-x-auto shadow-md rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Nombre</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Email</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Teléfono</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Documento</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($evento->participante_eventos as $participante)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $participante->usuario->nombre ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                    {{ $participante->usuario->email ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                    {{ $participante->usuario->telefono ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                    {{ $participante->usuario->numero_identificacion ?? 'N/A' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 bg-gray-50 p-4 rounded-lg border border-gray-200">No hay participantes inscritos aún. ¡Anima a más usuarios a unirse!</p>
                        @endif
                    </div>

                    <!-- Acciones -->
                    <div class="flex gap-3 pt-4 border-t">
                        <a href="{{ route('admin.eventos.edit', $evento) }}" 
                           class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition font-semibold inline-flex items-center gap-2 shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Editar
                        </a>
                        
                        <!-- Formulario de eliminación oculto -->
                        <form id="delete-event-form" action="{{ route('admin.eventos.destroy', $evento) }}" method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        
                        <!-- Botón para abrir el modal -->
                        <button type="button" onclick="showDeleteModal()"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold inline-flex items-center gap-2 shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-75 hidden items-center justify-center z-50 transition-opacity duration-300" 
         aria-modal="true" role="dialog" aria-labelledby="modal-title">
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden max-w-lg w-full m-4 transform transition-all sm:my-8 sm:align-middle">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-100 rounded-full p-3">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.398 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                            Confirmar Eliminación
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                ¿Estás absolutamente seguro de que deseas eliminar el evento **"{{ $evento->nombre }}"**? Esta acción es irreversible y se perderá toda la información asociada.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                <button type="button" onclick="hideDeleteModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-medium transition duration-150 ease-in-out">
                    Cancelar
                </button>
                <button type="button" onclick="confirmDelete()"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition duration-150 ease-in-out">
                    Sí, Eliminar Evento
                </button>
            </div>
        </div>
    </div>

    <script>
        function showDeleteModal() {
            const modal = document.getElementById('delete-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.querySelector('#delete-modal button:first-child').focus();
        }

        function hideDeleteModal() {
            const modal = document.getElementById('delete-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function confirmDelete() {
            document.getElementById('delete-event-form').submit();
        }
    </script>
</x-app-layout>
