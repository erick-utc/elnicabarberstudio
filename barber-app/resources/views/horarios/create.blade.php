<x-app-layout>
    <x-slot name="header">
         <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Horarios | Crear') }}
            </h2>
            @can('ver horarios')
            <x-nav-link href="{{ route('horario.index') }}" class="bg-gray-700 text-white hover:text-white px-2 py-2 pt-2 rounded-md inline-block flex items-center">
                {{ __('Volver a Horarios') }}
            </x-nav-link>
            @endcan
         </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                <!-- <x-welcome /> -->
                 <form action="{{ route('horario.store') }}" method="POST">
                    @csrf
                    <div>
                        {{-- <label for="name" class="text-lg font-medium">Nombre</label>
                        <div class="my-3">
                            <input value="{{ old('name') }}" placeholder="Ingrese el nombre del horario" name="name" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                            @error('name')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div> --}}
                        <label for="user_id" class="text-lg font-medium">Barbero</label>
                        <div class="my-3">
                            {{-- <input value="{{ old('name') }}" placeholder="Ingrese el nombre del horario" name="user_id" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg"> --}}
                            <select name="user_id" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @foreach ($usuarios as $user)
                                <option value="{{ $user->id }}" @selected(($user->name == Auth::user()->name)&&($user->primerApellido == Auth::user()->primerApellido)&&($user->segundoApellido == Auth::user()->segundoApellido))>{{ $user->name.' '.$user->primerApellido.' '.$user->segundoApellido }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <label for="dias" class="text-lg font-medium">Días de la semana</label>
                        <div class="grid grid-cols-4 mb-3">
                            @foreach (App\Enums\DiasSemana::cases() as $dia)
                                <div class="mt-3">
                                    <x-checkbox type="checkbox" id="{{ $dia->name }}" name="dias[]" value="{{ $dia->name }}" />
                                    <x-label for="{{ $dia->name }}" class="inline-block">{{ $dia->value }}</x-label>
                                </div>
                            @endforeach
                            
                        </div>
                        @error('dias')
                            <p class="text-red-600">{{ $message }}</p>
                        @enderror
                        <label for="inicio" class="text-lg font-medium">Hora de Inicio</label>
                        <div class="my-3">
                            <input value="{{ old('inicio') }}" placeholder="Ingrese la Hora de Entrada" name="inicio" type="time" class="border-gray-300 shadow-sm w-1/2 rounded-lg"  required>
                            @error('name')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <label for="fin" class="text-lg font-medium">Hora de Salida</label>
                        <div class="my-3">
                            <input value="{{ old('fin') }}" placeholder="Ingrese la Hora de Salida" name="fin" type="time" class="border-gray-300 shadow-sm w-1/2 rounded-lg" required>
                            @error('name')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <x-button >
                            {{ __('Crear') }}
                        </x-button>
                    </div>
                 </form>
            </div>
        </div>
    </div>
</x-app-layout>