{{-- resources/views/personagens/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Personagens da Marvel')

@section('content')
    <h1 class="text-4xl font-bold mb-8 text-center">Personagens da Marvel</h1>

    {{-- Formulário de Busca --}}
    <div class="mb-10">
        <form action="{{ route('personagens.index') }}" method="GET" class="max-w-xl mx-auto">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    name="busca" 
                    placeholder="Buscar por herói (ex: Hulk)" 
                    class="block w-full p-4 pl-10 text-lg bg-gray-700 border border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors"
                    value="{{ request('busca') }}"
                >
            </div>
        </form>
    </div>

    {{-- Container para os esqueletos (visível por padrão) --}}
    <div id="skeleton-loader" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        @for ($i = 0; $i < 12; $i++)
            <x-card-skeleton />
        @endfor
    </div>

    {{-- Container para o conteúdo real (escondido por padrão) --}}
    <div id="actual-content" style="display: none;">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @forelse ($personagens as $personagem)
                <x-personagem-card :personagem="$personagem" />
            @empty
                <p class="text-center col-span-full text-lg">
                    @if (request('busca'))
                        Nenhum personagem encontrado com o termo "{{ request('busca') }}".
                    @else
                        Nenhum personagem encontrado.
                    @endif
                </p>
            @endforelse
        </div>
    
        <div class="mt-10">
            {{ $personagens->links('vendor.pagination.tailwind') }}
        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            const skeleton = document.getElementById('skeleton-loader');
            const content = document.getElementById('actual-content');
            
            if (skeleton && content) {
                skeleton.style.display = 'none';
                content.style.display = 'block';
            }
        });
    </script>
@endsection