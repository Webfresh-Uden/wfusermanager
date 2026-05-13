<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 grid-cols-1">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="absolute right-0 top-0 p-4 z-20">
                    <flux:modal.trigger name="permission-write">
                        <flux:button>Create permission</flux:button>
                    </flux:modal.trigger>
                </div>
                <div class="z-10 mt-4 mb-4">
                    <flux:heading size="xl" level="1" class="ms-4">Permission management</flux:heading>
                </div>
                <div class="ms-4 me-4">
                    <flux:table :paginate="$this->permissions" class="z-10 table-fixed">
                        <flux:table.columns>
                            <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">Name</flux:table.column>
                            <flux:table.column class="max-w-32">Actions</flux:table.column>
                        </flux:table.columns>
                        <flux:table.rows>
                            @foreach( $permissions as $permission )
                            <flux:table.row wire:key="permission-{{ $permission->id }}">
                                <flux:table.cell class="w-full">
                                    <div class="flex items-center gap-2">
                                        <span>{{ $permission->name }}</span>
                                    </div>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:tooltip content="Update permission">
                                        <flux:icon.pencil-square class="cursor-pointer text-orange-500 inline-block" wire:click="showWritePermissionModal({{ $permission->id }})" />
                                    </flux:tooltip>
                                    <flux:tooltip content="Delete permission">
                                        <flux:icon.x-circle class="cursor-pointer text-red-500 inline-block" wire:click="showDeletePermissionModal({{ $permission->id }})" />
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

    <flux:modal name="permission-delete" class="md:w-96" wire:model.self="showPermissionDeleteModal">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete permission</flux:heading>
                <flux:text class="mt-2">Are you sure you want to delete this permission? This action cannot be undone.</flux:text>
            </div>
            <div class="flex gap-2">
                <flux:button variant="outline" @click="showPermissionDeleteModal = false">Cancel</flux:button>
                <flux:button variant="danger" wire:click="deletePermissionAction()">Delete permission</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="permission-write" class="md:w-96" wire:model.self="showPermissionWriteModal" wire:close="clearFieldData()">
        <div class="space-y-6">
            <div>
                @if( $this->id !== '' )
                    <flux:heading size="lg">Update permission</flux:heading>
                    <flux:text class="mt-2">Update the selected permission</flux:text>
                @else
                    <flux:heading size="lg">Create permission</flux:heading>
                    <flux:text class="mt-2">Create a new permission by providing a name below</flux:text>
                @endif
            </div>
            <form wire:submit="writePermissionAction">
                <flux:input wire:model="name" label="Name" placeholder="Permission name" class="mb-4" />
                <flux:dropdown class="w-full mb-4">
                    <flux:button icon:trailing="chevron-down" align="start" class="w-full mb-4">Select group</flux:button>
                    <flux:menu>
                        <flux:menu.radio.group wire:model="permission_group_id">
                            @foreach( $permissionGroups as $i => $pmg )
                                @if($pmg->id === $this->id)
                                    <flux:menu.radio checked :value="$team->id">{{ $pmg->name }} | {{$pmg->id }}-{{ $this->id}}</flux:menu.radio>
                                @else
                                    <flux:menu.radio :value="$pmg->id">{{ $pmg->name }}</flux:menu.radio>
                                @endif
                            @endforeach
                        </flux:menu.radio.group>
                    </flux:menu>
                </flux:dropdown>
                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">Save permission</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
