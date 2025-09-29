@php
    $personagensIsActive = request()->routeIs('personagens.*');
    $comicsIsActive = request()->routeIs('comics.*');
    $seriesIsActive = request()->routeIs('series.*'); // Adicionado
     $eventsIsActive = request()->routeIs('events.*');
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
        
        {{-- Link de Séries Adaptado --}}
        <a href="{{ route('series.index') }}"
           class="py-2 px-1 border-b-2 font-medium text-lg transition-all duration-300
                  {{ $seriesIsActive ? 'border-red-500 text-white' : 'border-transparent text-gray-400 hover:border-red-500 hover:text-gray-200' }}">
            Séries
        </a>

        <a href="{{ route('events.timeline') }}"
        class="py-2 px-1 border-b-2 font-medium text-lg transition-all duration-300
          {{ $eventsIsActive ? 'border-red-500 text-white' : 'border-transparent text-gray-400 hover:border-red-500 hover:text-gray-200' }}">
            Eventos
        </a>

    </nav>
</div>