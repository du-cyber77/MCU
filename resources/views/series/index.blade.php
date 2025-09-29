@extends('layouts.app') {{-- CORREÇÃO AQUI --}}

@section('content') {{-- CORREÇÃO AQUI --}}
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold text-white mb-8">Séries da Marvel</h1>

        {{-- Grid de Séries --}}
        @if (!empty($series))
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach ($series as $serie)
                    <x-serie-card :serie="$serie" />
                @endforeach
            </div>
        @else
            {{-- Skeleton Loading --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @for ($i = 0; $i < 20; $i++)
                    <x-card-skeleton />
                @endfor
            </div>
        @endif

        {{-- Paginação --}}
        <div class="mt-12 text-white">
            <div class="flex justify-between items-center">
                {{-- Botão Anterior --}}
                @if ($page > 1)
                    <a href="{{ route('series.index', ['page' => $page - 1] + request()->except('page')) }}" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        &laquo; Anterior
                    </a>
                @else
                    <span class="bg-gray-800 text-gray-500 font-bold py-2 px-4 rounded cursor-not-allowed">&laquo; Anterior</span>
                @endif

                <span class="text-lg">Página {{ $page }} de {{ $totalPages }}</span>

                {{-- Botão Próximo --}}
                @if ($page < $totalPages)
                    <a href="{{ route('series.index', ['page' => $page + 1] + request()->except('page')) }}" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        Próximo &raquo;
                    </a>
                @else
                     <span class="bg-gray-800 text-gray-500 font-bold py-2 px-4 rounded cursor-not-allowed">Próximo &raquo;</span>
                @endif
            </div>
        </div>
    </div>
@endsection 