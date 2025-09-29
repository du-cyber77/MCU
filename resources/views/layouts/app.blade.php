{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="pt-br" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Universo Marvel')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }
        body { font-family: 'Roboto', sans-serif; }
        h1, h2, a.navbar-brand { font-family: 'Bebas Neue', sans-serif; }

        /* Estilos para a animação de entrada dos cards */
        .card-item {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }
        .card-item.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Estilos para o botão "Voltar ao Topo" */
        #scrollTopBtn {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 30px;
            z-index: 99;
            border: none;
            outline: none;
            background-color: #dc2626; /* Cor vermelha do tema */
            color: white;
            cursor: pointer;
            padding: 15px;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.4);
            transition: opacity 0.3s, visibility 0.3s;
        }
        #scrollTopBtn:hover {
            background-color: #b91c1c;
        }
    </style>
</head>
<body class="bg-gray-900 text-white flex flex-col min-h-full">

    {{-- Botão Voltar ao Topo (Elemento HTML) --}}
    <button onclick="scrollToTop()" id="scrollTopBtn" title="Voltar ao topo">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
        </svg>
    </button>

    {{-- BARRA DE NAVEGAÇÃO --}}
    <nav class="bg-gray-800/80 backdrop-blur-sm shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <a href="{{ route('home') }}" class="navbar-brand text-3xl font-bold text-red-600 tracking-wider hover:text-red-500 transition-colors">
                Universo Marvel
            </a>
            @include('layouts.partials._navigation')
        </div>
    </nav>

    {{-- CONTEÚDO PRINCIPAL DA PÁGINA --}}
    <main class="container mx-auto mt-6 p-6 flex-grow">
        @yield('content')
    </main>

    {{-- RODAPÉ --}}
    <footer class="bg-gray-800 text-center text-sm p-4 text-gray-400 mt-10">
        <p>Dados fornecidos por Marvel. © {{ date('Y') }} Marvel</p>
        <p class="mt-1">Desenvolvido com Laravel e Tailwind CSS.</p>
    </footer>

    {{-- SCRIPTS --}}
    <script>
        // Script de Animação dos Cards
        document.addEventListener("DOMContentLoaded", function() {
            const cards = document.querySelectorAll('.card-item');
            if (typeof IntersectionObserver === 'undefined') return;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('is-visible');
                        }, index * 100);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });

            cards.forEach(card => observer.observe(card));
        });

        // Script para o botão "Voltar ao Topo"
        const scrollTopBtn = document.getElementById("scrollTopBtn");

        window.onscroll = function() {
            scrollFunction();
        };

        function scrollFunction() {
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                scrollTopBtn.style.display = "block";
            } else {
                scrollTopBtn.style.display = "none";
            }
        }

        function scrollToTop() {
            window.scrollTo({top: 0, behavior: 'smooth'});
        }
    </script>

</body>
</html>