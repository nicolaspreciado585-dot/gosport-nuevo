<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            'nombre' => ['required', 'string', 'max:100'],
            'apellidos' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'tipo_documento' => ['nullable', 'string', 'max:20'],
            'numero_identificacion' => ['nullable', 'string', 'max:50'],
            'genero' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->validate();

        return User::create([
            'nombre' => $input['nombre'],
            'apellidos' => $input['apellidos'] ?? null,
            'email' => $input['email'],
            'telefono' => $input['telefono'] ?? null,
            'tipo_documento' => $input['tipo_documento'] ?? null,
            'numero_identificacion' => $input['numero_identificacion'] ?? null,
            'genero' => $input['genero'] ?? null,
            'password' => Hash::make($input['password']),
            'id_rol' => 1, // Por defecto es jugador
        ]);
    }
}