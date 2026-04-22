<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 grid-cols-1">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="absolute right-0 top-0 p-4 z-20">
                    <flux:modal.trigger name="user-write">
                        <flux:button>Create user</flux:button>
                    </flux:modal.trigger>
                </div>
                <div class="z-10 mt-4 mb-4">
                    <flux:heading size="xl" level="1" class="ms-4">User management</flux:heading>
                </div>
                <div class="ms-4 me-4">
                    <flux:table :paginate="$this->users" class="z-10 table-fixed">
                        <flux:table.columns>
                            <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">Name</flux:table.column>
                            <flux:table.column sortable :sorted="$sortBy === 'email'" :direction="$sortDirection" wire:click="sort('email')">E-mail address</flux:table.column>
                            <flux:table.column class="max-w-32">Actions</flux:table.column>
                        </flux:table.columns>
                        <flux:table.rows>
                            @foreach( $users as $user )
                            <flux:table.row wire:key="user-{{ $user->id }}">
                                <flux:table.cell>
                                    <div class="flex items-center gap-2">
                                        <span>{{ $user->name }}</span>
                                    </div>
                                </flux:table.cell>
                                <flux:table.cell class="w-full">
                                    <div class="flex items-center gap-2">
                                        <span>{{ $user->email }}</span>
                                    </div>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:tooltip content="Update user">
                                        <flux:icon.pencil-square class="cursor-pointer text-orange-500 inline-block" wire:click.self="showWriteUserModal({{ $user->id }})" />
                                    </flux:tooltip>
                                    <flux:tooltip content="Delete user">
                                        <flux:icon.x-circle class="cursor-pointer text-red-500 inline-block" wire:click.self="showDeleteUserModal({{ $user->id }})" />
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

    <flux:modal name="user-delete" class="md:w-96" wire:model.self="showUserDeleteModal">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete user</flux:heading>
                <flux:text class="mt-2">Are you sure you want to delete this user? This action cannot be undone.</flux:text>
            </div>
            <div class="flex gap-2">
                <flux:button variant="outline" @click="showUserDeleteModal = false">Cancel</flux:button>
                <flux:button variant="danger" wire:click="deleteUserAction()">Delete user</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="user-write" class="md:w-96" wire:model.self="showUserWriteModal" @close="clearFieldData">
        <div class="space-y-6">
            <div>
                @if( $this->id !== '' )
                    <flux:heading size="lg">Update user</flux:heading>
                    <flux:text class="mt-2">Update the selected user</flux:text>
                @else
                    <flux:heading size="lg">Create user</flux:heading>
                    <flux:text class="mt-2">Create a new user by providing a name below</flux:text>
                @endif
            </div>
            <form wire:submit="writeUserAction">
                <flux:input wire:model="name" label="Name" placeholder="User name" class="mb-4" />
                <flux:input wire:model="email" label="E-mail address" placeholder="E-mail address" class="mb-4" />
                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">Save user</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>


</div>
