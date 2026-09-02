<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-900 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Chatbot TO BE - Expresiones Regulares') | Comfenalco</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.6);
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(99, 102, 241, 0.4);
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(99, 102, 241, 0.7);
        }
        .chat-bubble-bot {
            animation: fadeIn 0.25s ease-out forwards;
        }
        .chat-bubble-user {
            animation: fadeInRight 0.25s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(8px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .typing-dot {
            animation: typingAnimation 1.4s infinite ease-in-out both;
        }
        .typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .typing-dot:nth-child(2) { animation-delay: -0.16s; }
        @keyframes typingAnimation {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1.0); }
        }
    </style>
    @stack('styles')
</head>
<body class="h-full flex flex-col font-sans bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 text-slate-100 overflow-hidden">
    
    <!-- Top Navigation / Header -->
    <header class="border-b border-slate-800/80 bg-slate-900/80 backdrop-blur-md px-4 lg:px-8 py-3.5 flex items-center justify-between z-20 shrink-0">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-indigo-500/25 ring-1 ring-white/20">
                <i class="fa-solid fa-robot text-lg"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-base font-bold text-white tracking-tight">RegexBot <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">TO BE Questions</span></h1>
                    <span class="inline-flex items-center gap-1 text-[11px] font-medium text-emerald-400 bg-emerald-950/60 border border-emerald-800/50 px-2 py-0.5 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Activo
                    </span>
                </div>
                <p class="text-xs text-slate-400">Autómatas, Gramáticas y Lenguajes &bull; Prof. Carlos García</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="document.getElementById('docsModal').classList.remove('hidden')" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-medium text-slate-200 border border-slate-700 transition flex items-center gap-1.5">
                <i class="fa-solid fa-book-open text-indigo-400"></i>
                <span class="hidden sm:inline">Ver Documentación (.md)</span>
            </button>
            <button onclick="document.getElementById('regexModal').classList.remove('hidden')" class="px-3 py-1.5 rounded-lg bg-indigo-600/20 hover:bg-indigo-600/30 text-xs font-medium text-indigo-300 border border-indigo-500/30 transition flex items-center gap-1.5">
                <i class="fa-solid fa-code"></i>
                <span class="hidden sm:inline">Reglas Regex</span>
            </button>
        </div>
    </header>

    <!-- Main Workspace -->
    <main class="flex-1 flex overflow-hidden relative">
        @yield('content')
    </main>

    <!-- Modals -->
    @include('partials.docs-modal')
    @include('partials.regex-modal')

    @stack('scripts')
</body>
</html>
