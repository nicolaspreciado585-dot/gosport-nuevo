<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Confirmación de Reserva') }}
        </h2>
    </x-slot>

    <div class="py-12 px-6 bg-gray-100 min-h-screen">
        <div class="max-w-xl mx-auto bg-white p-8 rounded-2xl shadow-xl text-center border-t-4 border-indigo-500 animate-fadeIn">

            <svg class="mx-auto h-16 w-16 text-indigo-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>

            @if (session('ok'))
                <h3 class="text-3xl font-bold text-gray-900 mt-2 mb-3">¡Reserva Exitosa!</h3>
                <p class="text-lg text-gray-600 mb-6">{{ session('ok') }}</p>
            @else
                <h3 class="text-3xl font-bold text-gray-900 mt-2 mb-3">Reserva Registrada</h3>
                <p class="text-lg text-gray-600 mb-6">Tu reserva ha sido procesada. Revisa tu historial para más detalles.</p>
            @endif
            
            <a href="{{ route('dashboard') }}" 
               class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700 transition duration-150 font-semibold shadow-lg">
                Volver al Dashboard
            </a>
            
        </div>
    </div>

    <style>
        @keyframes fadeIn { 0% { opacity: 0; transform: translateY(20px);} 100% {opacity:1; transform: translateY(0);} }
        .animate-fadeIn { animation: fadeIn 0.8s ease-out forwards; }
    </style>
</x-app-layout>

