{{-- resources/views/personagens/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Personagens da Marvel')

@section('content')
    <h1 class="text-4xl font-bold mb-8 text-center">Personagens da Marvel</h1>

    {{-- Formulário de Filtros e Busca --}}
<div class="mb-8 bg-gray-800 p-4 rounded-lg flex flex-col md:flex-row items-center gap-4">
    
    {{-- Formulário de Busca --}}
    <form action="{{ route('personagens.index') }}" method="GET" class="flex-grow w-full md:w-auto">
        <div class="flex">
            <input
                type="text"
                name="busca" {{-- Alterado de 'search' para 'busca' --}}
                placeholder="Buscar por nome..."
                class="w-full bg-gray-700 text-white rounded-l-md p-2 focus:outline-none focus:ring-2 focus:ring-red-500"
                value="{{ request('busca') }}"
            >
            {{-- Mantém o filtro de ordenação ao buscar --}}
            <input type="hidden" name="orderBy" value="{{ $orderBy }}">
            
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-r-md">
                Buscar
            </button>
        </div>
    </form>

    {{-- Formulário de Ordenação --}}
    <form action="{{ route('personagens.index') }}" method="GET" class="w-full md:w-auto">
         <div class="flex items-center">
             <label for="orderBy" class="text-white mr-2">Ordenar por:</label>
             <select name="orderBy" id="orderBy" class="bg-gray-700 text-white rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-500" onchange="this.form.submit()">
                 <option value="name" @if($orderBy == 'name') selected @endif>Nome (A-Z)</option>
                 <option value="-name" @if($orderBy == '-name') selected @endif>Nome (Z-A)</option>
                 <option value="-modified" @if($orderBy == '-modified') selected @endif>Mais Recentes</option>
                 <option value="modified" @if($orderBy == 'modified') selected @endif>Mais Antigos</option>
             </select>
             {{-- Mantém o termo de busca ao ordenar --}}
             <input type="hidden" name="busca" value="{{ request('busca') }}">
         </div>
    </form>
</div>

{{-- Grid de Personagens --}}
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
    @foreach ($personagens as $personagem)
        <x-personagem-card :personagem="$personagem" />
    @endforeach
</div>

{{-- Paginação do Laravel --}}
<div class="mt-12">
    {{ $personagens->links() }}
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