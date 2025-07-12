<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between flex-col gap-4 lg:flex-row">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Citas') }}
            </h2>
            <x-nav-link href="{{ route('cita.calendar') }}" class="bg-green-700 uppercase font-semibold text-white hover:text-white px-2 py-2 pt-2 rounded-md inline-block flex items-center">
                {{ __('Calendario de Citas') }}
            </x-nav-link>
            @can('crear citas')
            <x-nav-link href="{{ route('cita.create') }}" class="bg-blue-500 text-white hover:text-white hover:underline px-2 py-2 pt-2 rounded-md inline-block flex items-center">
                {{ __('Crear Nuevo') }}
            </x-nav-link>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>

            <div class="bg-white overflow-y-hidden shadow-xl sm:rounded-lg p-4 w-full overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-200">
                        <tr class="border-b">
                            <th class="p-5 text-left">Cliente</th>
                            <th class="p-5 text-left">Barbero</th>
                            <th class="p-5 text-left">Paquete</th>
                            <th class="p-5 text-left">Fecha y Hora</th>
                            @can('editar citas')
                            <th class="p-5 text-left">Operaciones</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($citas as $cita)
                        <tr>
                            <td class="p-5">{{ $cita->cliente->name.' '.$cita->cliente->primerApellido }}</td>
                            <td class="p-5">{{ $cita->barbero->name.' '.$cita->barbero->primerApellido }}</td>
                            <td class="p-5"> {{ $cita->paquete->nombre }} - ₡{{ number_format($cita->paquete->precio, 2) }}</td>
                            <td class="p-5">{{$cita->dia.' '.\Carbon\Carbon::parse($cita->fecha)->format('d M, Y').' '.\Carbon\Carbon::parse($cita->hora)->format('H:i')}}</td>
                            @can('editar citas')
                            <td class="p-5 gap-4 flex">
                                
                                <x-nav-link href="{{ route('cita.edit', $cita->id) }}" class="bg-gray-700 text-white hover:text-white hover:underline px-2 py-2 pt-2 rounded-md inline-block flex items-center !text-base">
                                    {{ __('Editar') }}
                                </x-nav-link>
                                @endcan
                                @can('borrar citas')
                                <form action="{{ route('cita.destroy', $cita->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <x-button class="bg-red-600 hover:bg-red-600 hover:text-white hover:underline !px-2 py-2 capitalize !text-base">
                                        {{ __('Borrar') }}
                                    </x-button>
                                </form>
                                
                            </td>
                            @endcan
                        </tr>
                         @endforeach
                    </tbody>
                </table>
                {{ $citas->links() }}
            </div>
        </div>
    </div>
</x-app-layout>