<x-app-layout>
    <x-slot name="header">
         <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Roles | Editar') }}
            </h2>
            <x-nav-link href="{{ route('role.index') }}" class="bg-gray-700 text-white hover:text-white px-2 py-2 pt-2 rounded-md inline-block flex items-center">
                {{ __('Volver a Roles') }}
            </x-nav-link>
         </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                <!-- <x-welcome /> -->
                 <form action="{{ route('role.update', $role->id) }}" method="POST">
                    @csrf
                    <div>
                        <label for="name" class="text-lg font-medium">Nombre</label>
                        <div class="my-3">
                            <input value="{{ old('name', $role->name) }}" placeholder="Ingrese el nombre del role" name="name" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                            @error('name')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid grid-cols-4 mb-3">
                            @foreach ($permisos as $permiso)
                                <div class="mt-3">
                                    <input type="checkbox" {{ $hasPermisos->contains($permiso->name) ? 'checked':'' }} id="permiso-{{ $permiso->id }}" name="permisos[]" value="{{ $permiso->name }}"  class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                                    <x-label for="permiso-{{ $permiso->id }}" class="inline-block">{{ $permiso->name }}</x-label>
                                </div>
                            @endforeach
                        </div>
                        <x-button >
                            {{ __('Guardar') }}
                        </x-button>
                    </div>
                 </form>
            </div>
        </div>
    </div>
</x-app-layout>