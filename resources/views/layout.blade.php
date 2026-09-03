<!DOCTYPE html>
<html lang="es" class="h-full bg-black text-zinc-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Chatbot TO BE - Expresiones Regulares') | Comfenalco</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @stack('styles')
</head>
<body class="h-full flex flex-col font-sans bg-black text-zinc-100 overflow-hidden">
    <x-app.header />

    <main class="flex-1 flex overflow-hidden relative bg-black">
        @yield('content')
    </main>

    @include('partials.docs-modal')
    @include('partials.regex-modal')

    @stack('scripts')
</body>
</html>
