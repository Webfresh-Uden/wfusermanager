<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 grid-cols-1">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="absolute right-0 top-0 p-4 z-20">
                    <flux:modal.trigger name="role-write">
                        <flux:button>Create role</flux:button>
                    </flux:modal.trigger>
                </div>
                <div class="z-10 mt-4 mb-4">
                    <flux:heading size="xl" level="1" class="ms-4">Role management</flux:heading>
                </div>
                <div class="ms-4 me-4">
                    <flux:table :paginate="$this->roles" class="z-10 table-fixed">
                        <flux:table.columns>
                            <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">Name</flux:table.column>
                            <flux:table.column class="max-w-32">Actions</flux:table.column>
                        </flux:table.columns>
                        <flux:table.rows>
                            @foreach( $roles as $role )
                            <flux:table.row wire:key="role-{{ $role->id }}">
                                <flux:table.cell class="w-full">
                                    <div class="flex items-center gap-2">
                                        <span>{{ $role->name }}</span>
                                    </div>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:tooltip content="Update role">
                                        <flux:icon.pencil-square class="cursor-pointer text-orange-500 inline-block" wire:click.self="showWriteRoleModal({{ $role->id }})" />
                                    </flux:tooltip>
                                    <flux:tooltip content="Delete role">
                                        <flux:icon.x-circle class="cursor-pointer text-red-500 inline-block" wire:click.self="showDeleteRoleModal({{ $role->id }})" />
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

    <flux:modal name="role-delete" class="md:w-96" wire:model.self="showRoleDeleteModal">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete role</flux:heading>
                <flux:text class="mt-2">Are you sure you want to delete this role? This action cannot be undone.</flux:text>
            </div>
            <div class="flex gap-2">
                <flux:button variant="outline" @click="showRoleDeleteModal = false">Cancel</flux:button>
                <flux:button variant="danger" wire:click="deleteRoleAction()">Delete role</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="role-write" class="md:w-96" wire:model.self="showRoleWriteModal" @close="clearFieldData">
        <div class="space-y-6">
            <div>
                @if( $this->id !== '' )
                    <flux:heading size="lg">Update role</flux:heading>
                    <flux:text class="mt-2">Update the selected role</flux:text>
                @else
                    <flux:heading size="lg">Create role</flux:heading>
                    <flux:text class="mt-2">Create a new role by providing a name below</flux:text>
                @endif
            </div>
            <form wire:submit="writeRoleAction">
                <flux:input wire:model="name" label="Name" placeholder="Role name" class="mb-4" />
                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">Save role</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>


</div>
