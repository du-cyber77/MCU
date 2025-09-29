{{-- resources/views/layouts/partials/_navigation.blade.php --}}
@php
    $personagensIsActive = request()->routeIs('personagens.*');
    $comicsIsActive = request()->routeIs('comics.*');
@endphp

<div class="border-b-2 border-transparent">
    <nav class="flex space-x-6">
        <a href="{{ route('personagens.index') }}" 
           class="py-2 px-1 border-b-2 font-medium text-lg transition-all duration-300
                  {{ $personagensIsActive ? 'border-red-500 text-white' : 'border-transparent text-gray-400 hover:border-red-500 hover:text-gray-200' }}">
            Personagens
        </a>
        <a href="{{ route('comics.index') }}"
           class="py-2 px-1 border-b-2 font-medium text-lg transition-all duration-300
                  {{ $comicsIsActive ? 'border-red-500 text-white' : 'border-transparent text-gray-400 hover:border-red-500 hover:text-gray-200' }}">
            Quadrinhos
        </a>
    </nav>
</div>