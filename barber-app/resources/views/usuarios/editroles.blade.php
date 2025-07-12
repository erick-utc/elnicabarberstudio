<x-app-layout>
    <x-slot name="header">
         <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Usuarios | Editar Roles') }}
            </h2>
            <x-nav-link href="{{ route('usuario.index') }}" class="bg-gray-700 text-white hover:text-white px-2 py-2 pt-2 rounded-md inline-block flex items-center">
                {{ __('Volver a Usuarios') }}
            </x-nav-link>
         </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                <!-- <x-welcome /> -->
                 <form action="{{ route('usuario.updateroles', $usuario->id) }}" method="POST">
                    @csrf
                    <div>
                        <label for="name" class="text-lg font-medium">Nombre</label>
                        <div class="my-3">
                            <input value="{{ old('name', $usuario->name) }}" placeholder="Ingrese el nombre del usuario" name="name" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                            @error('name')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid grid-cols-4 mb-3">
                            @foreach ($roles as $role)
                                <div class="mt-3">
                                    <input {{ $hasRoles->contains($role->name) ? 'checked':'' }} type="checkbox"  id="role-{{ $role->id }}" name="role[]" value="{{ $role->name }}"  class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                                    <x-label for="role-{{ $role->id }}" class="inline-block">{{ $role->name }}</x-label>
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