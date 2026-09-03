<section
    id="chatbotApp"
    class="flex-1 flex flex-col h-full overflow-hidden bg-black relative"
    data-validate-url="{{ route('chat.validate') }}"
>
    <div id="chatMessages" class="flex-1 overflow-y-auto px-4 py-6 md:px-6 md:py-8 space-y-6">
        <x-chat.welcome-message />
    </div>

    <x-chat.input-form />
</section>
