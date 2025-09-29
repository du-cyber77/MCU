{{-- resources/views/comics/show.blade.php --}}
@extends('layouts.app')

@section('title', $comic['title'])

@section('content')
    {{-- INÍCIO DA HERO SECTION --}}
    <div class="rounded-lg overflow-hidden shadow-2xl mb-10">
        {{-- Background Image com Efeitos --}}
        <div class="h-[40vh] bg-cover bg-center relative" style="background-image: url('{{ $comic['thumbnail']['path'] . '.' . $comic['thumbnail']['extension'] }}');">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-md"></div>
        </div>

        {{-- Conteúdo Sobreposto --}}
        <div class="relative px-8 pb-8 -mt-48">
            <div class="flex flex-col md:flex-row gap-8">
                {{-- Imagem Principal (Poster) --}}
                <img src="{{ $comic['thumbnail']['path'] . '.' . $comic['thumbnail']['extension'] }}" 
                     alt="{{ $comic['title'] }}" 
                     class="w-full md:w-64 h-auto object-cover rounded-lg shadow-2xl transform hover:scale-105 transition-transform duration-300 -translate-y-16 border-4 border-gray-700">
                
                {{-- Informações --}}
                <div class="flex-1 pt-4 md:pt-0">
                    <h1 class="text-4xl md:text-6xl font-bold text-white mb-4 tracking-wider">{{ $comic['title'] }}</h1>
                    <p class="text-lg text-gray-300 leading-relaxed">
                        {{ $comic['description'] ? strip_tags($comic['description']) : 'Descrição não disponível.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    {{-- FIM DA HERO SECTION --}}

    {{-- Lista de Personagens --}}
    <div class="bg-gray-800 rounded-lg shadow-lg p-8">
        <h2 class="text-3xl font-bold mb-6 tracking-wide">Personagens nesta Edição ({{ $comic['characters']['available'] }})</h2>
        <div class="h-64 overflow-y-auto pr-4">
            <ul class="space-y-3 text-gray-300">
                @forelse($comic['characters']['items'] as $character)
                    @php
                        $characterId = basename($character['resourceURI']);
                    @endphp
                    <li class="flex items-center space-x-3">
                        <span class="text-red-500">&rarr;</span>
                        <a href="{{ route('personagens.show', $characterId) }}" class="text-lg hover:text-red-500 hover:underline transition-colors">
                            {{ $character['name'] }}
                        </a>
                    </li>
                @empty
                    <li>Nenhum personagem listado para esta edição.</li>
                @endforelse
            </ul>
        </div>
    </div>

    {{-- Botão Voltar --}}
    <div class="mt-10">
        <a href="{{ url()->previous() }}" class="inline-block px-8 py-3 bg-red-600 hover:bg-red-700 rounded-lg text-lg transition-colors">
            &larr; Voltar
        </a>
    </div>

 {{-- SEÇÃO NOVA: PERSONAGENS EM DESTAQUE --}}
            @if ($comic['characters']['available'] > 0)
                <div class="mt-12 border-t border-gray-700 pt-8">
                    <h2 class="text-3xl font-bold text-white mb-6">Personagens em Destaque</h2>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                        @foreach ($comic['characters']['items'] as $character)
                            @php
                                // Extrai o ID do personagem da URL (resourceURI)
                                $urlParts = explode('/', $character['resourceURI']);
                                $characterId = end($urlParts);
                            @endphp
                            <a href="{{ route('personagens.show', $characterId) }}" class="group text-center">
                                {{-- Este componente de imagem é um placeholder. O ideal é fazer uma chamada
                                     para pegar a imagem de cada personagem, mas para começar,
                                     vamos exibir o nome. --}}

                                <div class="bg-gray-800 p-3 rounded-lg aspect-square flex items-center justify-center group-hover:bg-red-600 transition-colors duration-200">
                                    {{-- Como não temos a imagem aqui, vamos exibir o nome --}}
                                    <span class="text-white font-semibold">{{ $character['name'] }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>



@endsection