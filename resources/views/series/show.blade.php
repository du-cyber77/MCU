@extends('layouts.app') {{-- CORREÇÃO AQUI --}}

@section('content') {{-- CORREÇÃO AQUI --}}
    <div class="container mx-auto px-4 py-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            {{-- Imagem da Série --}}
            <div class="md:col-span-1">
                <x-marvel-image
                    :path="$serie['thumbnail']['path']"
                    :extension="$serie['thumbnail']['extension']"
                    altText="{{ $serie['title'] }}"
                    class="rounded-lg shadow-lg w-full"
                />
            </div>

            {{-- Detalhes da Série --}}
            <div class="md:col-span-2 text-white">
                <h1 class="text-5xl font-extrabold mb-4">{{ $serie['title'] }}</h1>

                <p class="text-gray-300 text-lg leading-relaxed">
                    @if (!empty($serie['description']))
                        {!! $serie['description'] !!} {{-- Usando {!! !!} para renderizar HTML se houver --}}
                    @else
                        Nenhuma descrição disponível para esta série.
                    @endif
                </p>

                <div class="mt-8 border-t border-gray-700 pt-6">
                    <h2 class="text-3xl font-bold mb-4">Informações Adicionais</h2>
                    <ul class="space-y-2 text-gray-300">
                        <li>
                            <span class="font-bold text-white">Ano de Início:</span> {{ $serie['startYear'] }}
                        </li>
                        <li>
                            <span class="font-bold text-white">Ano de Fim:</span> {{ $serie['endYear'] }}
                        </li>
                        <li>
                            <span class="font-bold text-white">Avaliação:</span> {{ $serie['rating'] ?: 'Não classificado' }}
                        </li>
                    </ul>
                </div>

                 {{-- Seção de Quadrinhos --}}
                @if ($serie['comics']['available'] > 0)
                    <div class="mt-10">
                        <h3 class="text-3xl font-bold border-b-2 border-red-500 pb-2 mb-4">Quadrinhos nesta Série</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            @foreach (array_slice($serie['comics']['items'], 0, 8) as $comic)
                               <a href="{{ route('comics.show', explode('/', $comic['resourceURI'])[6]) }}" class="text-center hover:text-red-400">
                                    <p class="text-sm">{{ $comic['name'] }}</p>
                                </a>
                            @endforeach
                        </div>
                         @if ($serie['comics']['available'] > 8)
                             <p class="text-right mt-2"><a href="#" class="text-red-500 hover:underline">Ver todos...</a></p>
                         @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection 