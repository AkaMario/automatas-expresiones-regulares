@extends('layout')

@section('title', 'Chatbot con Expresiones Regulares | Questions TO BE')

@section('content')
<div class="flex-1 flex flex-col lg:flex-row h-full w-full overflow-hidden">
    
    <!-- Left Sidebar: Categorías & Ejemplos Rápidos -->
    <aside class="w-full lg:w-80 xl:w-96 border-b lg:border-b-0 lg:border-r border-slate-800 bg-slate-900/50 backdrop-blur flex flex-col shrink-0 overflow-y-auto">
        <div class="p-4 border-b border-slate-800/80 bg-slate-900/80">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 flex items-center justify-between">
                <span><i class="fa-solid fa-layer-group text-indigo-400 mr-1.5"></i> Categorías de Preguntas</span>
                <span id="selectedCategoryBadge" class="text-[10px] font-semibold px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">Auto</span>
            </h2>
            
            <div class="grid grid-cols-1 gap-2 text-xs">
                <button type="button" onclick="selectCategory(null, 'Modo Libre (Cualquiera)')" class="category-btn w-full text-left p-2.5 rounded-xl border border-indigo-500/40 bg-indigo-950/30 hover:bg-indigo-900/40 text-slate-200 transition flex items-center justify-between active-category" data-category="">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-indigo-600/30 text-indigo-400 flex items-center justify-center font-bold text-xs"><i class="fa-solid fa-wand-magic-sparkles"></i></span>
                        <div>
                            <p class="font-semibold text-white">Detección Automática</p>
                            <p class="text-[11px] text-slate-400">Valida cualquier tipo de pregunta</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-check text-indigo-400 text-xs checkmark"></i>
                </button>

                <button type="button" onclick="selectCategory('YES_NO_PRESENT', 'Yes/No Questions (Presente)')" class="category-btn w-full text-left p-2.5 rounded-xl border border-slate-800 bg-slate-900/60 hover:bg-slate-800 text-slate-300 transition flex items-center justify-between" data-category="YES_NO_PRESENT">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center font-bold text-xs"><i class="fa-solid fa-question"></i></span>
                        <div>
                            <p class="font-semibold text-slate-200">1. Yes/No Presente</p>
                            <p class="text-[11px] text-slate-400">Am / Is / Are + Sujeto + Comp + ?</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-check text-blue-400 text-xs checkmark hidden"></i>
                </button>

                <button type="button" onclick="selectCategory('WH_QUESTION', 'Wh- Questions (Información)')" class="category-btn w-full text-left p-2.5 rounded-xl border border-slate-800 bg-slate-900/60 hover:bg-slate-800 text-slate-300 transition flex items-center justify-between" data-category="WH_QUESTION">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-purple-500/20 text-purple-400 flex items-center justify-center font-bold text-xs"><i class="fa-solid fa-info"></i></span>
                        <div>
                            <p class="font-semibold text-slate-200">2. Wh- Questions</p>
                            <p class="text-[11px] text-slate-400">Wh- + To Be + Sujeto + Comp + ?</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-check text-purple-400 text-xs checkmark hidden"></i>
                </button>

                <button type="button" onclick="selectCategory('PAST_WAS_WERE', 'Questions Pasado (Was/Were)')" class="category-btn w-full text-left p-2.5 rounded-xl border border-slate-800 bg-slate-900/60 hover:bg-slate-800 text-slate-300 transition flex items-center justify-between" data-category="PAST_WAS_WERE">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-xs"><i class="fa-solid fa-clock-rotate-left"></i></span>
                        <div>
                            <p class="font-semibold text-slate-200">3. Pasado (Was / Were)</p>
                            <p class="text-[11px] text-slate-400">Was / Were + Sujeto + Comp + ?</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-check text-emerald-400 text-xs checkmark hidden"></i>
                </button>
            </div>
        </div>

        <!-- Predefined Examples Accordion -->
        <div class="p-4 flex-1 space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                <i class="fa-solid fa-flask-vial text-amber-400"></i> Ejemplos para Probar
            </h3>

            <div class="space-y-3 text-xs">
                @foreach($examples as $catKey => $catData)
                    <div class="bg-slate-950/60 border border-slate-800/80 rounded-xl p-3 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold text-slate-200">{{ $catData['title'] }}</span>
                        </div>
                        <div class="space-y-1.5">
                            @foreach($catData['valid'] as $validSentence)
                                <button type="button" onclick="sendCustomMessage('{{ addslashes($validSentence) }}', '{{ $catKey }}')" class="w-full text-left px-2.5 py-1.5 rounded-lg bg-slate-900 hover:bg-indigo-950/50 hover:border-indigo-500/40 border border-slate-800/80 text-emerald-300 font-mono text-[11px] transition flex items-center justify-between group">
                                    <span class="truncate">"{{ $validSentence }}"</span>
                                    <i class="fa-solid fa-arrow-right opacity-0 group-hover:opacity-100 transition text-indigo-400 text-[10px]"></i>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Footer Stats -->
        <div class="p-3 bg-slate-950/80 border-t border-slate-800 text-[11px] text-slate-400 flex items-center justify-between">
            <span>Evaluadas: <strong id="statTotal" class="text-white">0</strong></span>
            <span class="text-emerald-400">Válidas: <strong id="statValid">0</strong></span>
            <span class="text-rose-400">Inválidas: <strong id="statInvalid">0</strong></span>
        </div>
    </aside>

    <!-- Right Area: Chat Interactive Interface -->
    <section class="flex-1 flex flex-col h-full overflow-hidden bg-slate-950/40 relative">
        
        <!-- Chat Message Stream -->
        <div id="chatMessages" class="flex-1 overflow-y-auto p-4 md:p-6 space-y-4">
            
            <!-- Welcome Bot Message -->
            <div class="chat-bubble-bot flex items-start gap-3 max-w-3xl">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center text-white shrink-0 shadow-md">
                    <i class="fa-solid fa-robot text-sm"></i>
                </div>
                <div class="space-y-2 bg-slate-900 border border-slate-800 rounded-2xl rounded-tl-none p-4 shadow-sm text-sm text-slate-200">
                    <div class="flex items-center justify-between border-b border-slate-800/80 pb-2">
                        <span class="font-bold text-white text-xs flex items-center gap-1.5">
                            RegexBot <span class="text-[10px] font-normal text-slate-400">Asistente de Gramática & Autómatas</span>
                        </span>
                        <span class="text-[10px] text-slate-500">Ahora</span>
                    </div>
                    <p>¡Hola! 👋 Soy <strong>RegexBot</strong>. Te ayudaré a validar oraciones interrogativas (<em>Questions</em>) en inglés con el <strong>Verbo TO BE</strong> en tiempo presente y pasado utilizando <strong>Expresiones Regulares</strong>.</p>
                    
                    <div id="initialNamePrompt" class="pt-2">
                        <p class="text-xs text-indigo-300 font-medium mb-2">Para comenzar, ¿cuál es tu nombre?</p>
                        <div class="flex gap-2 max-w-md">
                            <input type="text" id="userNameInput" placeholder="Ej: Carlos..." class="flex-1 px-3 py-1.5 rounded-lg bg-slate-950 border border-slate-700 text-xs text-white focus:outline-none focus:border-indigo-500">
                            <button onclick="setUserName()" class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold transition">
                                Guardar
                            </button>
                            <button onclick="skipUserName()" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs transition">
                                Omitir
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Chat Input Footer -->
        <div class="p-3 md:p-4 bg-slate-900/90 border-t border-slate-800/80 backdrop-blur">
            <form id="chatForm" onsubmit="handleFormSubmit(event)" class="max-w-4xl mx-auto space-y-2">
                <div class="flex items-center gap-2">
                    <div class="relative flex-1">
                        <input 
                            type="text" 
                            id="sentenceInput" 
                            placeholder="Escribe una pregunta en inglés (Ej: Is she a nice girl?)..." 
                            class="w-full pl-4 pr-10 py-3 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-mono transition"
                            autocomplete="off"
                        >
                        <button type="button" onclick="clearInput()" title="Limpiar" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 text-xs p-1">
                            <i class="fa-solid fa-eraser"></i>
                        </button>
                    </div>

                    <button 
                        type="submit" 
                        id="sendButton"
                        class="px-5 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-semibold text-sm shadow-lg shadow-indigo-600/30 transition flex items-center gap-2 shrink-0 disabled:opacity-50"
                    >
                        <span>Validar</span>
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                    </button>
                </div>

                <div class="flex flex-wrap items-center justify-between text-[11px] text-slate-400 px-1 gap-2">
                    <div class="flex items-center gap-1.5">
                        <i class="fa-solid fa-lightbulb text-amber-400"></i>
                        <span>Fórmula actual: <code id="activeFormula" class="text-indigo-300 font-mono">Detección Automática</code></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="sendCustomMessage('Where is the cat?', 'WH_QUESTION')" class="hover:text-indigo-300 underline">Ejemplo Wh-</button>
                        <span>&bull;</span>
                        <button type="button" onclick="sendCustomMessage('Were you a good student?', 'PAST_WAS_WERE')" class="hover:text-indigo-300 underline">Ejemplo Pasado</button>
                    </div>
                </div>
            </form>
        </div>

    </section>

</div>
@endsection

@push('scripts')
<script>
    let currentUserName = 'Estudiante';
    let currentCategory = null;
    let totalEvaluated = 0;
    let totalValid = 0;
    let totalInvalid = 0;

    const categoryNames = {
        'YES_NO_PRESENT': 'Yes/No Questions (Presente)',
        'WH_QUESTION': 'Wh- Questions (Información)',
        'PAST_WAS_WERE': 'Questions Pasado (Was / Were)'
    };

    const categoryFormulas = {
        'YES_NO_PRESENT': 'Am / Is / Are + Sujeto + Complemento + ?',
        'WH_QUESTION': 'Wh- Word + To Be + Sujeto + Complemento + ?',
        'PAST_WAS_WERE': 'Was / Were + Sujeto + Complemento + ?'
    };

    function setUserName() {
        const input = document.getElementById('userNameInput').value.trim();
        if (input) {
            currentUserName = input;
        }
        document.getElementById('initialNamePrompt').innerHTML = `
            <div class="p-2.5 rounded-lg bg-emerald-950/40 border border-emerald-500/30 text-emerald-300 text-xs font-medium flex items-center justify-between">
                <span>¡Mucho gusto, <strong>${currentUserName}</strong>! 🎉</span>
                <span class="text-[10px] text-slate-400">Listo para practicar</span>
            </div>
        `;
        promptCategorySelection();
    }

    function skipUserName() {
        document.getElementById('initialNamePrompt').innerHTML = `
            <div class="p-2 rounded-lg bg-slate-800 text-slate-400 text-xs">Continuando como <strong>Estudiante</strong>.</div>
        `;
        promptCategorySelection();
    }

    function promptCategorySelection() {
        appendBotMessage(`
            <p>Por favor, selecciona qué tipo de pregunta deseas realizar o escribe directamente cualquier oración interrogativa:</p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 pt-2 text-xs">
                <button onclick="selectCategory('YES_NO_PRESENT', 'Yes/No Questions (Presente)')" class="p-2.5 rounded-xl bg-slate-950 hover:bg-slate-800 border border-blue-500/30 text-left text-blue-300 transition">
                    <p class="font-bold flex items-center gap-1.5"><i class="fa-solid fa-question"></i> Yes/No (Presente)</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Is she a nice girl?</p>
                </button>
                <button onclick="selectCategory('WH_QUESTION', 'Wh- Questions (Información)')" class="p-2.5 rounded-xl bg-slate-950 hover:bg-slate-800 border border-purple-500/30 text-left text-purple-300 transition">
                    <p class="font-bold flex items-center gap-1.5"><i class="fa-solid fa-info"></i> Wh- Questions</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Where is the cat?</p>
                </button>
                <button onclick="selectCategory('PAST_WAS_WERE', 'Questions Pasado (Was/Were)')" class="p-2.5 rounded-xl bg-slate-950 hover:bg-slate-800 border border-emerald-500/30 text-left text-emerald-300 transition">
                    <p class="font-bold flex items-center gap-1.5"><i class="fa-solid fa-clock-rotate-left"></i> Pasado (Was / Were)</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Were you a good student?</p>
                </button>
            </div>
        `);
    }

    function selectCategory(catKey, catTitle) {
        currentCategory = catKey;
        
        // Update sidebar buttons UI
        document.querySelectorAll('.category-btn').forEach(btn => {
            const btnCat = btn.getAttribute('data-category');
            const check = btn.querySelector('.checkmark');
            if (btnCat === (catKey || '')) {
                btn.classList.add('border-indigo-500/50', 'bg-indigo-950/40');
                btn.classList.remove('border-slate-800', 'bg-slate-900/60');
                if (check) check.classList.remove('hidden');
            } else {
                btn.classList.remove('border-indigo-500/50', 'bg-indigo-950/40');
                btn.classList.add('border-slate-800', 'bg-slate-900/60');
                if (check) check.classList.add('hidden');
            }
        });

        // Update badge and formula
        const badge = document.getElementById('selectedCategoryBadge');
        const formulaElem = document.getElementById('activeFormula');
        
        if (catKey) {
            badge.innerText = catKey.replace('_', ' ');
            badge.className = "text-[10px] font-semibold px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 border border-indigo-500/30";
            formulaElem.innerText = categoryFormulas[catKey] || catTitle;
            appendBotMessage(`Has seleccionado la categoría: <strong class="text-indigo-300">${catTitle}</strong>.<br><span class="text-xs text-slate-400 font-mono">Fórmula: ${categoryFormulas[catKey]}</span><br>¡Ingresa tu frase para validarla! ✍️`);
        } else {
            badge.innerText = "Auto";
            formulaElem.innerText = "Detección Automática";
            appendBotMessage(`Modo cambiado a <strong class="text-indigo-300">Detección Automática</strong>. Puedes escribir cualquier tipo de pregunta con TO BE.`);
        }

        document.getElementById('sentenceInput').focus();
    }

    function handleFormSubmit(e) {
        e.preventDefault();
        const inputElem = document.getElementById('sentenceInput');
        const message = inputElem.value.trim();
        if (!message) return;

        validateSentence(message, currentCategory);
        inputElem.value = '';
    }

    function sendCustomMessage(sentence, category = null) {
        if (category) {
            currentCategory = category;
            selectCategory(category, categoryNames[category] || category);
        }
        validateSentence(sentence, currentCategory);
    }

    async function validateSentence(sentence, category) {
        // Render user message bubble
        appendUserMessage(sentence);

        // Show typing indicator
        const typingId = showTypingIndicator();

        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const res = await fetch('/api/validate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message: sentence,
                    type: category,
                    user_name: currentUserName
                })
            });

            const data = await res.json();
            removeTypingIndicator(typingId);

            if (data.success) {
                renderValidationResult(data);
                updateStats(data.validation.is_valid);
            } else {
                appendBotMessage(`Ocurrió un error al procesar la oración.`);
            }

        } catch (err) {
            removeTypingIndicator(typingId);
            appendBotMessage(`⚠️ Error de conexión al validar la oración.`);
            console.error(err);
        }
    }

    function renderValidationResult(data) {
        const val = data.validation;
        const isValid = val.is_valid;
        
        let cardHtml = `
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold ${isValid ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30'}">
                        <i class="fa-solid ${isValid ? 'fa-circle-check text-emerald-400' : 'fa-circle-xmark text-rose-400'}"></i>
                        ${isValid ? 'ORACIÓN VÁLIDA' : 'ORACIÓN INVÁLIDA'}
                    </span>
                    <span class="text-[11px] text-slate-400 font-medium">${val.type_name || (val.type ? categoryNames[val.type] : 'Verbo TO BE')}</span>
                </div>

                <p class="text-xs text-slate-200">${val.feedback}</p>
        `;

        if (isValid && val.components) {
            const comp = val.components;
            cardHtml += `
                <div class="bg-slate-950/80 rounded-xl p-3 border border-slate-800 space-y-2 text-xs">
                    <div class="font-semibold text-slate-300 flex items-center justify-between border-b border-slate-800 pb-1.5">
                        <span><i class="fa-solid fa-diagram-project text-indigo-400 mr-1"></i> Desglose Sintáctico (Tokens Regex):</span>
                        <span class="text-[10px] text-indigo-300 font-mono">${comp.tense}</span>
                    </div>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-[11px]">
                        ${comp.wh_word ? `
                            <div class="bg-slate-900 p-2 rounded border border-purple-500/20">
                                <span class="text-slate-400 block text-[10px]">Wh- Word:</span>
                                <span class="font-mono text-purple-300 font-semibold">${comp.wh_word}</span>
                            </div>
                        ` : ''}
                        
                        <div class="bg-slate-900 p-2 rounded border border-indigo-500/20">
                            <span class="text-slate-400 block text-[10px]">Verbo TO BE:</span>
                            <span class="font-mono text-indigo-300 font-semibold">${comp.verb}</span>
                        </div>

                        <div class="bg-slate-900 p-2 rounded border border-blue-500/20">
                            <span class="text-slate-400 block text-[10px]">Sujeto:</span>
                            <span class="font-mono text-blue-300 font-semibold">${comp.subject}</span>
                            <span class="text-[9px] text-slate-400 block truncate">${comp.subject_type}</span>
                        </div>

                        <div class="bg-slate-900 p-2 rounded border border-slate-800">
                            <span class="text-slate-400 block text-[10px]">Complemento:</span>
                            <span class="font-mono text-slate-200 truncate block">${comp.complement}</span>
                        </div>
                    </div>

                    ${val.pattern_used ? `
                        <div class="pt-1.5 border-t border-slate-800/80">
                            <details class="cursor-pointer">
                                <summary class="text-[10px] text-slate-400 hover:text-indigo-300 select-none">🔍 Ver Expresión Regular coincidente</summary>
                                <div class="mt-1.5 p-2 rounded bg-slate-900 text-[10px] font-mono text-emerald-300 overflow-x-auto border border-slate-800 break-all">
                                    ${escapeHtml(val.pattern_used)}
                                </div>
                            </details>
                        </div>
                    ` : ''}
                </div>
            `;
        }

        if (!isValid) {
            cardHtml += `
                <div class="bg-rose-950/20 rounded-xl p-3 border border-rose-500/20 space-y-2 text-xs">
                    ${val.details ? `<p class="text-slate-300"><strong class="text-rose-300">Diagnóstico:</strong> ${val.details}</p>` : ''}
                    ${val.suggestion ? `
                        <div class="bg-slate-900/90 p-2 rounded border border-slate-800 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-slate-400 block">Sugerencia de corrección:</span>
                                <span class="font-mono text-emerald-300 text-xs font-semibold">"${val.suggestion}"</span>
                            </div>
                            <button onclick="sendCustomMessage('${escapeHtml(val.suggestion)}', '${val.type || ''}')" class="px-2.5 py-1 rounded bg-indigo-600 hover:bg-indigo-500 text-white text-[11px] font-medium transition">
                                Probar corrección
                            </button>
                        </div>
                    ` : ''}
                </div>
            `;
        }

        // Quick Reply Buttons
        cardHtml += `
            <div class="pt-2 border-t border-slate-800/60 flex flex-wrap gap-2 text-xs">
                <button onclick="document.getElementById('sentenceInput').focus()" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 transition">
                    ✨ Probar otra frase
                </button>
                <button onclick="promptCategorySelection()" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 transition">
                    🔄 Cambiar tipo
                </button>
                <button onclick="document.getElementById('docsModal').classList.remove('hidden')" class="px-2.5 py-1 rounded-lg bg-indigo-900/40 hover:bg-indigo-900/60 text-indigo-300 border border-indigo-500/30 transition">
                    📚 Ver ejemplos en docs (.md)
                </button>
            </div>
        </div>
        `;

        appendBotMessage(cardHtml);
    }

    function appendUserMessage(text) {
        const chat = document.getElementById('chatMessages');
        const userDiv = document.createElement('div');
        userDiv.className = 'chat-bubble-user flex items-start justify-end gap-3 max-w-3xl ml-auto';
        userDiv.innerHTML = `
            <div class="space-y-1 bg-gradient-to-r from-indigo-600 to-violet-600 text-white rounded-2xl rounded-tr-none px-4 py-3 shadow-md text-sm font-mono max-w-md break-words">
                <div class="flex items-center justify-between gap-4 border-b border-indigo-400/30 pb-1 text-[10px] text-indigo-200 font-sans">
                    <span class="font-semibold">${escapeHtml(currentUserName)}</span>
                    <span>Pregunta enviada</span>
                </div>
                <p class="pt-0.5 font-medium">${escapeHtml(text)}</p>
            </div>
            <div class="w-9 h-9 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-indigo-300 shrink-0 font-bold text-xs">
                <i class="fa-solid fa-user"></i>
            </div>
        `;
        chat.appendChild(userDiv);
        scrollToBottom();
    }

    function appendBotMessage(contentHtml) {
        const chat = document.getElementById('chatMessages');
        const botDiv = document.createElement('div');
        botDiv.className = 'chat-bubble-bot flex items-start gap-3 max-w-3xl';
        botDiv.innerHTML = `
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center text-white shrink-0 shadow-md">
                <i class="fa-solid fa-robot text-sm"></i>
            </div>
            <div class="space-y-2 bg-slate-900 border border-slate-800 rounded-2xl rounded-tl-none p-4 shadow-sm text-sm text-slate-200 flex-1">
                <div class="flex items-center justify-between border-b border-slate-800/80 pb-2">
                    <span class="font-bold text-white text-xs flex items-center gap-1.5">
                        RegexBot <span class="text-[10px] font-normal text-slate-400">Validador TO BE</span>
                    </span>
                    <span class="text-[10px] text-slate-500">Ahora</span>
                </div>
                ${contentHtml}
            </div>
        `;
        chat.appendChild(botDiv);
        scrollToBottom();
    }

    function showTypingIndicator() {
        const id = 'typing_' + Date.now();
        const chat = document.getElementById('chatMessages');
        const typingDiv = document.createElement('div');
        typingDiv.id = id;
        typingDiv.className = 'chat-bubble-bot flex items-start gap-3 max-w-md';
        typingDiv.innerHTML = `
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center text-white shrink-0">
                <i class="fa-solid fa-robot text-sm"></i>
            </div>
            <div class="bg-slate-900 border border-slate-800 rounded-2xl rounded-tl-none px-4 py-3 flex items-center gap-1.5 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-indigo-400 typing-dot"></span>
                <span class="w-2 h-2 rounded-full bg-indigo-400 typing-dot"></span>
                <span class="w-2 h-2 rounded-full bg-indigo-400 typing-dot"></span>
            </div>
        `;
        chat.appendChild(typingDiv);
        scrollToBottom();
        return id;
    }

    function removeTypingIndicator(id) {
        const elem = document.getElementById(id);
        if (elem) elem.remove();
    }

    function scrollToBottom() {
        const chat = document.getElementById('chatMessages');
        chat.scrollTop = chat.scrollHeight;
    }

    function clearInput() {
        const input = document.getElementById('sentenceInput');
        input.value = '';
        input.focus();
    }

    function updateStats(isValid) {
        totalEvaluated++;
        if (isValid) totalValid++;
        else totalInvalid++;

        document.getElementById('statTotal').innerText = totalEvaluated;
        document.getElementById('statValid').innerText = totalValid;
        document.getElementById('statInvalid').innerText = totalInvalid;
    }

    function escapeHtml(str) {
        if (!str) return '';
        return str
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
</script>
@endpush
