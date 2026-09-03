<div class="chat-bubble-bot max-w-3xl mx-auto">
    <div class="space-y-4 text-sm text-zinc-200">
        <div>
            <p class="text-xs font-medium text-zinc-500 mb-1">RegexBot</p>
            <p>Te ayudaré a validar oraciones interrogativas en inglés con el verbo <strong>TO BE</strong> en presente y pasado usando expresiones regulares.</p>
        </div>

        <div id="initialNamePrompt" class="pt-1">
            <p class="text-xs text-zinc-500 font-medium mb-2">Para comenzar, escribe tu nombre.</p>
            <div class="flex flex-col sm:flex-row gap-2 max-w-md">
                <input type="text" id="userNameInput" placeholder="Ej: Carlos" class="flex-1 px-3 py-2 rounded-lg bg-zinc-900 border border-zinc-800 text-xs text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-zinc-500">
                <button onclick="setUserName()" class="px-3 py-2 rounded-lg bg-zinc-100 hover:bg-white text-black text-xs font-semibold transition">
                    Guardar
                </button>
                <button onclick="skipUserName()" class="px-3 py-2 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-zinc-300 text-xs transition border border-zinc-800">
                    Omitir
                </button>
            </div>
        </div>
    </div>
</div>
