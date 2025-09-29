@extends('layouts.app')

@section('title', $personagem['name'])

@section('content')
    {{-- INÍCIO DA HERO SECTION --}}
    <div class="rounded-lg overflow-hidden shadow-2xl mb-10">
        {{-- Background Image com Efeitos --}}
        <div class="h-[40vh] bg-cover bg-center relative" style="background-image: url('{{ $personagem['thumbnail']['path'] . '.' . $personagem['thumbnail']['extension'] }}');">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-md"></div>
        </div>

        {{-- Conteúdo Sobreposto --}}
        <div class="relative px-8 pb-8 -mt-48">
            <div class="flex flex-col md:flex-row gap-8">
                {{-- Imagem Principal (Poster) --}}
                <img src="{{ $personagem['thumbnail']['path'] . '.' . $personagem['thumbnail']['extension'] }}" 
                     alt="{{ $personagem['name'] }}" 
                     class="w-full md:w-64 h-auto object-cover rounded-lg shadow-2xl transform hover:scale-105 transition-transform duration-300 -translate-y-16 border-4 border-gray-700">
                
                {{-- Informações --}}
                <div class="flex-1 pt-4 md:pt-0">
                    <h1 class="text-5xl md:text-7xl font-bold text-white mb-4 tracking-wider">{{ $personagem['name'] }}</h1>
                    <p class="text-lg text-gray-300 leading-relaxed">
                        {{ $personagem['description'] ?: 'Descrição não disponível.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    {{-- FIM DA HERO SECTION --}}

    {{-- INÍCIO DA NOVA SEÇÃO "APARIÇÕES EM DESTAQUE" --}}
    @if (!empty($comics))
        <div class="bg-gray-800 rounded-lg shadow-lg p-8">
            <h2 class="text-3xl font-bold text-white mb-6 tracking-wide">Aparições em Destaque</h2>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                @foreach ($comics as $comic)
                    {{-- Reutilizamos o componente que já existe para os cards de quadrinhos --}}
                    <x-comic-card :comic="$comic" />
                @endforeach
            </div>
        </div>
    @endif
    {{-- FIM DA NOVA SEÇÃO --}}

    {{-- Botão Voltar --}}
    <div class="mt-10">
        <a href="{{ url()->previous() }}" class="inline-block px-8 py-3 bg-red-600 hover:bg-red-700 rounded-lg text-lg transition-colors">
            &larr; Voltar
        </a>
    </div>
@endsection