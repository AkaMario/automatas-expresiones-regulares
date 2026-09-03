@props(['examples'])

<div id="chatWorkspace" class="flex-1 flex h-full w-full overflow-hidden">
    <x-chat.sidebar :examples="$examples" />
    <x-chat.panel />
</div>
