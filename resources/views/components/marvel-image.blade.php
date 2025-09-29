{{-- resources/views/components/marvel-image.blade.php --}}
@props(['src', 'alt', 'classes' => ''])

@php
    // Verifica se o nome do arquivo da imagem é o placeholder padrão da Marvel
    $isPlaceholder = str_contains($src, 'image_not_available');
@endphp

@if ($isPlaceholder)
    {{-- Nosso Placeholder SVG Estilizado --}}
    <div class="w-full h-full bg-gray-700 flex items-center justify-center {{ $classes }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
    </div>
@else
    {{-- Imagem Normal da API --}}
    <img src="{{ $src }}" alt="{{ $alt }}" class="{{ $classes }}">
@endif