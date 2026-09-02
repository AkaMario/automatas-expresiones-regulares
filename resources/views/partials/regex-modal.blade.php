<div id="regexModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-4xl max-h-[85vh] flex flex-col shadow-2xl overflow-hidden animate-fadeIn">
        <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between bg-slate-800/40">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-code text-indigo-400"></i>
                <h3 class="font-bold text-white text-base">Especificación de Expresiones Regulares (Regex)</h3>
            </div>
            <button onclick="document.getElementById('regexModal').classList.add('hidden')" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        
        <div class="p-6 overflow-y-auto space-y-6 text-sm text-slate-300 font-sans">
            @foreach($patterns as $key => $pattern)
                <div class="space-y-2 bg-slate-950/70 p-4 rounded-xl border border-slate-800">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-indigo-300 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-check-circle text-emerald-400 text-xs"></i>
                            {{ $pattern['name'] }}
                        </h4>
                        <span class="text-[11px] font-mono bg-slate-800 text-amber-300 px-2 py-0.5 rounded border border-slate-700">{{ $pattern['formula'] }}</span>
                    </div>
                    <div class="bg-slate-900 p-3 rounded-lg border border-slate-800 overflow-x-auto">
                        <code class="text-xs text-emerald-300 font-mono break-all selection:bg-indigo-600">{{ $pattern['pattern'] }}</code>
                    </div>
                </div>
            @endforeach

            <div class="p-4 rounded-xl bg-slate-800/40 border border-slate-700/60 space-y-2">
                <h4 class="font-bold text-slate-200 text-xs uppercase tracking-wider">Reglas de Sujeto Soportadas</h4>
                <ul class="text-xs text-slate-400 space-y-1 list-disc list-inside">
                    <li><strong class="text-slate-300">Personal Pronouns:</strong> I, you, he, she, it, we, they</li>
                    <li><strong class="text-slate-300">Common Nouns:</strong> The + singular/plural (e.g. <em>The cat, the boys, the car</em>)</li>
                    <li><strong class="text-slate-300">Proper Nouns:</strong> Michael, Ann, Cartagena, Maria, Charles, etc.</li>
                    <li><strong class="text-slate-300">Demonstrative Pronouns:</strong> This, That, These, Those + noun (e.g. <em>This pencil, Those cars</em>)</li>
                </ul>
            </div>
        </div>

        <div class="px-6 py-3 bg-slate-800/60 border-t border-slate-800 flex justify-end">
            <button onclick="document.getElementById('regexModal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs transition">
                Cerrar
            </button>
        </div>
    </div>
</div>
