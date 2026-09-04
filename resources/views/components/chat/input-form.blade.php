<div class="p-3 md:p-4 bg-black border-t border-zinc-900">
    <form id="chatForm" onsubmit="handleFormSubmit(event)" class="max-w-4xl mx-auto space-y-2">
        <div class="flex items-center gap-2">
            <div class="relative flex-1">
                <input
                    type="text"
                    id="sentenceInput"
                    placeholder="Escribe tu nombre para comenzar..."
                    class="w-full pl-4 pr-10 py-3 rounded-xl bg-zinc-900 border border-zinc-800 text-sm text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-zinc-500 font-mono transition"
                    autocomplete="off"
                >
                {{-- <button type="button" onclick="clearInput()" title="Limpiar" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-zinc-300 text-xs p-1">
                    <i class="fa-solid fa-eraser"></i>
                </button> --}}
            </div>

            <button
                type="submit"
                id="sendButton"
                class="px-5 py-3 rounded-xl bg-zinc-100 hover:bg-white text-black font-semibold text-sm transition flex items-center gap-2 shrink-0 disabled:opacity-50"
            >
                <span>Enviar</span>
                {{-- <i class="fa-solid fa-paper-plane text-xs"></i> --}}
            </button>
        </div>

        <div class="flex flex-wrap items-center justify-between text-[11px] text-zinc-500 px-1 gap-2">
            <div>
                <span id="inputHelperText">Primero dime cómo te llamas.</span>
                <span class="hidden">Fórmula actual: <code id="activeFormula" class="text-zinc-300 font-mono">Detección Automática</code></span>
            </div>
            {{-- <div class="flex items-center gap-2">
                <button type="button" onclick="sendCustomMessage('Where is the cat?', 'WH_QUESTION')" class="hover:text-zinc-300 underline">Ejemplo Wh-</button>
                <span>&bull;</span>
                <button type="button" onclick="sendCustomMessage('Were you a good student?', 'PAST_WAS_WERE')" class="hover:text-zinc-300 underline">Ejemplo Pasado</button>
            </div> --}}
        </div>
    </form>
</div>
