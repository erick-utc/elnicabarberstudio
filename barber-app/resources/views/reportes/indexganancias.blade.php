<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Report de Paquetes') }}
            </h2>
            {{-- <x-search model="role" value="{{ $search }}" /> --}}
            <x-nav-link href="{{ route('reporte.index') }}" class="bg-blue-500 text-white hover:text-white hover:underline px-2 py-2 pt-2 rounded-md inline-block flex items-center">
                {{ __('Volver a Reportes') }}
            </x-nav-link> 
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4 w-full">
                <div>
                    <form action="{{ route('reporte.gananciasBarberos') }}" method="post">
                        @csrf

                        <label for="inicio" class="text-lg font-medium">Desde</label>
                        <div class="my-3">
                            <input value="{{ old('inicio', $inicio) }}"name="inicio" type="date" class="border-gray-300 shadow-sm w-1/2 rounded-lg"  required>
                            @error('inicio')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <label for="fin" class="text-lg font-medium">Hasta</label>
                        <div class="my-3">
                            <input value="{{ old('fin', $fin) }}" name="fin" type="date" class="border-gray-300 shadow-sm w-1/2 rounded-lg"  required>
                            @error('fin')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <label for="porcentaje" class="text-lg font-medium">Porcentaje</label>
                        <div class="my-3">
                            <input value="{{ old('porcentaje', ) }}" placeholder="Porcentaje" name="porcentaje" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                            @error('porcentaje')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <x-button >
                            {{ __('Crear reporte') }}
                        </x-button>
                    </form>
                </div>
                @if (empty($resultados))
                <div class="w-full text-lg my-8">No hay Datos para los dias seleccionados</div> 
                @else
                <table class="w-full my-8">
                    <thead class="bg-gray-200">
                        <tr class="border-b">
                            <th class="p-5 text-left">#</th>
                            <th class="p-5 text-left">Babero</th>
                            <th class="p-5 text-left">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resultados as $resultado)
                        <tr>
                            <td class="p-5">{{$resultado->barbero_id}}</td>
                            <td class="p-5">{{$resultado->barbero->nombre}}</td>
                            <td class="p-5">{{$resultado->total_ganancia}}</td>
                        </tr>
                         @endforeach
                    </tbody>
                </table>
                @endif
                {{-- {{ $roles->links() }} --}}
            </div>
        </div>
    </div>
</x-app-layout>