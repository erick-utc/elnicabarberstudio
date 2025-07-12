{{-- <x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Citas') }}
            </h2>
            <x-nav-link href="{{ route('cita.create') }}" class="bg-blue-500 text-white hover:text-white hover:underline px-2 py-2 pt-2 rounded-md inline-block flex items-center">
                {{ __('Crear Nuevo') }}
            </x-nav-link>
        </div>
    </x-slot>

    <div class="container">
        <h2>Mi calendario de citas</h2>
        <div id="calendar"></div>
    </div>

    <!-- FullCalendar -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            locale: 'es',
            events: @json($events),
            editable: true,
            minTime: '07:00:00',
            maxTime: '20:00:00',
            allDaySlot: false,
            slotDuration: '00:30:00',
            eventDrop: function(info) {
                fetch(`/citas/${info.event.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        fecha: info.event.start.toISOString()
                    })
                }).then(() => {
                    alert('Cita actualizada');
                });
            }
        });
        calendar.render();
    });
    </script>

    {{-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4 w-full">
                <table class="w-full">
                    <thead class="bg-gray-200">
                        <tr class="border-b">
                            <th class="p-5 text-left">#</th>
                            <th class="p-5 text-left">Nombre</th>
                            <th class="p-5 text-left">Descripcion</th>
                            <th class="p-5 text-left">Precio</th>
                            <th class="p-5 text-left">Creado</th>
                            <th class="p-5 text-left">Operaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paquetes as $paquete)
                        <tr>
                            <td class="p-5">{{$paquete->id}}</td>
                            <td class="p-5">{{$paquete->nombre}}</td>
                            <td class="p5">{{ $paquete->descripcion }}</td>
                            <td class="p5">{{ $paquete->precio_colones }}</td>
                            <td class="p-5">{{\Carbon\Carbon::parse($paquete->created_at)->format('d M, Y')}}</td>
                            <td class="p-5">
                                <x-nav-link href="{{ route('paquete.edit', $paquete->id) }}" class="bg-gray-700 text-white hover:text-white hover:underline px-2 py-2 pt-2 rounded-md inline-block flex items-center !text-base">
                                    {{ __('Editar') }}
                                </x-nav-link>
                                <form action="{{ route('paquete.destroy', $paquete) }}" method="POST" class="inline-block">
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
                {{ $paquetes->links() }}
            </div>
        </div>
    </div> --}}
{{-- </x-app-layout> --}} 

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Citas | Calendario') }}
            </h2>
            @can('ver citas')
            <x-nav-link href="{{ route('cita.index') }}" class="bg-blue-500 text-white hover:text-white hover:underline px-2 py-2 pt-2 rounded-md inline-block flex items-center">
                {{ __('Volver a citas') }}
            </x-nav-link>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4 w-full">
                @if($isAdmin)
                <form method="GET" action="{{ route('cita.calendar') }}">
                    <label for="user_id" class="text-lg font-medium">Personal</label>
                    <div class="my-3">
                        <select name="user_id" class="border-gray-300 shadow-sm w-1/2 rounded-lg" onchange="this.form.submit()">
                            <option value="">Todos</option>  {{-- todo remove this if is not admin --}}
                            @foreach($barberos as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->nombre.' '.$user->primerApellido.' '.$user->segundoApellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
                @endif

                <div id="calendar" style="margin-top: 30px;"></div>
            </div>

            <style>
                    /* Aplicar en pantallas pequeñas */
                    @media (max-width: 768px) {
                    .fc .fc-header-toolbar {
                        flex-direction: column !important;
                        align-items: flex-start;
                        gap: 0.5rem;
                    }

                    .fc .fc-toolbar-chunk {
                        width: 100%;
                        display: flex;
                        justify-content: space-between;
                    }
                    }
                </style>

                <script type="module">
                    import { Calendar } from 'https://cdn.skypack.dev/@fullcalendar/core';
                    import dayGridPlugin from 'https://cdn.skypack.dev/@fullcalendar/daygrid';
                    import timeGridPlugin from 'https://cdn.skypack.dev/@fullcalendar/timegrid';
                    import listPlugin from 'https://cdn.skypack.dev/@fullcalendar/list';
                    import interactionPlugin from 'https://cdn.skypack.dev/@fullcalendar/interaction';
                    import esLocale from 'https://cdn.skypack.dev/@fullcalendar/core/locales/es';

                    document.addEventListener('DOMContentLoaded', function () {
                        const calendarEl = document.getElementById('calendar');
                        const isMobile = window.matchMedia('(max-width: 768px)');
                        const getInitialView = () => (isMobile.matches ? 'listWeek' : 'listWeek');
                        const calendar = new FullCalendar.Calendar(calendarEl, {
                            locale: 'es',
                            initialView: getInitialView(),
                            allDaySlot: false,
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'timeGridDay,dayGridMonth,timeGridWeek,listWeek'
                            },
                            events: @json($events),
                            slotMinTime: '06:00:00',
                            slotMaxTime: '22:00:00',
                            height: 'auto',
                        });
                        calendar.render();
                    });
                </script>
            </div>
        </div>
    </div>
</x-app-layout>


