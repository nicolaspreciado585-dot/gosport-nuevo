<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nombre -->
        <div class="mb-3">
            <x-input-label for="nombre" value="Nombre" />
            <x-text-input id="nombre"
                          type="text"
                          name="nombre"
                          :value="old('nombre')"
                          required
                          autofocus
                          autocomplete="given-name" />
            <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
        </div>

        <!-- Apellidos -->
        <div class="mb-3">
            <x-input-label for="apellidos" value="Apellidos" />
            <x-text-input id="apellidos"
                          type="text"
                          name="apellidos"
                          :value="old('apellidos')"
                          autocomplete="family-name" />
            <x-input-error :messages="$errors->get('apellidos')" class="mt-2" />
        </div>

        <!-- Correo electrónico -->
        <div class="mb-3">
            <x-input-label for="email" value="Correo Electrónico" />
            <x-text-input id="email"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required
                          autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Teléfono -->
        <div class="mb-3">
            <x-input-label for="telefono" value="Teléfono" />
            <x-text-input id="telefono"
                          type="text"
                          name="telefono"
                          :value="old('telefono')"
                          maxlength="20"
                          autocomplete="tel" />
            <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
        </div>

        <!-- Tipo de documento -->
        <div class="mb-3">
            <x-input-label for="tipo_documento" value="Tipo de Documento" />
            <select id="tipo_documento" name="tipo_documento" class="form-select w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="">Seleccione...</option>
                <option value="CC" {{ old('tipo_documento') == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
                <option value="TI" {{ old('tipo_documento') == 'TI' ? 'selected' : '' }}>Tarjeta de Identidad</option>
                <option value="CE" {{ old('tipo_documento') == 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
            </select>
            <x-input-error :messages="$errors->get('tipo_documento')" class="mt-2" />
        </div>

        <!-- Número de identificación -->
        <div class="mb-3">
            <x-input-label for="numero_identificacion" value="Número de Identificación" />
            <x-text-input id="numero_identificacion"
                          type="text"
                          name="numero_identificacion"
                          :value="old('numero_identificacion')"
                          maxlength="50"
                          autocomplete="off" />
            <x-input-error :messages="$errors->get('numero_identificacion')" class="mt-2" />
        </div>

        <!-- Género -->
        <div class="mb-3">
            <x-input-label for="genero" value="Género" />
            <select id="genero" name="genero" class="form-select w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="">Seleccione...</option>
                <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                <option value="Otro" {{ old('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
            </select>
            <x-input-error :messages="$errors->get('genero')" class="mt-2" />
        </div>

        <!-- Contraseña -->
        <div class="mb-3">
            <x-input-label for="password" value="Contraseña" />
            <x-text-input id="password"
                          type="password"
                          name="password"
                          required
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirmar contraseña -->
        <div class="mb-4">
            <x-input-label for="password_confirmation" value="Confirmar Contraseña" />
            <x-text-input id="password_confirmation"
                          type="password"
                          name="password_confirmation"
                          required
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Enlace y botón -->
        <div class="d-flex justify-content-end align-items-center">
            <a class="text-white-50 text-decoration-none me-3" href="{{ route('login') }}">
                ¿Ya tienes cuenta?
            </a>

            <x-primary-button>
                Registrarse
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
