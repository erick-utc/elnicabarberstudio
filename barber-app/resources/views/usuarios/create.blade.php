<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Usuarios | Registrar Nuevo') }}
            </h2>
            <x-nav-link href="{{ route('usuario.index') }}" class="bg-gray-700 text-white hover:text-white px-2 py-2 pt-2 rounded-md inline-block flex items-center">
                {{ __('Volver a Usuarios') }}
            </x-nav-link>
        </div>
    </x-slot>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('usuario.store') }}" class="w-full grid md:grid-cols-2 gap-4">
            @csrf

            <div class="w-full mt-4 pr-4">
                <x-label for="name" value="{{ __('Nombre') }}" />
                <x-input id="name" class="border-gray-300 shadow-sm w-full rounded-lg mt-3" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            {{-- <div class="w-full mt-4 pr-4">
                <x-label for="nombre" value="{{ __('Nombre') }}" />
                <x-input id="nombre" class="border-gray-300 shadow-sm w-full rounded-lg mt-3" type="text" name="nombre" :value="old('name')" required autofocus autocomplete="nombre" />
            </div> --}}

            <div class="w-full mt-4 pr-4">
                <x-label for="primerApellido" value="{{ __('Primer Apellido') }}" />
                <x-input id="primerApellido" class="border-gray-300 shadow-sm w-full rounded-lg mt-3" type="text" name="primerApellido" :value="old('primerApellido')" required autofocus autocomplete="primerApellido" />
            </div>

            <div class="w-full mt-4 pr-4">
                <x-label for="segundoApellido" value="{{ __('Segundo Apellido') }}" />
                <x-input id="segundoApellido" class="border-gray-300 shadow-sm w-full rounded-lg mt-3" type="text" name="segundoApellido" :value="old('segundoApellido')" required autofocus autocomplete="segundoApellido" />
            </div>

            {{-- <div class="w-full mt-4 pr-4">
                <x-label for="correo" value="{{ __('Correo') }}" />
                <x-input id="correo" class="border-gray-300 shadow-sm w-full rounded-lg mt-3" type="text" name="correo" :value="old('correo')" required autofocus autocomplete="correo" />
            </div> --}}

            <div class="w-full mt-4 pr-4">
                <x-label for="email" value="{{ __('Correo') }}" />
                <x-input id="email" class="border-gray-300 shadow-sm w-full rounded-lg mt-3" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="w-full mt-4 pr-4">
                <x-label for="telefono" value="{{ __('Teléfono') }}" />
                <x-input id="telefono" class="border-gray-300 shadow-sm w-full rounded-lg mt-3" type="tel" name="telefono" :value="old('telefono')" required autocomplete="telefono" />
            </div>

            <div class="w-full mt-4 pr-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="border-gray-300 shadow-sm w-full rounded-lg mt-3" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="w-full mt-4 pr-4">
                <x-label for="password_confirmation" value="{{ __('Confirmar Password') }}" />
                <x-input id="password_confirmation" class="border-gray-300 shadow-sm w-full rounded-lg mt-3" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <input type="hidden" name="desabilitado" value="0" />
            
            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="w-full mt-4 pr-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ms-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-between w-full mt-4 pr-4">
                {{-- <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Ya estoy registrado') }}
                </a> --}}

                <x-button >
                    {{ __('Registrar ') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-app-layout>
