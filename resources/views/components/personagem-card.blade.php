{{-- resources/views/components/personagem-card.blade.php --}}
@props(['personagem'])

<div class="group bg-gray-800 rounded-lg overflow-hidden shadow-xl hover:shadow-red-600/30 transition-all duration-300 transform hover:-translate-y-2 card-item">
    <a href="{{ route('personagens.show', $personagem['id']) }}">
        <div class="relative">
            <img src="{{ $personagem['thumbnail']['path'] . '.' . $personagem['thumbnail']['extension'] }}" 
                 alt="{{ $personagem['name'] }}" 
                 class="w-full h-80 object-cover object-top transition-transform duration-300 group-hover:scale-110">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-4">
                <h2 class="text-2xl font-bold text-white tracking-wider">{{ $personagem['name'] }}</h2>
            </div>
        </div>
    </a>
</div>