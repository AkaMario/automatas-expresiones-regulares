<div id="docsModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-4xl max-h-[85vh] flex flex-col shadow-2xl overflow-hidden animate-fadeIn">
        <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between bg-slate-800/40">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-file-lines text-indigo-400"></i>
                <h3 class="font-bold text-white text-base">Archivo de Documentación: <code class="text-xs bg-slate-800 px-2 py-0.5 rounded text-indigo-300 font-mono">docs/ejemplos.md</code></h3>
            </div>
            <button onclick="document.getElementById('docsModal').classList.add('hidden')" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        
        <div class="p-6 overflow-y-auto space-y-6 text-sm text-slate-300 leading-relaxed font-sans">
            <div class="p-4 rounded-xl bg-indigo-950/40 border border-indigo-500/20">
                <h4 class="font-semibold text-indigo-300 text-sm mb-1">Fundación Tecnológica Comfenalco de Cartagena</h4>
                <p class="text-xs text-slate-400">I Proyecto de Autómatas, Gramáticas y Lenguajes &bull; Chatbot con Expresiones Regulares &bull; Profesor: Ing. Carlos García Castro</p>
            </div>

            <div class="space-y-4">
                <h4 class="text-white font-bold text-base flex items-center gap-2 border-b border-slate-800 pb-2">
                    <span class="w-6 h-6 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center text-xs font-bold">1</span>
                    Yes/No Questions (Presente)
                </h4>
                <div class="bg-slate-950/60 p-4 rounded-xl border border-slate-800 space-y-2">
                    <p><strong class="text-slate-200">Estructura:</strong> <code class="text-amber-300 font-mono">Verbo To Be (Am / Is / Are) + Sujeto + Complemento + ?</code></p>
                    <p><strong class="text-emerald-400">Ejemplo:</strong> <span class="text-white font-semibold italic">"Is she a nice girl?"</span></p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-2 text-xs">
                        <div class="bg-slate-900 p-2 rounded border border-slate-800"><span class="text-slate-400 block">Verbo:</span> <span class="text-indigo-300 font-mono">Is</span></div>
                        <div class="bg-slate-900 p-2 rounded border border-slate-800"><span class="text-slate-400 block">Sujeto:</span> <span class="text-indigo-300 font-mono">she</span></div>
                        <div class="bg-slate-900 p-2 rounded border border-slate-800"><span class="text-slate-400 block">Complemento:</span> <span class="text-indigo-300 font-mono">a nice girl</span></div>
                        <div class="bg-slate-900 p-2 rounded border border-slate-800"><span class="text-slate-400 block">Cierre:</span> <span class="text-indigo-300 font-mono">?</span></div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <h4 class="text-white font-bold text-base flex items-center gap-2 border-b border-slate-800 pb-2">
                    <span class="w-6 h-6 rounded-full bg-purple-500/20 text-purple-400 flex items-center justify-center text-xs font-bold">2</span>
                    Wh- Questions (Información)
                </h4>
                <div class="bg-slate-950/60 p-4 rounded-xl border border-slate-800 space-y-2">
                    <p><strong class="text-slate-200">Estructura:</strong> <code class="text-amber-300 font-mono">Palabra Wh- + Verbo To Be + Sujeto + Complemento + ?</code></p>
                    <p><strong class="text-emerald-400">Ejemplo:</strong> <span class="text-white font-semibold italic">"Where is the cat?"</span></p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-2 text-xs">
                        <div class="bg-slate-900 p-2 rounded border border-slate-800"><span class="text-slate-400 block">Wh- Word:</span> <span class="text-purple-300 font-mono">Where</span></div>
                        <div class="bg-slate-900 p-2 rounded border border-slate-800"><span class="text-slate-400 block">Verbo:</span> <span class="text-purple-300 font-mono">is</span></div>
                        <div class="bg-slate-900 p-2 rounded border border-slate-800"><span class="text-slate-400 block">Sujeto:</span> <span class="text-purple-300 font-mono">the cat</span></div>
                        <div class="bg-slate-900 p-2 rounded border border-slate-800"><span class="text-slate-400 block">Cierre:</span> <span class="text-purple-300 font-mono">?</span></div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <h4 class="text-white font-bold text-base flex items-center gap-2 border-b border-slate-800 pb-2">
                    <span class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs font-bold">3</span>
                    Pasado (Was / Were)
                </h4>
                <div class="bg-slate-950/60 p-4 rounded-xl border border-slate-800 space-y-2">
                    <p><strong class="text-slate-200">Estructura:</strong> <code class="text-amber-300 font-mono">Verbo To Be (Was / Were) + Sujeto + Complemento + ?</code></p>
                    <p><strong class="text-emerald-400">Ejemplo:</strong> <span class="text-white font-semibold italic">"Were you a good student?"</span></p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-2 text-xs">
                        <div class="bg-slate-900 p-2 rounded border border-slate-800"><span class="text-slate-400 block">Verbo:</span> <span class="text-emerald-300 font-mono">Were</span></div>
                        <div class="bg-slate-900 p-2 rounded border border-slate-800"><span class="text-slate-400 block">Sujeto:</span> <span class="text-emerald-300 font-mono">you</span></div>
                        <div class="bg-slate-900 p-2 rounded border border-slate-800"><span class="text-slate-400 block">Complemento:</span> <span class="text-emerald-300 font-mono">a good student</span></div>
                        <div class="bg-slate-900 p-2 rounded border border-slate-800"><span class="text-slate-400 block">Cierre:</span> <span class="text-emerald-300 font-mono">?</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-3 bg-slate-800/60 border-t border-slate-800 flex justify-end">
            <button onclick="document.getElementById('docsModal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs transition">
                Cerrar Documentación
            </button>
        </div>
    </div>
</div>
