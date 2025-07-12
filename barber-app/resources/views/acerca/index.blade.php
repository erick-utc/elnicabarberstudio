<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Acerca de...') }}
        </h2>
    </x-slot>

    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="w-full p-4">
                    <h1 class="text-xl uppercase pb-3">El nica barber studio</h1>
                    <h2 class="text-lg pb-3">version: 1.0.0</h2>
                    <h3 class="text-md pb-3">fecha: 22 de agosto del 2025</h3>
                    <h4 class="text-md pb-3">Desarolladores</h4>
                    <ul class="list-disc pl-6 pb-3">
                        <li>Erick Espinoza Araya</li>
                        <li>Anthony Ramirez Fonseca</li>
                    </ul>
                    <h4 class="text-md pb-3">Desarollado en: php 8.4</h4>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>