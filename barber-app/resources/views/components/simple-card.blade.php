@props(['titulo'=>null, 'descripcion'=>null, 'url'=>null])

<a href="{{ $url }}" class="block w-full p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100">
    @if(isset($titulo))
    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">{{ $titulo }}</h5>
    @endif
    @if(isset($descripcion))
    <p class="font-normal text-gray-700">{{ $descripcion }}</p>
    @endif
</a>

