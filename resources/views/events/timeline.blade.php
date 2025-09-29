@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold text-white mb-12 text-center">Linha do Tempo dos Eventos Marvel</h1>

    <div class="relative wrap overflow-hidden p-10 h-full">
        {{-- A linha vertical central --}}
        <div class="border-2-2 absolute border-opacity-20 border-gray-700 h-full border" style="left: 50%"></div>

        @foreach ($events as $index => $event)
            {{-- Item da Direita --}}
            @if($index % 2 == 0)
            <div class="mb-8 flex justify-between items-center w-full right-timeline">
                <div class="order-1 w-5/12"></div>
                <div class="z-20 flex items-center order-1 bg-gray-800 shadow-xl w-8 h-8 rounded-full">
                    <h1 class="mx-auto font-semibold text-lg text-white">{{ $index + 1 }}</h1>
                </div>
                <div class="order-1 bg-gray-700 rounded-lg shadow-xl w-5/12 px-6 py-4">
                    <h3 class="mb-3 font-bold text-white text-xl">{{ $event['title'] }} ({{ \Carbon\Carbon::parse($event['start'])->format('Y') }})</h3>
                    <p class="text-sm leading-snug tracking-wide text-gray-300 text-opacity-100">
                        {{ Str::limit($event['description'], 150) }}
                    </p>
                    {{-- Futuramente, um link para a página de detalhes --}}
                    {{-- <a href="#" class="text-red-500 hover:underline mt-2 inline-block">Saiba mais...</a> --}}
                </div>
            </div>
            @else
            {{-- Item da Esquerda --}}
            <div class="mb-8 flex justify-between flex-row-reverse items-center w-full left-timeline">
                <div class="order-1 w-5/12"></div>
                <div class="z-20 flex items-center order-1 bg-gray-800 shadow-xl w-8 h-8 rounded-full">
                    <h1 class="mx-auto text-white font-semibold text-lg">{{ $index + 1 }}</h1>
                </div>
                <div class="order-1 bg-red-700 rounded-lg shadow-xl w-5/12 px-6 py-4">
                    <h3 class="mb-3 font-bold text-white text-xl">{{ $event['title'] }} ({{ \Carbon\Carbon::parse($event['start'])->format('Y') }})</h3>
                    <p class="text-sm font-medium leading-snug tracking-wide text-white text-opacity-100">
                         {{ Str::limit($event['description'], 150) }}
                    </p>
                    {{-- <a href="#" class="text-white hover:underline mt-2 inline-block">Saiba mais...</a> --}}
                </div>
            </div>
            @endif
        @endforeach
    </div>
</div>
@endsection