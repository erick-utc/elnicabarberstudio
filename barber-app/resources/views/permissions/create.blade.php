<x-app-layout>
    <x-slot name="header">
         <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Permisos | Crear') }}
            </h2>
            @can('ver permisos')
            <x-nav-link href="{{ route('permission.index') }}" class="bg-gray-700 text-white hover:text-white px-2 py-2 pt-2 rounded-md inline-block flex items-center">
                {{ __('Volver a Permisos') }}
            </x-nav-link>
            @endcan
         </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                <!-- <x-welcome /> -->
                 <form action="{{ route('permission.store') }}" method="POST">
                    @csrf
                    <div>
                        <label for="name" class="text-lg font-medium">Nombre</label>
                        <div class="my-3">
                            <input value="{{ old('name') }}" placeholder="Ingrese el nombre del permiso" name="name" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
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