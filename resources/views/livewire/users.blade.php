<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 grid-cols-1">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <flux:heading size="xl" level="1">User management</flux:heading>
                @foreach($teams as $team)
                    <div class="flex items-center gap-2">
                        <flux:avatar :src="$team->avatar_url" :name="$team->name" size="md" />
                        <span>{{ $team->name }}</span>
                    </div>
                @endforeach

                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
    </div>
</div>
