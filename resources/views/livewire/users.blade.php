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
                            <flux:table.column>Teams</flux:table.column>
                            <flux:table.column>Status</flux:table.column>
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
                                <flux:table.cell>
                                    <div class="flex items-center gap-2">
                                        <span>{{ $user->email }}</span>
                                    </div>
                                </flux:table.cell>
                                <flux:table.cell class="w-full">
                                    <div class="flex items-center gap-2">
                                        <span>{{ implode(', ', \WebFresh\UserManager\Models\WfumUser::find($user->id)->teams()) }}</span>
                                    </div>
                                </flux:table.cell>
                                <flux:table.cell>
                                    @if( $user->id === auth()->id() )
                                        <flux:tooltip content="You cannot change your own status">
                                            <flux:badge variant="info">Active</flux:badge>
                                        </flux:tooltip>
                                    @else
                                        <div class="flex items-center gap-2">
                                            @if( $user->blocked )
                                                <flux:tooltip content="Click to unblock user">
                                                    <flux:badge wire:click="changeUserStatus({{ $user->id }})" variant="danger">Blocked</flux:badge>
                                                </flux:tooltip>
                                            @else
                                                <flux:tooltip content="Click to block user">
                                                    <flux:badge wire:click="changeUserStatus({{ $user->id }})" variant="success">Active</flux:badge>
                                                </flux:tooltip>
                                            @endif
                                        </div>
                                    @endif
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:tooltip content="Assign roles to user">
                                        <flux:icon.identification class="cursor-pointer text-orange-500 inline-block me-4" wire:click.self="showAssignRoleModalWindow({{ $user->id }})" />
                                    </flux:tooltip>
                                    <flux:tooltip content="Update user">
                                        <flux:icon.pencil-square class="cursor-pointer text-orange-500 inline-block me-4" wire:click.self="showWriteUserModalWindow({{ $user->id }})" />
                                    </flux:tooltip>
                                    @if( $user->id === auth()->id() )
                                        <flux:tooltip content="You cannot change your own status">
                                            <flux:icon.x-circle class="cursor-pointer text-gray-500 inline-block" />
                                        </flux:tooltip>
                                    @else
                                        <flux:tooltip content="Delete user">
                                            <flux:icon.x-circle class="cursor-pointer text-red-500 inline-block" wire:click.self="showDeleteUserModalWindow({{ $user->id }})" />
                                        </flux:tooltip>
                                    @endif
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

    <flux:modal name="user-write" class="md:w-96" wire:model.self="showUserWriteModal" wire:close="clearFieldData()">
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
                <flux:input type="password" wire:model="password" label="Password" placeholder="Password" class="mb-4" />
                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">Save user</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <flux:modal flyout name="user-assign-role" class="md:w-96" wire:model.self="showAssignRoleModal" wire:close="clearFieldData()">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Assign roles</flux:heading>
                <flux:text class="mt-2">Assign roles to the selected user</flux:text>
            </div>
            <form wire:submit="assignRoleAction">
                @foreach( $teams as $team )
                    @if( $team->roles()->count() > 0 )
                        <flux:checkbox.group wire:model="roles" label="{{ $team->name }}" class="mb-4">
                            @foreach( $team->roles as $role )
                                @if( in_array( $role->id, $userRoles ) )
                                    <flux:checkbox label="{{ $role->name }}" value="{{ $role->id }}" checked />
                                @else
                                    <flux:checkbox label="{{ $role->name }}" value="{{ $role->id }}" />
                                @endif
                            @endforeach
                        </flux:checkbox.group>
                    @endif
                @endforeach
                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">Save roles</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
