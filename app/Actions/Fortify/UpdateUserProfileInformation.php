<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'nombre' => ['required', 'string', 'max:100'],
            'apellidos' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique('users')->ignore($user->id)],
            'telefono' => ['nullable', 'string', 'max:20'],
        ])->validateWithBag('updateProfileInformation');

        $user->forceFill([
            'nombre' => $input['nombre'],
            'apellidos' => $input['apellidos'] ?? $user->apellidos,
            'email' => $input['email'],
            'telefono' => $input['telefono'] ?? $user->telefono,
        ])->save();
    }
}