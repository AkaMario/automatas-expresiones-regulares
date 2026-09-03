@props(['examples'])

<aside id="chatSidebar" class="w-72 sm:w-80 border-r border-zinc-800 bg-zinc-950 flex flex-col shrink-0 overflow-y-auto transition-[width,opacity,transform] duration-200">
    <div class="p-3 border-b border-zinc-800">
        <button type="button" data-chat-action="new-chat" class="w-full rounded-lg border border-zinc-800 bg-zinc-900 px-3 py-2 text-left text-xs font-medium text-zinc-100 transition hover:bg-zinc-800">
            Nuevo chat
        </button>
    </div>

    <div class="p-3 border-b border-zinc-800">
        <h2 class="text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-3">Historial</h2>
        <div id="conversationList" class="space-y-1 text-xs text-zinc-400">
            <p class="px-3 py-2 text-zinc-600">Sin chats guardados</p>
        </div>
    </div>

    <div class="p-3 border-b border-zinc-800">
        <h2 class="text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-3 flex items-center justify-between">
            <span>Categorías</span>
            <span id="selectedCategoryBadge" class="text-[10px] font-medium px-2 py-0.5 rounded-md bg-zinc-900 text-zinc-300 border border-zinc-800">Auto</span>
        </h2>

        <div class="grid grid-cols-1 gap-2 text-xs">
            <x-chat.category-button
                title="Detección Automática"
                description="Valida cualquier tipo de pregunta"
                active
            />

            <x-chat.category-button
                category="YES_NO_PRESENT"
                title="1. Yes/No Presente"
                description="Am / Is / Are + Sujeto + Comp + ?"
            />

            <x-chat.category-button
                category="WH_QUESTION"
                title="2. Wh- Questions"
                description="Wh- + To Be + Sujeto + Comp + ?"
            />

            <x-chat.category-button
                category="PAST_WAS_WERE"
                title="3. Pasado (Was / Were)"
                description="Was / Were + Sujeto + Comp + ?"
            />
        </div>
    </div>

    <footer class="mt-auto p-3 bg-zinc-950 border-t border-zinc-800 text-[11px] text-zinc-500 grid grid-cols-3 gap-2">
        <span>Total <strong id="statTotal" class="block text-zinc-100 text-sm">0</strong></span>
        <span>Válidas <strong id="statValid" class="block text-zinc-100 text-sm">0</strong></span>
        <span>Inválidas <strong id="statInvalid" class="block text-zinc-100 text-sm">0</strong></span>
    </footer>
</aside>
