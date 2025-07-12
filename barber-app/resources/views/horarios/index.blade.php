<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between flex-col gap-4 lg:flex-row">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Horarios') }}
            </h2>
            <x-nav-link href="{{ route('horario.calendar') }}" class="bg-green-700 uppercase font-semibold text-white hover:text-white px-2 py-2 pt-2 rounded-md inline-block flex items-center">
                {{ __('Calendario de Horarios') }}
            </x-nav-link>
            @can('crear horarios')
            <x-nav-link href="{{ route('horario.create') }}" class="bg-blue-500 text-white hover:text-white hover:underline px-2 py-2 pt-2 rounded-md inline-block flex items-center">
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
                            <th class="p-5 text-left">Barbero</th>
                            <th class="p-5 text-left">Dias</th>
                            <th class="p-5 text-left">Hora de Inicio</th>
                            <th class="p-5 text-left">Hora de Salida</th>
                            @can('editar horarios')
                            <th class="p-5 text-left">Operaciones</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($horarios as $horario)
                        <tr>
                            <td class="p-5 text-left">{{$horario->user->nombre.' '.$horario->user->primerApellido.' '.$horario->user->segundoApellido}}</td>
                            <td class="p-5 text-left">
                                @foreach($horario->dias as $day)
                                    {{ ucfirst($day) }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </td>
                            <td class="p-5 text-left">{{ $horario->inicio }}</td>
                            <td class="p-5 text-left">{{ $horario->fin }}</td>
                            {{-- <td class="p-5">{{\Carbon\Carbon::parse($horario->created_at)->format('d M, Y')}}</td> --}}
                            @can('editar horarios')
                            <td class="p-5 flex gap-4">
                                
                                <x-nav-link href="{{ route('horario.edit', $horario->id) }}" class="bg-gray-700 text-white hover:text-white hover:underline px-2 py-2 pt-2 rounded-md inline-block flex items-center !text-base">
                                    {{ __('Editar') }}
                                </x-nav-link>
                                @endcan
                                @can('borrar horarios')
                                <form action="{{ route('horario.destroy', $horario->id) }}" method="POST" class="inline-block">
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
                {{ $horarios->links() }}
            </div>
        </div>
    </div>
</x-app-layout>