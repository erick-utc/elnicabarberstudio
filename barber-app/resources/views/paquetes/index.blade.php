<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Paquetes') }}
            </h2>
            <x-search model="paquete" value="{{ $search }}" />
            @can('crear paquetes')
            <x-nav-link href="{{ route('paquete.create') }}" class="bg-blue-500 text-white hover:text-white hover:underline px-2 py-2 pt-2 rounded-md inline-block flex items-center">
                {{ __('Crear Nuevo') }}
            </x-nav-link>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4 w-full">
                <table class="w-full">
                    <thead class="bg-gray-200">
                        <tr class="border-b">
                            <th class="p-5 text-left">Nombre</th>
                            <th class="p-5 text-left">Descripcion</th>
                            <th class="p-5 text-left">Precio</th>
                            {{-- <th class="p-5 text-left">Creado</th> --}}
                            @can('editar paquetes')
                            <th class="p-5 text-left">Operaciones</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paquetes as $paquete)
                        <tr>
                            <td class="p-5">{{$paquete->nombre}}</td>
                            <td class="p-5">{{ $paquete->descripcion }}</td>
                            @if($paquete->precio == 0)
                            <td class="p-5">{{ 'Consultar por precio' }}</td>
                            @endif
                            @if($paquete->precio != 0)
                            <td class="p-5">{{ $paquete->precio_colones }}</td>
                            @endif
                            {{-- <td class="p-5">{{\Carbon\Carbon::parse($paquete->created_at)->format('d M, Y')}}</td> --}}
                            @can('editar paquetes')
                            <td class="p-5">
                                <x-nav-link href="{{ route('paquete.edit', $paquete->id) }}" class="bg-gray-700 text-white hover:text-white hover:underline px-2 py-2 pt-2 rounded-md inline-block flex items-center !text-base">
                                    {{ __('Editar') }}
                                </x-nav-link>
                                @endcan
                                @can('borrar paquetes')
                                <form action="{{ route('paquete.destroy', $paquete->id) }}" method="POST" class="inline-block">
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
                {{ $paquetes->links() }}
            </div>
        </div>
    </div>
</x-app-layout>