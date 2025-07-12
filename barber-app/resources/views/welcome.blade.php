<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('El Nica Barber Studio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4 w-full">
                {{-- <x-welcome /> --}}
                <div class="grid sm:grid-cols-1  md:grid-cols-3 gap-8">
                    @foreach ($paquetes as $paquete)
                        <x-paquetes-card title="{{ $paquete->nombre }}" description="{{ $paquete->descripcion }}" btnText="{{ 'Agendar ahora' }}" imgScr="{{ asset($imagenes[$loop->index]) }}" id="{{ $paquete->id }}" precio="{{ $paquete->precio }}" precioColones="{{ $paquete->precio_colones }}"></x-paquetes-card>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>