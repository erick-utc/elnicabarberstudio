<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Reportes') }}
            </h2>
            {{-- <x-search model="role" value="{{ $search }}" />
            <x-nav-link href="{{ route('role.create') }}" class="bg-blue-500 text-white hover:text-white hover:underline px-2 py-2 pt-2 rounded-md inline-block flex items-center">
                {{ __('Crear Nuevo') }}
            </x-nav-link> --}}
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4 w-full grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- <table class="w-full">
                    <thead class="bg-gray-200">
                        <tr class="border-b">
                            <th class="p-5 text-left">#</th>
                            <th class="p-5 text-left">Nombre</th>
                            <th class="p-5 text-left">Permisos</th>
                            <th class="p-5 text-left">Creado</th>
                            <th class="p-5 text-left">Operaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                        <tr>
                            <td class="p-5">{{$role->id}}</td>
                            <td class="p-5">{{$role->name}}</td>
                            <td class="p-5">
                               {{ $role->permissions->pluck('name')->implode(', '); }}
                            </td>
                            <td class="p-5">{{\Carbon\Carbon::parse($role->created_at)->format('d M, Y')}}</td>
                            <td class="p-5">
                                <x-nav-link href="{{ route('role.edit', $role->id) }}" class="bg-gray-700 text-white hover:text-white hover:underline px-2 py-2 pt-2 rounded-md inline-block flex items-center !text-base">
                                    {{ __('Editar') }}
                                </x-nav-link>
                                <form action="{{ route('role.destroy', $role->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <x-button class="bg-red-600 hover:bg-red-600 hover:text-white hover:underline !px-2 py-2 capitalize !text-base">
                                        {{ __('Borrar') }}
                                    </x-button>
                                </form>
                            </td>
                        </tr>
                         @endforeach
                    </tbody>
                </table>
                {{ $roles->links() }} --}}
                 {{-- <x-nav-link href="{{ route('reporte.indexpaquetes') }}" >Paquetes</x-nav-link>
                 <x-nav-link href="{{ route('reporte.indexganancias') }}" >Ganancias</x-nav-link> --}}
                 <x-simple-card titulo="Paquetes" descripcion="Reporta el total de reportes en un rango de fechas" url="{{ route('reporte.indexpaquetes') }}"/>
                 <x-simple-card titulo="Ganancias" descripcion="Reporta la ganacia de los barberos en un rango de fechas" url="{{ route('reporte.indexganancias') }}" />
            </div>
        </div>
    </div>
</x-app-layout>