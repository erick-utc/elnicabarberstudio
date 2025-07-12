@props(['title' => null, 'description' => null, 'btnText'=>null, 'imgScr' => null, 'id' => null, 'precio' => null, 'precioColones'=> null])

<div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm">
    <a href="{{ route('cita.create' , ['id'=>$id, 'isclient'=>true]) }}" class=" max-h-60 overflow-hidden block">
        <img class="rounded-t-lg max-h-60 object-cover object-center block w-full" src="{{ $imgScr }}" alt="{{ 'Paquete'.$title }}" />
    </a>
    <div class="p-5">
        <a href="{{ route('cita.create' , ['id'=>$id, 'isclient'=>true]) }}">
            <h3 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">{{ $title }}</h3>
        </a>
        <p class="mb-3 font-normal text-gray-700 ">{{ $description }}</p>
        @if($precio == 0)
        <p class="mb-3 font-semibold text-lg text-gray-700 ">{{ 'Consultar por el precio' }}</p>
        @endif
        @if($precio != 0)
        <p class="mb-3 font-semibold text-lg text-gray-700 ">{{ $precioColones }}</p>
        @endif
        <a href="{{ route('cita.create' , ['id'=>$id , 'isclient'=>true]) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 ">
            {{ $btnText }}
             <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
            </svg>
        </a>
    </div>
</div>
