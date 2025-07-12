<x-app-layout>
    <x-slot name="header">
         <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Paquetes | Editar') }}
            </h2>
            @can('ver paquetes')
            <x-nav-link href="{{ route('paquete.index') }}" class="bg-gray-700 text-white hover:text-white px-2 py-2 pt-2 rounded-md inline-block flex items-center">
                {{ __('Volver a Paquetes') }}
            </x-nav-link>
            @endcan
         </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                <!-- <x-welcome /> -->
                 <form action="{{ route('paquete.update', $paquete->id) }}" method="POST">
                    @csrf
                    <div>
                        <label for="nombre" class="text-lg font-medium">Nombre</label>
                        <div class="my-3">
                            <input value="{{ old('nombre', $paquete->nombre) }}" placeholder="Ingrese el nombre del paquete" name="nombre" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg" required>
                            @error('nombre')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="descripcion" class="text-lg font-medium">Descripcion</label>
                        <div class="my-3">
                            <input value="{{ old('descripcion', $paquete->descripcion) }}" placeholder="Ingrese la descrpcion del paquete" name="descripcion" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                            @error('descripcion')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="precio" class="text-lg font-medium">Precio</label>
                        <div class="my-3">
                            <input value="{{ old('precio', $paquete->precio) }}" placeholder="Ingrese el precio del paquete en colones" name="precio" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg " required>
                            @error('precio')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <x-button >
                            {{ __('Guardar') }}
                        </x-button>
                 </form>
            </div>
        </div>
    </div>
</x-app-layout>