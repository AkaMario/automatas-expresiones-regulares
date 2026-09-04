let currentUserName = "Estudiante";
let currentCategory = null;
let currentConversationId = null;
let initialChatMarkup = "";
let totalEvaluated = 0;
let totalValid = 0;
let totalInvalid = 0;
let pendingDeleteConversation = null;
let isWaitingForUserName = true;

const categoryNames = {
    YES_NO_PRESENT: "Yes/No Questions (Presente)",
    // WH_QUESTION: "Wh- Questions (Información)",
    // PAST_WAS_WERE: "Questions Pasado (Was / Were)",
};

const categoryFormulas = {
    YES_NO_PRESENT: "Am / Is / Are + Sujeto + Complemento + ?",
    // WH_QUESTION: "Wh- Word + To Be + Sujeto + Complemento + ?",
    // PAST_WAS_WERE: "Was / Were + Sujeto + Complemento + ?",
};

// function setUserName() {
//     const input = document.getElementById("userNameInput").value.trim();
//
//     if (input) {
//         currentUserName = input;
//     }
//
//     document.getElementById("initialNamePrompt").innerHTML = `
//         <div class="rounded-lg border border-zinc-800 bg-zinc-900 px-3 py-2 text-xs text-zinc-300">
//             Mucho gusto, <strong class="text-zinc-100">${escapeHtml(currentUserName)}</strong>. Ya puedes practicar.
//         </div>
//     `;
//     promptCategorySelection();
// }

// function skipUserName() {
//     document.getElementById("initialNamePrompt").innerHTML = `
//         <div class="rounded-lg border border-zinc-800 bg-zinc-900 px-3 py-2 text-xs text-zinc-400">
//             Continuando como <strong class="text-zinc-200">Estudiante</strong>.
//         </div>
//     `;
//     promptCategorySelection();
// }

function setUserNameFromMessage(name) {
    currentUserName = name;
    isWaitingForUserName = false;

    appendUserMessage(name);
    appendBotMessage(`
        <p>¡Mucho gusto, <strong>${escapeHtml(currentUserName)}</strong>!</p>
        <p class="text-zinc-400">Vamos paso a paso, escribe una question en present para empezar a practicar</p>
    `);
    promptCategorySelection();
    prepareQuestionInput();
}

function prepareQuestionInput() {
    const inputElement = document.getElementById("sentenceInput");
    const helperText = document.getElementById("inputHelperText");
    const activeFormula = document.getElementById("activeFormula");

    inputElement.placeholder =
        "Escribe una pregunta en inglés (Ej: Is she a nice girl?)...";
    helperText.innerText = "Pregunta activa:";
    activeFormula.parentElement.classList.remove("hidden");
}

function promptCategorySelection() {
    appendBotMessage(`
        <p>Practica preguntas Yes/No en presente o escribe directamente una oración interrogativa.</p>
        <div class="grid grid-cols-1 gap-2 pt-2 text-xs">
            ${categoryPromptButton("YES_NO_PRESENT", "Yes/No Questions (Presente)", "Yes/No Presente", "Is she a nice girl?")}
            ${
                /*
            categoryPromptButton("WH_QUESTION", "Wh- Questions (Información)", "Wh- Questions", "Where is the cat?")
            */ ""
            }
            ${
                /*
            categoryPromptButton("PAST_WAS_WERE", "Questions Pasado (Was/Were)", "Pasado", "Were you a good student?")
            */ ""
            }
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

    document.querySelectorAll(".category-btn").forEach((button) => {
        const buttonCategory = button.getAttribute("data-category");
        const check = button.querySelector(".checkmark");

        if (buttonCategory === (catKey || "")) {
            button.classList.add(
                "border-zinc-600",
                "bg-zinc-900",
                "text-zinc-100",
            );
            button.classList.remove(
                "border-transparent",
                "bg-transparent",
                "text-zinc-400",
            );
            check?.classList.remove("hidden");
        } else {
            button.classList.remove(
                "border-zinc-600",
                "bg-zinc-900",
                "text-zinc-100",
            );
            button.classList.add(
                "border-transparent",
                "bg-transparent",
                "text-zinc-400",
            );
            check?.classList.add("hidden");
        }
    });

    const badge = document.getElementById("selectedCategoryBadge");
    const formulaElement = document.getElementById("activeFormula");

    if (catKey) {
        badge.innerText = catKey.replaceAll("_", " ");
        formulaElement.innerText = categoryFormulas[catKey] || catTitle;
        appendBotMessage(
            `Categoría seleccionada: <strong>${escapeHtml(catTitle)}</strong>.<br><span class="text-xs text-zinc-500 font-mono">Fórmula: ${escapeHtml(categoryFormulas[catKey])}</span>`,
        );
    } else {
        badge.innerText = "Auto";
        formulaElement.innerText = "Detección Automática";
        appendBotMessage(
            "Modo cambiado a <strong>Detección Automática</strong>. Puedes escribir preguntas Yes/No en presente con TO BE.",
        );
    }

    document.getElementById("sentenceInput").focus();
}

function handleFormSubmit(event) {
    event.preventDefault();

    const inputElement = document.getElementById("sentenceInput");
    const message = inputElement.value.trim();

    if (!message) {
        return;
    }

    const shouldCaptureName =
        isWaitingForUserName ||
        (currentUserName === "Estudiante" &&
            totalEvaluated === 0 &&
            !message.endsWith("?"));

    if (shouldCaptureName) {
        setUserNameFromMessage(message);
    } else {
        validateSentence(message, currentCategory);
    }

    inputElement.value = "";
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
        const token = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
        const response = await fetch(getValidateUrl(), {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token,
                Accept: "application/json",
            },
            body: JSON.stringify({
                message: sentence,
                type: category,
                user_name: currentUserName,
                conversation_id: currentConversationId,
            }),
        });

        const data = await parseJsonResponse(response);
        removeTypingIndicator(typingId);

        if (response.ok && data.success) {
            currentConversationId =
                data.conversation?.id || currentConversationId;
            renderValidationResult(data);
            setStats(data.conversation?.stats);
            await loadConversationHistory(currentConversationId, {
                preserveMessages: true,
            });
        } else {
            appendBotMessage(validationRequestError(data));
        }
    } catch (error) {
        removeTypingIndicator(typingId);
        appendBotMessage(
            `Error al validar la oración. <span class="text-zinc-500">${escapeHtml(error.message)}</span>`,
        );
        console.error(error);
    }
}

async function parseJsonResponse(response) {
    const responseText = await response.text();

    try {
        return responseText ? JSON.parse(responseText) : {};
    } catch (error) {
        throw new Error(
            `El servidor respondió ${response.status}, pero no devolvió JSON válido.`,
        );
    }
}

function validationRequestError(data) {
    const typeError = data.errors?.type?.[0];
    const message =
        typeError || data.message || "Ocurrió un error al procesar la oración.";

    return escapeHtml(message);
}

function renderValidationResult(data) {
    const validation = data.validation;
    const isValid = validation.is_valid;

    let cardHtml = `
        <div class="space-y-3">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium ${isValid ? "border-zinc-600 bg-zinc-900 text-zinc-100" : "border-zinc-800 bg-zinc-950 text-zinc-400"}">
                    ${isValid ? "Oración válida" : "Oración inválida"}
                </span>
                <span class="text-[11px] text-zinc-500 font-medium">${escapeHtml(validation.type_name || (validation.type ? categoryNames[validation.type] : "Verbo TO BE"))}</span>
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
                <button type="button" data-chat-action="show-chat-summary" class="rounded-md bg-zinc-900 px-3 py-1.5 text-zinc-300 transition hover:bg-zinc-800 hover:text-zinc-100">
                    Evaluar
                </button>
            </div>
        </div>
    `;

    appendBotMessage(cardHtml);
}

function conversationStatsSummary(stats) {
    return `
        <div class="rounded-lg border border-zinc-800 bg-zinc-950 p-3 text-xs">
            <p class="mb-2 font-medium text-zinc-300">Resumen del chat actual</p>
            <div class="grid grid-cols-3 gap-2">
                ${statsToken("Evaluadas", stats.total)}
                ${statsToken("Buenas", stats.valid)}
                ${statsToken("Malas", stats.invalid)}
            </div>
        </div>
    `;
}

function statsToken(label, value) {
    return `
        <div class="rounded-md border border-zinc-900 bg-black p-2">
            <span class="block text-[10px] text-zinc-500">${label}</span>
            <span class="font-mono text-sm font-semibold text-zinc-100">${escapeHtml(value)}</span>
        </div>
    `;
}

function showCurrentChatSummary() {
    appendBotMessage(
        conversationStatsSummary({
            total: totalEvaluated,
            valid: totalValid,
            invalid: totalInvalid,
        }),
    );
}

async function loadConversationHistory(conversationId = null, options = {}) {
    const historyUrl = getHistoryUrl();

    if (!historyUrl) {
        return;
    }

    try {
        const url = new URL(historyUrl, window.location.origin);

        if (conversationId) {
            url.searchParams.set("conversation_id", conversationId);
        }

        const response = await fetch(url, {
            headers: {
                Accept: "application/json",
            },
        });
        const data = await response.json();
        renderConversationList(
            data.conversations || [],
            data.conversation?.id || null,
        );

        if (options.preserveMessages) {
            return;
        }

        renderConversation(data.conversation);
    } catch (error) {
        console.error(error);
    }
}

function renderConversation(conversation) {
    const messages = conversation?.messages || [];
    const hasMessages = messages.length > 0;
    currentConversationId = conversation?.id || null;

    resetChat();

    if (conversation?.user_name && hasMessages) {
        currentUserName = conversation.user_name;
        isWaitingForUserName = false;
        prepareQuestionInput();
    }

    messages.forEach((message) => {
        appendUserMessage(message.user_message);
        renderValidationResult({ validation: message.validation });
        updateStats(message.is_valid);
    });

    if (conversation?.stats) {
        setStats(conversation.stats);
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
                ${components.wh_word ? componentToken("Wh- Word", components.wh_word) : ""}
                ${componentToken("Verbo TO BE", components.verb)}
                ${subjectToken(components)}
                ${componentToken("Complemento", components.complement)}
            </div>

            ${validation.pattern_used ? regexDetails(validation.pattern_used) : ""}
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
            ${validation.details ? `<p class="text-zinc-300"><strong class="text-zinc-100">Diagnóstico:</strong> ${escapeHtml(validation.details)}</p>` : ""}
            ${validation.suggestion ? correctionSuggestion(validation) : ""}
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
                data-category="${escapeHtml(validation.type || "")}"
                class="rounded-md bg-zinc-100 px-3 py-1.5 text-black text-[11px] font-medium transition hover:bg-white"
            >
                Probar
            </button>
        </div>
    `;
}

function appendUserMessage(text) {
    const chat = document.getElementById("chatMessages");
    const userDiv = document.createElement("div");
    userDiv.className = "chat-bubble-user max-w-3xl mx-auto flex justify-end";
    userDiv.innerHTML = `
        <div class="max-w-[80%] rounded-2xl bg-zinc-800 px-4 py-3 text-sm text-zinc-100 font-mono break-words">
            ${escapeHtml(text)}
        </div>
    `;
    chat.appendChild(userDiv);
    scrollToBottom();
}

function appendBotMessage(contentHtml) {
    const chat = document.getElementById("chatMessages");
    const botDiv = document.createElement("div");
    botDiv.className = "chat-bubble-bot max-w-3xl mx-auto";
    botDiv.innerHTML = `
        <div class="space-y-3 text-sm text-zinc-200">
            <p class="text-xs font-medium text-zinc-500">REGULARBOT</p>
            ${contentHtml}
        </div>
    `;
    chat.appendChild(botDiv);
    scrollToBottom();
}

function showTypingIndicator() {
    const id = `typing_${Date.now()}`;
    const chat = document.getElementById("chatMessages");
    const typingDiv = document.createElement("div");
    typingDiv.id = id;
    typingDiv.className = "chat-bubble-bot max-w-3xl mx-auto";
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
    const chat = document.getElementById("chatMessages");
    chat.scrollTop = chat.scrollHeight;
}

function clearInput() {
    const input = document.getElementById("sentenceInput");
    input.value = "";
    input.focus();
}

async function startNewChat() {
    const newChatUrl = getNewChatUrl();

    if (!newChatUrl) {
        return;
    }

    try {
        const token = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
        const response = await fetch(newChatUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token,
                Accept: "application/json",
            },
        });
        const data = await response.json();

        currentConversationId = data.conversation?.id || null;
        resetChat();
        renderConversationList(data.conversations || [], currentConversationId);
        document.getElementById("sentenceInput").focus();
    } catch (error) {
        console.error(error);
        appendBotMessage("No se pudo iniciar un nuevo chat.");
    }
}

async function renameConversation(conversationId, currentTitle) {
    const title = window.prompt(
        "Editar título del chat",
        currentTitle || "Nuevo chat",
    );

    if (title === null) {
        return;
    }

    const trimmedTitle = title.trim();

    if (!trimmedTitle) {
        window.alert("El título no puede estar vacío.");
        return;
    }

    try {
        const response = await fetch(
            getConversationUrl(getUpdateChatUrl(), conversationId),
            {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": getCsrfToken(),
                    Accept: "application/json",
                },
                body: JSON.stringify({
                    title: trimmedTitle,
                }),
            },
        );

        if (!response.ok) {
            throw new Error("Rename request failed.");
        }

        const data = await response.json();
        renderConversationList(data.conversations || [], currentConversationId);
    } catch (error) {
        console.error(error);
        appendBotMessage("No se pudo editar el título del chat.");
    }
}

async function deleteConversation(conversationId, title) {
    if (!conversationId) {
        return;
    }

    try {
        const wasActiveConversation =
            Number(conversationId) === Number(currentConversationId);
        const response = await fetch(
            getConversationUrl(getDeleteChatUrl(), conversationId),
            {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": getCsrfToken(),
                    Accept: "application/json",
                },
            },
        );

        if (!response.ok) {
            throw new Error("Delete request failed.");
        }

        const data = await response.json();
        renderConversationList(
            data.conversations || [],
            data.conversation?.id || currentConversationId,
        );

        if (wasActiveConversation) {
            renderConversation(data.conversation);
        }
    } catch (error) {
        console.error(error);
        appendBotMessage("No se pudo eliminar el chat.");
    }
}

function openDeleteConversationModal(conversationId, title) {
    pendingDeleteConversation = {
        id: conversationId,
        title: title || "Nuevo chat",
    };

    let modal = document.getElementById("deleteConversationModal");

    if (!modal) {
        modal = document.createElement("div");
        modal.id = "deleteConversationModal";
        modal.className =
            "fixed inset-0 z-50 hidden items-center justify-center bg-black/70 px-4";
        modal.innerHTML = `
            <div class="w-full max-w-sm rounded-lg border border-zinc-800 bg-zinc-950 p-4 shadow-2xl shadow-black/50">
                <div class="space-y-2">
                    <p class="text-sm font-semibold text-zinc-100">Eliminar chat</p>
                    <p class="text-xs leading-5 text-zinc-400">Esta acción eliminará el historial de este chat.</p>
                    <p id="deleteConversationTitle" class="truncate rounded-md border border-zinc-800 bg-black px-3 py-2 text-xs font-medium text-zinc-200"></p>
                </div>
                <div class="mt-4 flex justify-end gap-2">
                    <button
                        type="button"
                        data-chat-action="cancel-delete-chat"
                        class="rounded-md border border-zinc-800 px-3 py-2 text-xs font-medium text-zinc-300 transition hover:bg-zinc-900 hover:text-zinc-100"
                    >
                        Cancelar
                    </button>
                    <button
                        type="button"
                        data-chat-action="confirm-delete-chat"
                        class="rounded-md bg-red-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-red-500"
                    >
                        Eliminar
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }

    modal.querySelector("#deleteConversationTitle").textContent =
        pendingDeleteConversation.title;
    modal.classList.remove("hidden");
    modal.classList.add("flex");
}

function closeDeleteConversationModal() {
    const modal = document.getElementById("deleteConversationModal");

    modal?.classList.add("hidden");
    modal?.classList.remove("flex");
    pendingDeleteConversation = null;
}

function confirmDeleteConversation() {
    if (!pendingDeleteConversation) {
        return;
    }

    const conversation = pendingDeleteConversation;

    closeDeleteConversationModal();
    deleteConversation(conversation.id, conversation.title);
}

function renderConversationList(conversations, activeConversationId) {
    const list = document.getElementById("conversationList");

    if (!list) {
        return;
    }

    if (!conversations.length) {
        list.innerHTML =
            '<p class="px-3 py-2 text-zinc-600">Sin chats guardados</p>';
        return;
    }

    list.innerHTML = conversations
        .map((conversation) => {
            const isActive =
                Number(conversation.id) === Number(activeConversationId);
            const activeClasses = isActive
                ? "border-zinc-700 bg-zinc-900 text-zinc-100"
                : "border-transparent bg-transparent text-zinc-400 hover:bg-zinc-900 hover:text-zinc-100";
            const title = conversation.title || "Nuevo chat";

            return `
            <div class="relative rounded-lg border px-3 py-2 transition ${activeClasses}">
                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        data-chat-action="open-chat"
                        data-conversation-id="${conversation.id}"
                        class="min-w-0 flex-1 text-left"
                    >
                        <span class="block truncate text-xs font-medium">${escapeHtml(title)}</span>
                        <span class="block text-[10px] text-zinc-600">${conversation.messages_count || 0} mensajes</span>
                    </button>
                    <button
                        type="button"
                        data-chat-action="toggle-chat-menu"
                        data-conversation-id="${conversation.id}"
                        class="shrink-0 rounded-md px-2 py-1 text-sm leading-none text-zinc-500 transition hover:bg-zinc-800 hover:text-zinc-100"
                        aria-label="Opciones de ${escapeHtml(title)}"
                    >
                        ...
                    </button>
                    <div class="conversation-menu absolute right-2 top-9 z-20 hidden min-w-32 overflow-hidden rounded-md border border-zinc-800 bg-zinc-950 py-1 shadow-xl shadow-black/40">
                        <button
                            type="button"
                            data-chat-action="rename-chat"
                            data-conversation-id="${conversation.id}"
                            data-conversation-title="${escapeHtml(title)}"
                            class="block w-full px-3 py-2 text-left text-[11px] text-zinc-300 transition hover:bg-zinc-900 hover:text-zinc-100"
                        >
                            Editar título
                        </button>
                        <button
                            type="button"
                            data-chat-action="delete-chat"
                            data-conversation-id="${conversation.id}"
                            data-conversation-title="${escapeHtml(title)}"
                            class="block w-full px-3 py-2 text-left text-[11px] text-red-300 transition hover:bg-red-950/40 hover:text-red-200"
                        >
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        `;
        })
        .join("");
}

function toggleConversationMenu(button) {
    const menu = button.parentElement?.querySelector(".conversation-menu");
    const isHidden = menu?.classList.contains("hidden");

    closeConversationMenus();

    if (isHidden) {
        menu.classList.remove("hidden");
    }
}

function closeConversationMenus() {
    document.querySelectorAll(".conversation-menu").forEach((menu) => {
        menu.classList.add("hidden");
    });
}

function resetChat() {
    const chat = document.getElementById("chatMessages");
    chat.innerHTML = initialChatMarkup;
    resetStats();
    currentCategory = null;
    currentUserName = "Estudiante";
    isWaitingForUserName = true;
    prepareNameInput();

    document.querySelectorAll(".category-btn").forEach((button) => {
        const buttonCategory = button.getAttribute("data-category");
        const check = button.querySelector(".checkmark");

        if (!buttonCategory) {
            button.classList.add(
                "border-zinc-600",
                "bg-zinc-900",
                "text-zinc-100",
            );
            button.classList.remove(
                "border-transparent",
                "bg-transparent",
                "text-zinc-400",
            );
            check?.classList.remove("hidden");
        } else {
            button.classList.remove(
                "border-zinc-600",
                "bg-zinc-900",
                "text-zinc-100",
            );
            button.classList.add(
                "border-transparent",
                "bg-transparent",
                "text-zinc-400",
            );
            check?.classList.add("hidden");
        }
    });

    document.getElementById("selectedCategoryBadge").innerText = "Auto";
    document.getElementById("activeFormula").innerText = "Detección Automática";
}

function prepareNameInput() {
    const inputElement = document.getElementById("sentenceInput");
    const helperText = document.getElementById("inputHelperText");
    const activeFormula = document.getElementById("activeFormula");

    inputElement.placeholder = "Escribe tu nombre para comenzar...";
    helperText.innerText = "Primero dime cómo te llamas.";
    activeFormula.parentElement.classList.add("hidden");
}

function resetStats() {
    totalEvaluated = 0;
    totalValid = 0;
    totalInvalid = 0;

    renderStats();
}

function setStats(stats) {
    if (!stats) {
        return;
    }

    totalEvaluated = Number(stats.total || 0);
    totalValid = Number(stats.valid || 0);
    totalInvalid = Number(stats.invalid || 0);

    renderStats();
}

function renderStats() {
    setElementText("statTotal", totalEvaluated);
    setElementText("statValid", totalValid);
    setElementText("statInvalid", totalInvalid);
}

function setElementText(id, value) {
    const element = document.getElementById(id);

    if (!element) {
        return;
    }

    element.innerText = value;
}

function toggleSidebar() {
    const isCollapsed = document.body.classList.toggle("sidebar-collapsed");
    const sidebarIcon = document.querySelector(
        '#chatSidebar [data-chat-action="toggle-sidebar"] .material-symbols-outlined',
    );

    if (sidebarIcon) {
        sidebarIcon.innerText = isCollapsed ? "dock_to_right" : "close";
    }
}

function updateStats(isValid) {
    totalEvaluated++;

    if (isValid) {
        totalValid++;
    } else {
        totalInvalid++;
    }

    renderStats();
}

function getValidateUrl() {
    return (
        document.getElementById("chatbotApp")?.dataset.validateUrl ||
        "/api/validate"
    );
}

function getHistoryUrl() {
    return document.getElementById("chatbotApp")?.dataset.historyUrl || null;
}

function getNewChatUrl() {
    return document.getElementById("chatbotApp")?.dataset.newChatUrl || null;
}

function getUpdateChatUrl() {
    return document.getElementById("chatbotApp")?.dataset.updateChatUrl || null;
}

function getDeleteChatUrl() {
    return document.getElementById("chatbotApp")?.dataset.deleteChatUrl || null;
}

function getConversationUrl(url, conversationId) {
    return url?.replace("__CONVERSATION_ID__", conversationId) || null;
}

function getCsrfToken() {
    return document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
}

function escapeHtml(value) {
    if (value === null || value === undefined) {
        return "";
    }

    return String(value)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

document.addEventListener("click", (event) => {
    const categoryButton = event.target.closest("[data-chat-category]");

    if (categoryButton) {
        selectCategory(
            categoryButton.dataset.chatCategory,
            categoryButton.dataset.chatTitle,
        );
        return;
    }

    const actionButton = event.target.closest("[data-chat-action]");

    if (!actionButton) {
        closeConversationMenus();
        return;
    }

    if (actionButton.dataset.chatAction !== "toggle-chat-menu") {
        closeConversationMenus();
    }

    if (actionButton.dataset.chatAction === "toggle-sidebar") {
        toggleSidebar();
    }

    if (actionButton.dataset.chatAction === "focus-input") {
        document.getElementById("sentenceInput").focus();
    }

    if (actionButton.dataset.chatAction === "show-chat-summary") {
        showCurrentChatSummary();
    }

    if (actionButton.dataset.chatAction === "change-type") {
        promptCategorySelection();
    }

    if (actionButton.dataset.chatAction === "open-docs") {
        document.getElementById("docsModal").classList.remove("hidden");
    }

    if (actionButton.dataset.chatAction === "try-correction") {
        sendCustomMessage(
            actionButton.dataset.sentence,
            actionButton.dataset.category || null,
        );
    }

    if (actionButton.dataset.chatAction === "new-chat") {
        startNewChat();
    }

    if (actionButton.dataset.chatAction === "open-chat") {
        loadConversationHistory(actionButton.dataset.conversationId);
    }

    if (actionButton.dataset.chatAction === "toggle-chat-menu") {
        toggleConversationMenu(actionButton);
    }

    if (actionButton.dataset.chatAction === "rename-chat") {
        renameConversation(
            actionButton.dataset.conversationId,
            actionButton.dataset.conversationTitle,
        );
    }

    if (actionButton.dataset.chatAction === "delete-chat") {
        openDeleteConversationModal(
            actionButton.dataset.conversationId,
            actionButton.dataset.conversationTitle,
        );
    }

    if (actionButton.dataset.chatAction === "cancel-delete-chat") {
        closeDeleteConversationModal();
    }

    if (actionButton.dataset.chatAction === "confirm-delete-chat") {
        confirmDeleteConversation();
    }
});

document.addEventListener("mousedown", (event) => {
    const modal = document.getElementById("deleteConversationModal");

    if (modal && event.target === modal) {
        closeDeleteConversationModal();
    }
});

document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
        closeDeleteConversationModal();
    }
});

Object.assign(window, {
    clearInput,
    handleFormSubmit,
    promptCategorySelection,
    selectCategory,
    sendCustomMessage,
    // setUserName,
    startNewChat,
    // skipUserName,
    toggleSidebar,
});

document.addEventListener("DOMContentLoaded", () => {
    initialChatMarkup = document.getElementById("chatMessages").innerHTML;
    loadConversationHistory();
});
