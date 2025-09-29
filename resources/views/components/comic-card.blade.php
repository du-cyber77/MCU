{{-- resources/views/components/comic-card.blade.php --}}
@props(['comic'])

<div class="group bg-gray-800 rounded-lg overflow-hidden shadow-xl hover:shadow-red-600/30 transition-all duration-300 transform hover:-translate-y-2 card-item">
    <a href="{{ route('comics.show', $comic['id']) }}">
        <div class="relative">
            <img src="{{ $comic['thumbnail']['path'] . '.' . $comic['thumbnail']['extension'] }}" 
                 alt="{{ $comic['title'] }}" 
                 class="w-full h-96 object-cover transition-transform duration-300 group-hover:scale-110">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-4">
                <h2 class="font-bold text-white truncate">{{ $comic['title'] }}</h2>
            </div>
        </div>
    </a>
</div>