<section
    id="chatbotApp"
    class="flex-1 flex flex-col h-full overflow-hidden bg-black relative"
    data-validate-url="{{ route('chat.validate') }}"
    data-history-url="{{ route('chat.history') }}"
    data-new-chat-url="{{ route('chat.conversations.store') }}"
    data-update-chat-url="{{ route('chat.conversations.update', ['conversation' => '__CONVERSATION_ID__']) }}"
    data-delete-chat-url="{{ route('chat.conversations.destroy', ['conversation' => '__CONVERSATION_ID__']) }}"
>
    <div id="chatMessages" class="flex-1 overflow-y-auto px-4 py-6 md:px-6 md:py-8 space-y-6">
        <x-chat.welcome-message />
    </div>

    <x-chat.input-form />
</section>
