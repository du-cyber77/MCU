@props(['serie'])

<div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
    <a href="{{ route('series.show', $serie['id']) }}">
        <x-marvel-image
            :path="$serie['thumbnail']['path']"
            :extension="$serie['thumbnail']['extension']"
            altText="{{ $serie['title'] }}"
            class="w-full h-72 object-cover"
        />
    </a>
    <div class="p-4">
        <h3 class="text-xl font-bold text-white">
            <a href="{{ route('series.show', $serie['id']) }}" class="hover:text-red-500 transition-colors duration-200">
                {{ $serie['title'] }}
            </a>
        </h3>
    </div>
</div>