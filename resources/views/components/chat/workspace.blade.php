@props(['examples'])

<div id="chatWorkspace" class="flex-1 flex h-full w-full overflow-hidden">
    <x-chat.sidebar :examples="$examples" />
    <div class="flex min-w-0 flex-1 flex-col">
        <x-app.header />
        <x-chat.panel />
    </div>
</div>
