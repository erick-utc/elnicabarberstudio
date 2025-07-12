<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ayuda') }}
        </h2>
    </x-slot>

    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="w-full p-4">
                    <h1 class="text-xl uppercase pb-3">El nica barber studio</h1>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>