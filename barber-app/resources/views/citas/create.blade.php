<x-app-layout>
    <x-slot name="header">
         <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Citas | Crear') }}
            </h2>
            @can('ver citas')
            <x-nav-link href="{{ route('cita.index') }}" class="bg-gray-700 text-white hover:text-white px-2 py-2 pt-2 rounded-md inline-block flex items-center">
                {{ __('Volver a Citas') }}
            </x-nav-link>
            @endcan
         </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                <!-- <x-welcome /> -->
                 <form action="{{ route('cita.store', ['isclient'=>$isClient]) }}" method="POST">
                    @csrf
                    <div>
                        {{-- <label for="name" class="text-lg font-medium">Nombre</label>
                        <div class="my-3">
                            <input value="{{ old('name') }}" placeholder="Ingrese el nombre del cita" name="name" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                            @error('name')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div> --}}
                        <label for="barbero_id" class="text-lg font-medium" >Barbero</label>
                        <div class="my-3 grid grid-cols-3">
                             @foreach ($barberos as $barbero)
                            <x-barbero-card nombre="{{ $barbero->name }}" primerApellido="{{ $barbero->primerApellido }}" segundoApellido="{{ $barbero->segundoApellido }}" id="{{ $barbero->id }}" imgSrc="{{ Storage::url($barbero->profile_photo_path) }}" onchange="cargarHoras()"/>
                            @endforeach
                            
                        </div>
                        <div>
                            @error('barbero_id')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <style>
                            .ts-contol {
                                font-size: inherit;
                            }
                        </style>
                        <label for="cliente_id" class="text-lg font-medium">Cliente</label>
                        <div class="my-3">
                            {{-- <input value="{{ old('name') }}" placeholder="Ingrese el nombre del cita" name="user_id" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg"> --}}
                            <select name="cliente_id" id="cliente_id" class="border-gray-300 shadow-sm w-1/2 rounded-lg tom-select !text-lg">
                                <option value="">Seleccione el cliente</option>
                                @foreach ($clientes as $user)
                                <option value="{{ $user->id }}" @selected(($user->name == Auth::user()->name)&&($user->primerApellido == Auth::user()->primerApellido)&&($user->segundoApellido == Auth::user()->segundoApellido))>{{ $user->name.' '.$user->primerApellido.' '.$user->segundoApellido }}</option>
                                @endforeach
                            </select>
                            @error('cliente_id')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <label for="paquete_id" class="text-lg font-medium">Paquete</label>
                        <div class="my-3">
                            {{-- <input value="{{ old('name') }}" placeholder="Ingrese el nombre del cita" name="user_id" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg"> --}}
                            <select name="paquete_id" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @foreach ($paquetes as $paquete)
                                <option value="{{ $paquete->id }}" @selected($paquete->id == $id)>{{ $paquete->nombre.' '.$paquete->descripcion }}</option>
                                @endforeach
                            </select>
                            @error('paquete_id')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- <label for="dia" class="text-lg font-medium">Dia</label>
                        <div class="my-3">
                            <select name="dia" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @foreach (App\Enums\DiasSemana::cases() as $diaEnum)
                                    <div class="mt-3">
                                        <option @selected($diaEnum->name == $dia) value="{{ $diaEnum->name }}">{{ $diaEnum->name }}</option>
                                    </div>
                                @endforeach
                            </select>
                            @error('paquete_id')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div> --}}
                        <label for="fecha" class="text-lg font-medium" >Fecha</label>
                        <div class="my-3">
                            <input value="{{ old('fecha', $fecha) }}" id="fecha" name="fecha" type="date" class="border-gray-300 shadow-sm w-1/2 rounded-lg" onchange="cargarHoras()" required>
                            @error('fecha')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <label for="hora" class="text-lg font-medium">Hora de la cita</label>
                        <div class="my-3">
                            <x-loading/>
                            {{-- <input value="{{ old('name') }}" placeholder="Ingrese el nombre del cita" name="user_id" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg"> --}}
                            <select name="hora" class="border-gray-300 shadow-sm w-1/2 rounded-lg" id="hora">
                                {{-- @foreach ($horas as $hora)
                                <option value="{{ \Carbon\Carbon::parse($hora)->locale('es')->format("H:i") }}">{{ \Carbon\Carbon::parse($hora)->locale('es')->format("H:i") }}</option>
                                @endforeach --}}
                                <option value="">Seleccione una hora</option>
                            </select>
                            @error('hora')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- <div class="my-3">
                            <input value="{{ old('hora') }}" name="hora" type="time" class="border-gray-300 shadow-sm w-1/2 rounded-lg"  required>
                            @error('hora')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div> --}}
                        {{-- <label for="fin" class="text-lg font-medium">Hora de Fin</label>
                        <div class="my-3">
                            <input value="{{ old('fin') }}" placeholder="Ingrese la Hora de Salida" name="fin" type="time" class="border-gray-300 shadow-sm w-1/2 rounded-lg" required>
                            @error('fin')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div> --}}
                        {{-- <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                const barberoSelect = document.getElementById('barbero');
                                console.log(barberoSelect);
                                if(barberoSelect) {
                                    
                                    console.log(barberoSelect.value);
                                }
                            })
                            
                        </script> --}}
                        <x-button >
                            {{ __('Crear') }}
                        </x-button>
                    </div>
                 </form>
            </div>
        </div>
    </div>
    <!-- obtener las horas del barbero seleccionado -->
    <script>
        function cargarHoras() {
            const barberoId = document.getElementById('barbero_id').value;
            const fecha = document.getElementById('fecha')?.value || '{{ $fecha }}';
            const loading = document.getElementById('loading');

            if (!barberoId) return;

            if(loading) {
                loading.classList.remove('hidden');
            }

            fetch(`/barbero/horas-disponibles?barbero_id=${barberoId}&fecha=${fecha}`)
                .then(res => res.json())
                .then(data => {
                    const selectHora = document.getElementById('hora');
                    selectHora.innerHTML = '<option value="">Seleccione una hora</option>';

                    data.horas_posibles.forEach(hora => {
                        if (!data.horas_no_disponibles.includes(hora)) {
                            const option = document.createElement('option');
                            option.value = hora;
                            option.innerText = hora;
                            selectHora.appendChild(option);
                        }
                    });

                    loading.classList.add('hidden');

                    if (selectHora.options.length === 1) {
                        const noOptions = document.createElement('option');
                        noOptions.innerText = 'No hay horas disponibles';
                        noOptions.disabled = true;
                        selectHora.appendChild(noOptions);
                    }
                });
        }
    </script>
    <!-- activar el tom select -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.0/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            new TomSelect('#cliente_id', {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
        });
    </script>
</x-app-layout>