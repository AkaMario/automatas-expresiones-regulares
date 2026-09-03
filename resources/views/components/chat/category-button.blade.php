@props([
    'category' => null,
    'title',
    'description',
    'active' => false,
])

<button
    type="button"
    @class([
        'category-btn w-full text-left p-3 rounded-lg border transition flex items-center justify-between',
        'border-zinc-600 bg-zinc-900 text-zinc-100 active-category' => $active,
        'border-transparent bg-transparent hover:bg-zinc-900 text-zinc-400 hover:text-zinc-100' => ! $active,
    ])
    data-category="{{ $category }}"
    data-chat-category="{{ $category }}"
    data-chat-title="{{ $title }}"
>
    <div class="min-w-0">
        <p @class(['font-medium truncate', 'text-zinc-100' => $active, 'text-zinc-300' => ! $active])>{{ $title }}</p>
        <p class="text-[11px] text-zinc-500 truncate">{{ $description }}</p>
    </div>
    <i @class(['fa-solid fa-check text-xs checkmark text-zinc-400', 'hidden' => ! $active])></i>
</button>
