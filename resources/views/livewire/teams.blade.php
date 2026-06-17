<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 grid-cols-1">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="absolute right-0 top-0 p-4 z-20">
                    <flux:modal.trigger name="team-write">
                        <flux:button>{{ __('Create team') }}</flux:button>
                    </flux:modal.trigger>
                </div>
                <div class="z-10 mt-4 mb-4">
                    <flux:heading size="xl" level="1" class="ms-4">{{ __('Team management') }}</flux:heading>
                </div>
                <div class="ms-4 me-4">
                    <flux:table :paginate="$teams" class="z-10 table-fixed">
                        <flux:table.columns>
                            <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">{{ __('Name') }}</flux:table.column>
                            <flux:table.column class="max-w-32">{{ __('Actions') }}</flux:table.column>
                        </flux:table.columns>
                        <flux:table.rows>
                            @foreach( $teams as $team )
                            <flux:table.row wire:key="team-{{ $team->id }}">
                                <flux:table.cell class="w-full">
                                    <div class="flex items-center gap-2">
                                        <span>{{ $team->name }}</span>
                                    </div>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:tooltip content="{{ __('Update team') }}">
                                        <flux:icon.pencil-square class="cursor-pointer text-orange-500 inline-block" wire:click="showWriteTeamModal({{ $team->id }})" />
                                    </flux:tooltip>
                                    <flux:tooltip content="{{ __('Delete team') }}">
                                        <flux:icon.x-circle class="cursor-pointer text-red-500 inline-block" wire:click="showDeleteTeamModal({{ $team->id }})" />
                                    </flux:tooltip>
                                </flux:table.cell>
                            </flux:table.row>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
                </div>
            </div>
        </div>
    </div>

    <flux:modal name="team-delete" class="md:w-96" wire:model.self="showTeamDeleteModal">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __("Delete team") }}</flux:heading>
                <flux:text class="mt-2">{{ __("Are you sure you want to delete this team? This action cannot be undone.") }}</flux:text>
            </div>
            <div class="flex gap-2">
                <flux:button variant="outline" @click="showTeamDeleteModal = false">{{ __("Cancel") }}</flux:button>
                <flux:button variant="danger" wire:click="deleteTeamAction()">{{ __("Delete team") }}</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="team-write" class="md:w-96" wire:model.self="showTeamWriteModal" wire:close="clearFieldData()">
        <div class="space-y-6">
            <div>
                @if( $this->id !== '' )
                    <flux:heading size="lg">{{ __("Update team") }}</flux:heading>
                    <flux:text class="mt-2">{{ __("Update the selected team") }}</flux:text>
                @else
                    <flux:heading size="lg">{{ __("Create team") }}</flux:heading>
                    <flux:text class="mt-2">{{ __("Create a new team by providing a name below") }}</flux:text>
                @endif
            </div>
            <form wire:submit="writeTeamAction">
                <flux:input wire:model="name" label="{{ __('Name') }}" placeholder="{{ __('Team name') }}" class="mb-4" />
                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">{{ __("Save team") }}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>


</div>
