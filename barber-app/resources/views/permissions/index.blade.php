<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between gap-4 flex-col lg:flex-row">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Permisos') }}
            </h2>
            <x-search model="permission" value="{{ $search }}"/>
            @can('crear permisos')
            <x-nav-link href="{{ route('permission.create') }}" class="bg-blue-500 text-white hover:text-white hover:underline px-2 py-2 pt-2 rounded-md inline-block flex items-center">
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
                            <th class="p-5 text-left">Nombre</th>
                            <th class="p-5 text-left">Creado</th>
                            <th class="p-5 text-left">Operaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permisos as $permiso)
                        <tr>
                            <td class="p-5">{{$permiso->name}}</td>
                            <td class="p-5">{{\Carbon\Carbon::parse($permiso->created_at)->format('d M, Y')}}</td>
                            <td class="p-5 flex gap-4">
                                @can('editar permisos')
                                <x-nav-link href="{{ route('permission.edit', $permiso->id) }}" class="bg-gray-700 text-white hover:text-white hover:underline px-2 py-2 pt-2 rounded-md inline-block flex items-center !text-base">
                                    {{ __('Editar') }}
                                </x-nav-link>
                                @endcan
                                @can('borrar permisos')
                                <form action="{{ route('permission.destroy', $permiso->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <x-button class="bg-red-600 hover:bg-red-600 hover:text-white hover:underline !px-2 py-2 capitalize !text-base">
                                        {{ __('Borrar') }}
                                    </x-button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                         @endforeach
                    </tbody>
                </table>
                {{ $permisos->links() }}
            </div>
        </div>
    </div>
</x-app-layout>