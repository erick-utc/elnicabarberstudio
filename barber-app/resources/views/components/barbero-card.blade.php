@props(['nombre' => null, 'primerApellido' => null, 'segundoApellido'=>null, 'imgSrc' => null, 'id' => null, 'precio' => null, 'precioColones'=> null, 'onchange' => null])

<div class="">
    <input type="checkbox" id="barbero_id" {{ $id == old('barbero_id') ? 'checked':'' }} name="barbero_id" value="{{ $id }}" class="hidden peer" {{ $attributes->merge(['onchange' => $onchange]) }}>
    <label for="barbero_id" class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer  peer-checked:border-blue-600 peer-checked:bg-gray-200  hover:text-gray-600  peer-checked:text-gray-600 hover:bg-gray-50">
        <div class="w-full flex flex-col items-center">
            {{-- {{ $imgSrc }} --}}
            @if(isset($imgSrc))
            <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="{{ $imgSrc }}" alt="{{ $nombre.' '.$primerApellido.' '.$segundoApellido.' image' }} "/>
            @endif
            <h5 class="mb-1 text-xl font-medium text-gray-900">{{ $nombre.' '.$primerApellido.' '.$segundoApellido }}</h5>
            <span class="text-sm text-gray-500">Barbero</span>
            <div class="flex mt-4 md:mt-6">
                <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">Seleccionar</span>
            </div>
        </div>
    </label>
</div>

