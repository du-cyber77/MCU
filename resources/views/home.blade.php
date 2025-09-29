{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Bem-vindo ao Universo Marvel')

@section('content')
    <div 
        class="text-center h-[60vh] flex flex-col items-center justify-center rounded-lg p-8 bg-cover bg-center bg-no-repeat"
        style="background-image: linear-gradient(rgba(17, 24, 39, 0.8), rgba(17, 24, 39, 0.8)), url('https://images.unsplash.com/photo-1620165188327-0c17c8fafc25?q=80&w=2070&auto=format&fit=crop');">
        
        <h1 class="text-7xl md:text-9xl font-extrabold mb-4 text-white uppercase tracking-wider">
            Universo Marvel
        </h1>
        <p class="text-xl text-gray-300 mb-8 max-w-2xl">
            Sua enciclopédia completa de personagens e quadrinhos. Explore o vasto universo de heróis e vilões da Marvel.
        </p>
        <div class="mt-8">
            <a href="{{ route('personagens.index') }}" 
               class="bg-red-600 hover:bg-red-700 text-white font-bold py-4 px-10 rounded-full text-2xl tracking-wider uppercase transition-all transform hover:scale-110 duration-300 shadow-lg shadow-red-600/30">
                Explorar
            </a>
        </div>
    </div>
@endsection