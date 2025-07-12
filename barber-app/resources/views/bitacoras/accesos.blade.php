<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Bitacora de Accesos') }}
            </h2>
            {{--<x-search model="permission" value="{{ $search }}"/>
             <x-nav-link href="{{ route('permission.create') }}" class="bg-blue-500 text-white hover:text-white hover:underline px-2 py-2 pt-2 rounded-md inline-block flex items-center">
                {{ __('Crear Nuevo') }}
            </x-nav-link> --}}
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4 w-full">
                <table class="w-full">
                    <thead class="bg-gray-200">
                        <tr class="border-b">
                            <th class="p-5 text-left">Usuario</th>
                            <th class="p-5 text-left">Rol</th>
                            <th class="p-5 text-left">Tipo</th>
                            <th class="p-5 text-left">IP</th>
                            <th class="p-5 text-left">Navegador</th>
                            <th class="p-5 text-left">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bitacora as $log)
                        <tr>
                            <td class="p-5">{{ $log->user->correo }}</td>
                            <td class="p-5">{{ implode(', ', $log->user->getRoleNames()->toArray()) }}</td>
                            <td class="p-5">{{ strtoupper($log->tipo) }}</td>
                            <td class="p-5">{{ $log->ip }}</td>
                            <td class="p-5">{{ $log->navegador }}</td>
                            <td class="p-5">{{\Carbon\Carbon::parse($log->fecha)->format('d M, Y')}}</td>
                            
                            {{-- <td class="p-5">
                                <x-nav-link href="{{ route('permission.edit', $permiso->id) }}" class="bg-gray-700 text-white hover:text-white hover:underline px-2 py-2 pt-2 rounded-md inline-block flex items-center !text-base">
                                    {{ __('Editar') }}
                                </x-nav-link>
                                <form action="{{ route('permission.destroy', $permiso->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <x-button class="bg-red-600 hover:bg-red-600 hover:text-white hover:underline !px-2 py-2 capitalize !text-base">
                                        {{ __('Borrar') }}
                                    </x-button>
                                </form>
                            </td> --}}
                        </tr>
                         @endforeach
                    </tbody>
                </table>
                {{ $bitacora->links() }}
            </div>
        </div>
    </div>
</x-app-layout>