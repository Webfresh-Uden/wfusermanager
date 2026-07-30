<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 grid-cols-1">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="absolute right-0 top-0 p-4 z-20">
                    <flux:modal.trigger name="group-write">
                        <flux:button>{{ __('Create group') }}</flux:button>
                    </flux:modal.trigger>
                </div>
                <div class="z-10 mt-4 mb-4">
                    <flux:heading size="xl" level="1" class="ms-4">{{ __('Permission group management') }}</flux:heading>
                </div>
                <div class="ms-4 me-4">
                    <flux:table :paginate="$this->groups" class="z-10 table-fixed">
                        <flux:table.columns>
                            <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">{{ __('Name') }}</flux:table.column>
                            <flux:table.column class="max-w-32">{{ __('Actions') }}</flux:table.column>
                        </flux:table.columns>
                        <flux:table.rows>
                            @foreach( $groups as $group )
                                <flux:table.row wire:key="group-{{ $group->id }}">
                                    <flux:table.cell class="w-full">
                                        <div class="flex items-center gap-2">
                                            <span>{{ $group->name }}</span>
                                        </div>
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        <flux:tooltip content="{{ __('Update group') }}">
                                            <flux:icon.pencil-square class="cursor-pointer text-gray-500 inline-block" wire:click="showWriteGroupModal({{ $group->id }})" />
                                        </flux:tooltip>
                                        <flux:tooltip content="{{ __('Delete group') }}">
                                            <flux:icon.x-circle class="cursor-pointer text-red-500 inline-block" wire:click="showDeleteGroupModal({{ $group->id }})" />
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

    <flux:modal name="group-delete" class="md:w-96" wire:model.self="showGroupDeleteModal">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Delete group') }}</flux:heading>
                <flux:text class="mt-2">{{ __('Are you sure you want to delete this group? This action cannot be undone.') }}</flux:text>
            </div>
            <div class="flex gap-2">
                <flux:button variant="outline" @click="showGroupDeleteModal = false">{{ __('Cancel') }}</flux:button>
                <flux:button variant="danger" wire:click="deleteGroupAction()">{{ __('Delete group') }}</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="group-write" class="md:w-96" wire:model.self="showGroupWriteModal" wire:close="clearFieldData()">
        <div class="space-y-6">
            <div>
                @if( $this->id !== '' )
                    <flux:heading size="lg">{{ __('Update group') }}</flux:heading>
                    <flux:text class="mt-2">{{ __('Update the selected group') }}</flux:text>
                @else
                    <flux:heading size="lg">{{ __('Create group') }}</flux:heading>
                    <flux:text class="mt-2">{{ __('Create a new group by providing a name below') }}</flux:text>
                @endif
            </div>
            <form wire:submit="writeGroupAction">
                <flux:input wire:model="name" label="{{ __('Name') }}" placeholder="{{ __('Group name') }}" class="mb-4" />
                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">{{ __('Save group') }}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

</div>
