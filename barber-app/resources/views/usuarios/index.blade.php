<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between flex-col gap-4 lg:flex-row">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Usuarios') }}
            </h2>
            <x-search model="usuario" value="{{ $search }}" />
            @can('crear usuarios')
            <x-nav-link href="{{ route('usuario.create') }}" class="bg-blue-500 text-white hover:text-white hover:underline px-2 py-2 pt-2 rounded-md inline-block flex items-center">
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
                            <th class="p-5 text-left">Roles</th>
                            {{-- <th class="p-5 text-left">Creado</th> --}}
                            @can('editar usuarios')
                            <th class="p-5 text-left">Operaciones</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usuarios as $usuario)
                        <tr>
                            <td class="p-5">{{$usuario->name.' '.$usuario->primerApellido.' '.$usuario->segundoApellido}}</td>
                            <td class="p-5">
                               {{ $usuario->roles->pluck('name')->implode(', '); }}
                            </td>
                            {{-- <td class="p-5">{{\Carbon\Carbon::parse($usuario->created_at)->format('d M, Y')}}</td> --}}
                            @can('editar usuarios')
                            <td class="p-5 flex items-center justify-between gap-4">
                                @role('admin')
                                <x-nav-link href="{{ route('usuario.editroles', $usuario->id) }}" class="bg-gray-700 text-white hover:text-white hover:underline px-2 py-2 pt-2 rounded-md inline-block flex items-center !text-base">
                                    {{ __('Editar Roles') }}
                                </x-nav-link>
                                @endrole
                                <x-nav-link href="{{ route('usuario.edit', $usuario->id) }}" class="bg-gray-700 text-white hover:text-white hover:underline px-2 py-2 pt-2 rounded-md inline-block flex items-center !text-base">
                                    {{ __('Editar Información de Usuario') }}
                                </x-nav-link>
                                @role('admin')
                                <label class="inline-flex items-center me-5 cursor-pointer">
                                    <input type="checkbox" value="" class="sr-only peer toggle-estado" {{ $usuario->desabilitado ? 'checked' : '' }} data-id="{{ $usuario->id }}">
                                    <div class="relative w-11 h-6 bg-green-500 rounded-full peer peer-focus:ring-4 peer-focus:ring-red-300 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-red-600 dark:peer-checked:bg-red-600"></div>
                                    <span class="ms-3 text-sm font-medium text-gray-900">Activo</span>
                                </label>
                                @endrole
                                @endcan
                                @can('borrar usuarios')
                                <form action="{{ route('usuario.destroy', $usuario->id) }}" method="POST" class="inline-block">
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
                {{ $usuarios->links() }}
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.toggle-estado').forEach(toggle => {
        toggle.addEventListener('change', function () {
            const userId = this.dataset.id;

            fetch(`/usuarios/${userId}/estado`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                console.log(`Nuevo estado del usuario: ${data.nuevo_estado}`);
            })
            .catch(error => {
            alert('Error actualizando el estado del usuario');
                this.checked = !this.checked; // revertir en caso de error
            });
        });
        });
    </script>
</x-app-layout>