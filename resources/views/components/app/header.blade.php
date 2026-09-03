<header class="h-14 border-b border-zinc-800 bg-black px-3 sm:px-4 flex items-center justify-between z-20 shrink-0">
    <div class="flex items-center gap-2">
        <button type="button" data-chat-action="toggle-sidebar" class="h-9 w-9 rounded-md text-zinc-400 hover:text-zinc-100 hover:bg-zinc-900 transition" title="Abrir o cerrar panel lateral">
            <i class="fa-solid fa-bars text-sm"></i>
        </button>
        <div class="leading-tight">
            <h1 class="text-sm font-semibold text-zinc-100 tracking-tight">RegexBot</h1>
            <p class="hidden sm:block text-[11px] text-zinc-500">Validador de preguntas con verbo TO BE</p>
        </div>
    </div>

    <div class="flex items-center gap-2">
        <button onclick="document.getElementById('docsModal').classList.remove('hidden')" class="h-9 px-3 rounded-md bg-transparent hover:bg-zinc-900 text-xs font-medium text-zinc-300 border border-zinc-800 transition flex items-center gap-2">
            <i class="fa-solid fa-book-open text-zinc-500"></i>
            <span class="hidden sm:inline">Documentación</span>
        </button>
        <button onclick="document.getElementById('regexModal').classList.remove('hidden')" class="h-9 px-3 rounded-md bg-zinc-100 hover:bg-white text-xs font-medium text-black border border-zinc-100 transition flex items-center gap-2">
            <i class="fa-solid fa-code"></i>
            <span class="hidden sm:inline">Regex</span>
        </button>
    </div>
</header>
