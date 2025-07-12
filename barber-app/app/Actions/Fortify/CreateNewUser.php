<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
            'primerApellido' => ['string', 'max:255'],
            'segundoApellido' => ['string', 'max:255'],
            // 'correo' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'telefono' => ['required', 'string', 'numeric', 'unique:users'],
            'desabilitado' => '',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'nombre' => $input['name'],
            'primerApellido' => $input['primerApellido'],
            'segundoApellido' => $input['segundoApellido'],
            'correo' => $input['email'],
            'telefono' => $input['telefono'],
            'desabilitado' => $input['desabilitado'],
        ]);

        $user->syncRoles(['cliente']);

        return $user;
    }
}
