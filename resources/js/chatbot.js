let currentUserName = 'Estudiante';
let currentCategory = null;
let totalEvaluated = 0;
let totalValid = 0;
let totalInvalid = 0;

const categoryNames = {
    YES_NO_PRESENT: 'Yes/No Questions (Presente)',
    WH_QUESTION: 'Wh- Questions (Información)',
    PAST_WAS_WERE: 'Questions Pasado (Was / Were)',
};

const categoryFormulas = {
    YES_NO_PRESENT: 'Am / Is / Are + Sujeto + Complemento + ?',
    WH_QUESTION: 'Wh- Word + To Be + Sujeto + Complemento + ?',
    PAST_WAS_WERE: 'Was / Were + Sujeto + Complemento + ?',
};

function setUserName() {
    const input = document.getElementById('userNameInput').value.trim();

    if (input) {
        currentUserName = input;
    }

    document.getElementById('initialNamePrompt').innerHTML = `
        <div class="rounded-lg border border-zinc-800 bg-zinc-900 px-3 py-2 text-xs text-zinc-300">
            Mucho gusto, <strong class="text-zinc-100">${escapeHtml(currentUserName)}</strong>. Ya puedes practicar.
        </div>
    `;
    promptCategorySelection();
}

function skipUserName() {
    document.getElementById('initialNamePrompt').innerHTML = `
        <div class="rounded-lg border border-zinc-800 bg-zinc-900 px-3 py-2 text-xs text-zinc-400">
            Continuando como <strong class="text-zinc-200">Estudiante</strong>.
        </div>
    `;
    promptCategorySelection();
}

function promptCategorySelection() {
    appendBotMessage(`
        <p>Selecciona un tipo de pregunta o escribe directamente una oración interrogativa.</p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 pt-2 text-xs">
            ${categoryPromptButton('YES_NO_PRESENT', 'Yes/No Questions (Presente)', 'Yes/No Presente', 'Is she a nice girl?')}
            ${categoryPromptButton('WH_QUESTION', 'Wh- Questions (Información)', 'Wh- Questions', 'Where is the cat?')}
            ${categoryPromptButton('PAST_WAS_WERE', 'Questions Pasado (Was/Were)', 'Pasado', 'Were you a good student?')}
        </div>
    `);
}

function categoryPromptButton(category, title, label, example) {
    return `
        <button
            type="button"
            data-chat-category="${category}"
            data-chat-title="${escapeHtml(title)}"
            class="rounded-lg border border-zinc-800 bg-zinc-950 p-3 text-left text-zinc-300 transition hover:bg-zinc-900 hover:text-zinc-100"
        >
            <p class="font-medium">${label}</p>
            <p class="text-[10px] text-zinc-500 mt-1">${example}</p>
        </button>
    `;
}

function selectCategory(catKey, catTitle) {
    catKey = catKey || null;
    currentCategory = catKey;

    document.querySelectorAll('.category-btn').forEach((button) => {
        const buttonCategory = button.getAttribute('data-category');
        const check = button.querySelector('.checkmark');

        if (buttonCategory === (catKey || '')) {
            button.classList.add('border-zinc-600', 'bg-zinc-900', 'text-zinc-100');
            button.classList.remove('border-transparent', 'bg-transparent', 'text-zinc-400');
            check?.classList.remove('hidden');
        } else {
            button.classList.remove('border-zinc-600', 'bg-zinc-900', 'text-zinc-100');
            button.classList.add('border-transparent', 'bg-transparent', 'text-zinc-400');
            check?.classList.add('hidden');
        }
    });

    const badge = document.getElementById('selectedCategoryBadge');
    const formulaElement = document.getElementById('activeFormula');

    if (catKey) {
        badge.innerText = catKey.replaceAll('_', ' ');
        formulaElement.innerText = categoryFormulas[catKey] || catTitle;
        appendBotMessage(`Categoría seleccionada: <strong>${escapeHtml(catTitle)}</strong>.<br><span class="text-xs text-zinc-500 font-mono">Fórmula: ${escapeHtml(categoryFormulas[catKey])}</span>`);
    } else {
        badge.innerText = 'Auto';
        formulaElement.innerText = 'Detección Automática';
        appendBotMessage('Modo cambiado a <strong>Detección Automática</strong>. Puedes escribir cualquier tipo de pregunta con TO BE.');
    }

    document.getElementById('sentenceInput').focus();
}

function handleFormSubmit(event) {
    event.preventDefault();

    const inputElement = document.getElementById('sentenceInput');
    const message = inputElement.value.trim();

    if (!message) {
        return;
    }

    validateSentence(message, currentCategory);
    inputElement.value = '';
}

function sendCustomMessage(sentence, category = null) {
    if (category) {
        currentCategory = category;
        selectCategory(category, categoryNames[category] || category);
    }

    validateSentence(sentence, currentCategory);
}

async function validateSentence(sentence, category) {
    appendUserMessage(sentence);

    const typingId = showTypingIndicator();

    try {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch(getValidateUrl(), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                Accept: 'application/json',
            },
            body: JSON.stringify({
                message: sentence,
                type: category,
                user_name: currentUserName,
            }),
        });

        const data = await response.json();
        removeTypingIndicator(typingId);

        if (data.success) {
            renderValidationResult(data);
            updateStats(data.validation.is_valid);
        } else {
            appendBotMessage('Ocurrió un error al procesar la oración.');
        }
    } catch (error) {
        removeTypingIndicator(typingId);
        appendBotMessage('Error de conexión al validar la oración.');
        console.error(error);
    }
}

function renderValidationResult(data) {
    const validation = data.validation;
    const isValid = validation.is_valid;

    let cardHtml = `
        <div class="space-y-3">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium ${isValid ? 'border-zinc-600 bg-zinc-900 text-zinc-100' : 'border-zinc-800 bg-zinc-950 text-zinc-400'}">
                    ${isValid ? 'Oración válida' : 'Oración inválida'}
                </span>
                <span class="text-[11px] text-zinc-500 font-medium">${escapeHtml(validation.type_name || (validation.type ? categoryNames[validation.type] : 'Verbo TO BE'))}</span>
            </div>

            <p class="text-sm text-zinc-300">${escapeHtml(validation.feedback)}</p>
    `;

    if (isValid && validation.components) {
        cardHtml += validationComponents(validation);
    }

    if (!isValid) {
        cardHtml += invalidFeedback(validation);
    }

    cardHtml += `
            <div class="pt-2 border-t border-zinc-900 flex flex-wrap gap-2 text-xs">
                <button type="button" data-chat-action="focus-input" class="rounded-md bg-zinc-900 px-3 py-1.5 text-zinc-300 transition hover:bg-zinc-800 hover:text-zinc-100">
                    Probar otra frase
                </button>
                <button type="button" data-chat-action="change-type" class="rounded-md bg-zinc-900 px-3 py-1.5 text-zinc-300 transition hover:bg-zinc-800 hover:text-zinc-100">
                    Cambiar tipo
                </button>
                <button type="button" data-chat-action="open-docs" class="rounded-md border border-zinc-800 px-3 py-1.5 text-zinc-300 transition hover:bg-zinc-900 hover:text-zinc-100">
                    Ver documentación
                </button>
            </div>
        </div>
    `;

    appendBotMessage(cardHtml);
}

async function loadConversationHistory() {
    const historyUrl = getHistoryUrl();

    if (!historyUrl) {
        return;
    }

    try {
        const response = await fetch(historyUrl, {
            headers: {
                Accept: 'application/json',
            },
        });
        const data = await response.json();
        const messages = data.conversation?.messages || [];

        if (data.conversation?.user_name) {
            currentUserName = data.conversation.user_name;
        }

        messages.forEach((message) => {
            appendUserMessage(message.user_message);
            renderValidationResult({ validation: message.validation });
            updateStats(message.is_valid);
        });
    } catch (error) {
        console.error(error);
    }
}

function validationComponents(validation) {
    const components = validation.components;

    return `
        <div class="rounded-lg border border-zinc-800 bg-zinc-950 p-3 text-xs space-y-3">
            <div class="flex items-center justify-between border-b border-zinc-900 pb-2">
                <span class="font-medium text-zinc-300">Desglose sintáctico</span>
                <span class="text-[10px] text-zinc-500 font-mono">${escapeHtml(components.tense)}</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-[11px]">
                ${components.wh_word ? componentToken('Wh- Word', components.wh_word) : ''}
                ${componentToken('Verbo TO BE', components.verb)}
                ${subjectToken(components)}
                ${componentToken('Complemento', components.complement)}
            </div>

            ${validation.pattern_used ? regexDetails(validation.pattern_used) : ''}
        </div>
    `;
}

function componentToken(label, value) {
    return `
        <div class="rounded-md border border-zinc-900 bg-black p-2">
            <span class="text-zinc-500 block text-[10px]">${label}</span>
            <span class="font-mono font-medium text-zinc-200">${escapeHtml(value)}</span>
        </div>
    `;
}

function subjectToken(components) {
    return `
        <div class="rounded-md border border-zinc-900 bg-black p-2">
            <span class="text-zinc-500 block text-[10px]">Sujeto</span>
            <span class="font-mono text-zinc-200 font-medium">${escapeHtml(components.subject)}</span>
            <span class="text-[9px] text-zinc-600 block truncate">${escapeHtml(components.subject_type)}</span>
        </div>
    `;
}

function regexDetails(pattern) {
    return `
        <div class="pt-1 border-t border-zinc-900">
            <details class="cursor-pointer">
                <summary class="text-[10px] text-zinc-500 hover:text-zinc-300 select-none">Ver expresión regular coincidente</summary>
                <div class="mt-2 rounded-md border border-zinc-900 bg-black p-2 text-[10px] font-mono text-zinc-300 overflow-x-auto break-all">
                    ${escapeHtml(pattern)}
                </div>
            </details>
        </div>
    `;
}

function invalidFeedback(validation) {
    return `
        <div class="rounded-lg border border-zinc-800 bg-zinc-950 p-3 text-xs space-y-2">
            ${validation.details ? `<p class="text-zinc-300"><strong class="text-zinc-100">Diagnóstico:</strong> ${escapeHtml(validation.details)}</p>` : ''}
            ${validation.suggestion ? correctionSuggestion(validation) : ''}
        </div>
    `;
}

function correctionSuggestion(validation) {
    return `
        <div class="rounded-md border border-zinc-900 bg-black p-2 flex items-center justify-between gap-3">
            <div>
                <span class="text-[10px] text-zinc-500 block">Sugerencia de corrección:</span>
                <span class="font-mono text-zinc-200 text-xs font-medium">"${escapeHtml(validation.suggestion)}"</span>
            </div>
            <button
                type="button"
                data-chat-action="try-correction"
                data-sentence="${escapeHtml(validation.suggestion)}"
                data-category="${escapeHtml(validation.type || '')}"
                class="rounded-md bg-zinc-100 px-3 py-1.5 text-black text-[11px] font-medium transition hover:bg-white"
            >
                Probar
            </button>
        </div>
    `;
}

function appendUserMessage(text) {
    const chat = document.getElementById('chatMessages');
    const userDiv = document.createElement('div');
    userDiv.className = 'chat-bubble-user max-w-3xl mx-auto flex justify-end';
    userDiv.innerHTML = `
        <div class="max-w-[80%] rounded-2xl bg-zinc-800 px-4 py-3 text-sm text-zinc-100 font-mono break-words">
            ${escapeHtml(text)}
        </div>
    `;
    chat.appendChild(userDiv);
    scrollToBottom();
}

function appendBotMessage(contentHtml) {
    const chat = document.getElementById('chatMessages');
    const botDiv = document.createElement('div');
    botDiv.className = 'chat-bubble-bot max-w-3xl mx-auto';
    botDiv.innerHTML = `
        <div class="space-y-3 text-sm text-zinc-200">
            <p class="text-xs font-medium text-zinc-500">BOT</p>
            ${contentHtml}
        </div>
    `;
    chat.appendChild(botDiv);
    scrollToBottom();
}

function showTypingIndicator() {
    const id = `typing_${Date.now()}`;
    const chat = document.getElementById('chatMessages');
    const typingDiv = document.createElement('div');
    typingDiv.id = id;
    typingDiv.className = 'chat-bubble-bot max-w-3xl mx-auto';
    typingDiv.innerHTML = `
        <div class="inline-flex items-center gap-1.5 rounded-full border border-zinc-900 bg-zinc-950 px-3 py-2">
            <span class="w-1.5 h-1.5 rounded-full bg-zinc-500 typing-dot"></span>
            <span class="w-1.5 h-1.5 rounded-full bg-zinc-500 typing-dot"></span>
            <span class="w-1.5 h-1.5 rounded-full bg-zinc-500 typing-dot"></span>
        </div>
    `;
    chat.appendChild(typingDiv);
    scrollToBottom();

    return id;
}

function removeTypingIndicator(id) {
    document.getElementById(id)?.remove();
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

function toggleSidebar() {
    document.body.classList.toggle('sidebar-collapsed');
}

function updateStats(isValid) {
    totalEvaluated++;

    if (isValid) {
        totalValid++;
    } else {
        totalInvalid++;
    }

    document.getElementById('statTotal').innerText = totalEvaluated;
    document.getElementById('statValid').innerText = totalValid;
    document.getElementById('statInvalid').innerText = totalInvalid;
}

function getValidateUrl() {
    return document.getElementById('chatbotApp')?.dataset.validateUrl || '/api/validate';
}

function getHistoryUrl() {
    return document.getElementById('chatbotApp')?.dataset.historyUrl || null;
}

function escapeHtml(value) {
    if (!value) {
        return '';
    }

    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

document.addEventListener('click', (event) => {
    const categoryButton = event.target.closest('[data-chat-category]');

    if (categoryButton) {
        selectCategory(categoryButton.dataset.chatCategory, categoryButton.dataset.chatTitle);
        return;
    }

    const actionButton = event.target.closest('[data-chat-action]');

    if (!actionButton) {
        return;
    }

    if (actionButton.dataset.chatAction === 'toggle-sidebar') {
        toggleSidebar();
    }

    if (actionButton.dataset.chatAction === 'focus-input') {
        document.getElementById('sentenceInput').focus();
    }

    if (actionButton.dataset.chatAction === 'change-type') {
        promptCategorySelection();
    }

    if (actionButton.dataset.chatAction === 'open-docs') {
        document.getElementById('docsModal').classList.remove('hidden');
    }

    if (actionButton.dataset.chatAction === 'try-correction') {
        sendCustomMessage(actionButton.dataset.sentence, actionButton.dataset.category || null);
    }
});

Object.assign(window, {
    clearInput,
    handleFormSubmit,
    promptCategorySelection,
    selectCategory,
    sendCustomMessage,
    setUserName,
    skipUserName,
    toggleSidebar,
});

document.addEventListener('DOMContentLoaded', () => {
    loadConversationHistory();
});
