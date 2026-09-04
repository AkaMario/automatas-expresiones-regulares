<div id="docsModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-black border border-zinc-800 rounded-xl w-full max-w-4xl max-h-[85vh] flex flex-col shadow-2xl overflow-hidden animate-fadeIn">
        <div class="px-6 py-4 border-b border-zinc-800 flex items-center justify-between bg-zinc-950">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-file-lines text-zinc-500"></i>
                <h3 class="font-bold text-zinc-100 text-base">Archivo de Documentación: <code class="text-xs bg-zinc-900 px-2 py-0.5 rounded text-zinc-300 font-mono">docs/ejemplos.md</code></h3>
            </div>
            <button onclick="document.getElementById('docsModal').classList.add('hidden')" class="text-zinc-500 hover:text-zinc-100 p-1 rounded-lg hover:bg-zinc-900 transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        
        <div class="p-6 overflow-y-auto space-y-6 text-sm text-zinc-300 leading-relaxed font-sans">
            <div class="p-4 rounded-lg bg-zinc-950 border border-zinc-800">
                <h4 class="font-semibold text-zinc-100 text-sm mb-1">Fundación Tecnológica Comfenalco de Cartagena</h4>
                <p class="text-xs text-zinc-500">I Proyecto de Autómatas, Gramáticas y Lenguajes &bull; Chatbot con Expresiones Regulares &bull; Profesor: Ing. Carlos García Castro</p>
            </div>

            <div class="space-y-4">
                <h4 class="text-zinc-100 font-bold text-base flex items-center gap-2 border-b border-zinc-800 pb-2">
                    <span class="w-6 h-6 rounded-full bg-zinc-900 text-zinc-400 flex items-center justify-center text-xs font-bold">1</span>
                    Yes/No Questions (Presente)
                </h4>
                <div class="bg-zinc-950 p-4 rounded-lg border border-zinc-800 space-y-2">
                    <p><strong class="text-zinc-100">Estructura:</strong> <code class="text-zinc-300 font-mono">Verbo To Be (Am / Is / Are) + Sujeto + Complemento + ?</code></p>
                    <p><strong class="text-zinc-100">Ejemplo:</strong> <span class="text-white font-semibold italic">"Is she a nice girl?"</span></p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-2 text-xs">
                        <div class="bg-black p-2 rounded border border-zinc-900"><span class="text-zinc-500 block">Verbo:</span> <span class="text-zinc-200 font-mono">Is</span></div>
                        <div class="bg-black p-2 rounded border border-zinc-900"><span class="text-zinc-500 block">Sujeto:</span> <span class="text-zinc-200 font-mono">she</span></div>
                        <div class="bg-black p-2 rounded border border-zinc-900"><span class="text-zinc-500 block">Complemento:</span> <span class="text-zinc-200 font-mono">a nice girl</span></div>
                        <div class="bg-black p-2 rounded border border-zinc-900"><span class="text-zinc-500 block">Cierre:</span> <span class="text-zinc-200 font-mono">?</span></div>
                    </div>
                </div>
            </div>

            {{--
                <div class="space-y-4">
                    <h4 class="text-zinc-100 font-bold text-base flex items-center gap-2 border-b border-zinc-800 pb-2">
                        <span class="w-6 h-6 rounded-full bg-zinc-900 text-zinc-400 flex items-center justify-center text-xs font-bold">2</span>
                        Wh- Questions (Información)
                    </h4>
                    <div class="bg-zinc-950 p-4 rounded-lg border border-zinc-800 space-y-2">
                        <p><strong class="text-zinc-100">Estructura:</strong> <code class="text-zinc-300 font-mono">Palabra Wh- + Verbo To Be + Sujeto + Complemento + ?</code></p>
                        <p><strong class="text-zinc-100">Ejemplo:</strong> <span class="text-white font-semibold italic">"Where is the cat?"</span></p>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-2 text-xs">
                            <div class="bg-black p-2 rounded border border-zinc-900"><span class="text-zinc-500 block">Wh- Word:</span> <span class="text-zinc-200 font-mono">Where</span></div>
                            <div class="bg-black p-2 rounded border border-zinc-900"><span class="text-zinc-500 block">Verbo:</span> <span class="text-zinc-200 font-mono">is</span></div>
                            <div class="bg-black p-2 rounded border border-zinc-900"><span class="text-zinc-500 block">Sujeto:</span> <span class="text-zinc-200 font-mono">the cat</span></div>
                            <div class="bg-black p-2 rounded border border-zinc-900"><span class="text-zinc-500 block">Cierre:</span> <span class="text-zinc-200 font-mono">?</span></div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="text-zinc-100 font-bold text-base flex items-center gap-2 border-b border-zinc-800 pb-2">
                        <span class="w-6 h-6 rounded-full bg-zinc-900 text-zinc-400 flex items-center justify-center text-xs font-bold">3</span>
                        Pasado (Was / Were)
                    </h4>
                    <div class="bg-zinc-950 p-4 rounded-lg border border-zinc-800 space-y-2">
                        <p><strong class="text-zinc-100">Estructura:</strong> <code class="text-zinc-300 font-mono">Verbo To Be (Was / Were) + Sujeto + Complemento + ?</code></p>
                        <p><strong class="text-zinc-100">Ejemplo:</strong> <span class="text-white font-semibold italic">"Were you a good student?"</span></p>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-2 text-xs">
                            <div class="bg-black p-2 rounded border border-zinc-900"><span class="text-zinc-500 block">Verbo:</span> <span class="text-zinc-200 font-mono">Were</span></div>
                            <div class="bg-black p-2 rounded border border-zinc-900"><span class="text-zinc-500 block">Sujeto:</span> <span class="text-zinc-200 font-mono">you</span></div>
                            <div class="bg-black p-2 rounded border border-zinc-900"><span class="text-zinc-500 block">Complemento:</span> <span class="text-zinc-200 font-mono">a good student</span></div>
                            <div class="bg-black p-2 rounded border border-zinc-900"><span class="text-zinc-500 block">Cierre:</span> <span class="text-zinc-200 font-mono">?</span></div>
                        </div>
                    </div>
                </div>
            --}}
        </div>

        <div class="px-6 py-3 bg-zinc-950 border-t border-zinc-800 flex justify-end">
            <button onclick="document.getElementById('docsModal').classList.add('hidden')" class="px-4 py-2 rounded-lg bg-zinc-100 hover:bg-white text-black font-semibold text-xs transition">
                Cerrar Documentación
            </button>
        </div>
    </div>
</div>
