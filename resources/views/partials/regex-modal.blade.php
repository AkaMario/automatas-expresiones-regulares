<div id="regexModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-black border border-zinc-800 rounded-xl w-full max-w-4xl max-h-[85vh] flex flex-col shadow-2xl overflow-hidden animate-fadeIn">
        <div class="px-6 py-4 border-b border-zinc-800 flex items-center justify-between bg-zinc-950">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-code text-zinc-500"></i>
                <h3 class="font-bold text-zinc-100 text-base">Especificación de Expresiones Regulares (Regex)</h3>
            </div>
            <button onclick="document.getElementById('regexModal').classList.add('hidden')" class="text-zinc-500 hover:text-zinc-100 p-1 rounded-lg hover:bg-zinc-900 transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        
        <div class="p-6 overflow-y-auto space-y-6 text-sm text-zinc-300 font-sans">
            @foreach($patterns as $key => $pattern)
                <div class="space-y-2 bg-zinc-950 p-4 rounded-lg border border-zinc-800">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-zinc-100 text-sm flex items-center gap-2">
                            {{ $pattern['name'] }}
                        </h4>
                        <span class="text-[11px] font-mono bg-black text-zinc-300 px-2 py-0.5 rounded border border-zinc-800">{{ $pattern['formula'] }}</span>
                    </div>
                    <div class="bg-black p-3 rounded-lg border border-zinc-900 overflow-x-auto">
                        <code class="text-xs text-zinc-300 font-mono break-all selection:bg-zinc-700">{{ $pattern['pattern'] }}</code>
                    </div>
                </div>
            @endforeach

            <div class="p-4 rounded-lg bg-zinc-950 border border-zinc-800 space-y-2">
                <h4 class="font-bold text-zinc-200 text-xs uppercase tracking-wider">Reglas de Sujeto Soportadas</h4>
                <ul class="text-xs text-zinc-500 space-y-1 list-disc list-inside">
                    <li><strong class="text-zinc-300">Personal Pronouns:</strong> I, you, he, she, it, we, they</li>
                    <li><strong class="text-zinc-300">Common Nouns:</strong> The + singular/plural (e.g. <em>The cat, the boys, the car</em>)</li>
                    <li><strong class="text-zinc-300">Proper Nouns:</strong> Michael, Ann, Cartagena, Maria, Charles, etc.</li>
                    <li><strong class="text-zinc-300">Demonstrative Pronouns:</strong> This, That, These, Those + noun (e.g. <em>This pencil, Those cars</em>)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
