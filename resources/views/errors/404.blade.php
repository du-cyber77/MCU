{{-- resources/views/errors/404.blade.php --}}
@extends('layouts.app')

@section('title', 'Página Não Encontrada')

@section('content')
    <div class="text-center h-[60vh] flex flex-col items-center justify-center">
        <h1 class="text-9xl font-extrabold text-red-600 tracking-wider">404</h1>
        <h2 class="text-4xl font-bold text-white mt-4 mb-6">Página Não Encontrada</h2>
        <p class="text-lg text-gray-400 max-w-md mb-8">
            Parece que você tentou acessar um universo que ainda não foi descoberto.
        </p>
        <a href="{{ route('home') }}" 
           class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-full text-xl tracking-wider uppercase transition-all transform hover:scale-105 duration-300">
            Voltar para a Home
        </a>
    </div>
@endsection