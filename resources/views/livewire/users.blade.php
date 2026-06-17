<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 grid-cols-1">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="absolute right-0 top-0 p-4 z-20">
                    <flux:modal.trigger name="user-write">
                        <flux:button>{{ __('Create user') }}</flux:button>
                    </flux:modal.trigger>
                </div>
                <div class="z-10 mt-4 mb-4">
                    <flux:heading size="xl" level="1" class="ms-4">{{ __('User management') }}</flux:heading>
                </div>
                <div class="ms-4 me-4">
                    <flux:table :paginate="$this->users" class="z-10 table-fixed">
                        <flux:table.columns>
                            <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">{{ __('Name') }}</flux:table.column>
                            <flux:table.column sortable :sorted="$sortBy === 'email'" :direction="$sortDirection" wire:click="sort('email')">{{ __('E-mail address') }}</flux:table.column>
                            @if( config('permission.teams') && (int)session('team_id') === 0 )
                                <flux:table.column>{{ __('Teams') }}</flux:table.column>
                            @endif
                            <flux:table.column>{{ __('Status') }}</flux:table.column>
                            <flux:table.column class="max-w-32">{{ __('Actions') }}</flux:table.column>
                        </flux:table.columns>
                        <flux:table.rows>
                            @foreach( $users as $user )
                            <flux:table.row wire:key="user-{{ $user->id }}">
                                <flux:table.cell>
                                    <div class="flex items-center gap-2">
                                        <span>{{ $user->name }}</span>
                                    </div>
                                </flux:table.cell>
                                @if( config('permission.teams') && (int)session('team_id') === 0 )
                                    <flux:table.cell>
                                        <div class="flex items-center gap-2">
                                            <span>{{ $user->email }}</span>
                                        </div>
                                    </flux:table.cell>
                                @else
                                    <flux:table.cell class="w-full">
                                        <div class="flex items-center gap-2">
                                            <span>{{ $user->email }}</span>
                                        </div>
                                    </flux:table.cell>
                                @endif
                                @if( config('permission.teams') && (int)session('team_id') === 0 )
                                    <flux:table.cell class="w-full">
                                        <div class="flex items-center gap-2">
                                            <span>
                                                @foreach( \WebFresh\UserManager\Models\WfumUser::find($user->id)->userTeams() as $userTeam )
                                                    {{ $userTeam['name'] }},
                                                @endforeach
                                            </span>
                                        </div>
                                    </flux:table.cell>
                                @endif
                                <flux:table.cell>
                                    @if( $user->id === auth()->id() )
                                        <flux:tooltip content="{{ __('You cannot change your own status') }}">
                                            <flux:badge color="gray">{{ __('Active') }}</flux:badge>
                                        </flux:tooltip>
                                    @else
                                        <div class="flex items-center gap-2">
                                            @if( $user->blocked )
                                                <flux:tooltip content="{{ __('Click to unblock user') }}">
                                                    <flux:badge color="red" style="cursor:pointer;" wire:click="changeUserStatus({{ $user->id }})" variant="solid">{{ __('Blocked') }}</flux:badge>
                                                </flux:tooltip>
                                            @else
                                                <flux:tooltip content="{{ __('Click to block user') }}">
                                                    <flux:badge color="lime"  style="cursor:pointer;" wire:click="changeUserStatus({{ $user->id }})">{{ __('Active') }}</flux:badge>
                                                </flux:tooltip>
                                            @endif
                                        </div>
                                    @endif
                                </flux:table.cell>
                                <flux:table.cell>
                                    @if( config('wfusermanager.allow_shadow_login') === true && (int)$user->shadow_opt_out === 0 )
                                        @if( $user->id !== auth()->id() )
                                            <flux:tooltip content="{{ __('Login as this user') }}">
                                                <flux:icon.square-2-stack class="cursor-pointer inline-block me-4" wire:click="shadowlogin({{ $user->id }})" />
                                            </flux:tooltip>
                                        @else
                                            <flux:tooltip content="{{ __('You cannot shadow login as yourself') }}">
                                                <flux:icon.square-2-stack color="lightgray" class="inline-block me-4" />
                                            </flux:tooltip>
                                        @endif
                                    @elseif( config('wfusermanager.allow_shadow_login') === true && (int)$user->shadow_opt_out === 1 )
                                        <flux:tooltip content="{{ __('User disabled shadow login in their settings') }}">
                                            <flux:icon.exclamation-triangle color="red" class="cursor-pointer inline-block me-4" />
                                        </flux:tooltip>
                                    @endif
                                    <flux:tooltip content="{{ __('Assign roles to user') }}">
                                        <flux:icon.identification class="cursor-pointer text-orange-500 inline-block me-4" wire:click="showAssignRoleModalWindow({{ $user->id }})" />
                                    </flux:tooltip>
                                    <flux:tooltip content="{{ __('Assign direct permissions to user') }}">
                                        <flux:icon.puzzle-piece class="cursor-pointer text-orange-500 inline-block me-4" wire:click="showAssignPermissionsModalWindow({{ $user->id }})" />
                                    </flux:tooltip>
                                    <flux:tooltip content="{{ __('Update user') }}">
                                        <flux:icon.pencil-square class="cursor-pointer text-orange-500 inline-block me-4" wire:click="showWriteUserModalWindow({{ $user->id }})" />
                                    </flux:tooltip>
                                    @if( $user->id === auth()->id() )
                                        <flux:tooltip content="{{ __('You cannot delete your own user account') }}">
                                            <flux:icon.x-circle color="lightgray" class="text-gray-500 inline-block" />
                                        </flux:tooltip>
                                    @else
                                        <flux:tooltip content="{{ __('Delete user') }}">
                                            <flux:icon.x-circle class="cursor-pointer text-red-500 inline-block" wire:click="showDeleteUserModalWindow({{ $user->id }})" />
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
                <flux:heading size="lg">{{ __('Delete user') }}</flux:heading>
                <flux:text class="mt-2">{{ __('Are you sure you want to delete this user? This action cannot be undone.') }}</flux:text>
            </div>
            <div class="flex gap-2">
                <flux:button variant="outline" @click="showUserDeleteModal = false">{{ __('Cancel') }}</flux:button>
                <flux:button variant="danger" wire:click="deleteUserAction()">{{ __('Delete user') }}</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="user-write" class="md:w-96" wire:model.self="showUserWriteModal" wire:close="clearFieldData()">
        <div class="space-y-6">
            <div>
                @if( $this->id !== '' )
                    <flux:heading size="lg">{{ __('Update user') }}</flux:heading>
                    <flux:text class="mt-2">{{ __('Update the selected user') }}</flux:text>
                @else
                    <flux:heading size="lg">{{ __('Create user') }}</flux:heading>
                    <flux:text class="mt-2">{{ __('Create a new user by providing a name below') }}</flux:text>
                @endif
            </div>
            <form wire:submit="writeUserAction">
                <flux:input wire:model="name" label="{{ __('Name') }}" placeholder="{{ __('User name') }}" class="mb-4" />
                <flux:input wire:model="email" label="{{ __('E-mail address') }}" placeholder="{{ __('E-mail address') }}" class="mb-4" />
                <flux:input type="password" wire:model="password" label="{{ __('Password') }}" placeholder="{{ __('Password') }}" class="mb-4" />
                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">{{ __('Save user') }}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <flux:modal flyout name="user-assign-role" class="md:w-96" wire:model.self="showAssignRoleModal" wire:close="clearFieldData()">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Assign roles') }}</flux:heading>
                <flux:text class="mt-2">{{ __('Assign roles to the selected user') }}</flux:text>
            </div>
            <form wire:submit="assignRoleAction">
                @if( config('permission.teams') && (int)session('team_id') == 0 )
                    @foreach( $teams as $team )
                        @if( $team->roles()->count() > 0 )
                            <flux:checkbox.group wire:key="rolegroup{{ $team->id }}" wire:model.live="availableRoles" label="{{ $team->name }}" class="mb-4">
                                @foreach( $team->roles()->get() as $role )
                                    <flux:checkbox wire:key="role{{ $role->id }}" label="{{ $role->name }}" value="{{ $role->id }}" />
                                @endforeach
                            </flux:checkbox.group>
                        @endif
                    @endforeach
                @elseif( config('permission.teams') && (int)session('team_id') > 0 )
                    @php $subteam = $teams->where('id', (int)session('team_id'))->first(); @endphp
                    @if( $subteam->roles()->count() > 0 )
                        <flux:checkbox.group wire:key="rolegroup{{ $subteam->id }}" wire:model.live="availableRoles" label="{{ $subteam->name }}" class="mb-4">
                            @foreach( $subteam->roles()->get() as $role )
                                <flux:checkbox wire:key="role{{ $role->id }}" label="{{ $role->name }}" value="{{ $role->id }}" />
                            @endforeach
                        </flux:checkbox.group>
                    @else
                        <flux:text class="mb-4">{{ __('Environment has no roles to assign.') }}</flux:text>
                    @endif
                @else
                @endif
                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">{{ __('Save roles') }}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <flux:modal flyout name="user-assign-permissions" class="md:w-96" wire:model.self="showAssignPermissionsModal" wire:poll.visible wire:close="clearFieldData()">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Assign direct permissions') }}</flux:heading>
                <flux:text class="mt-2">{{ __('Assign direct permissions directly to the selected user') }}</flux:text>
            </div>
            <form wire:submit="assignPermissionsAction">
                @if( config('permission.teams') && count($availableTeams) > 1 )
                    <flux:select wire:model.live="selectedTeamId" size="sm" placeholder="{{ __('Select team') }}" class="mb-4">
                        @foreach( $availableTeams as $team )
                            <flux:select.option value="{{ $team['id'] }}" wire:key="{{ $team['id'] }}">{{ $team['name'] }}</flux:select.option>
                        @endforeach
                    </flux:select>
                @endif
                @if( (config('permission.teams') && $selectedTeamId !== null) || !config('permission.teams') )
                    @if( count($availableRoles) > 1 )
                        <flux:select wire:model.live="selectedRoleId" size="sm" placeholder="{{ __('Select role') }}" class="mb-4">
                            @foreach( $availableRoles as $role )
                                <flux:select.option value="{{ $role->name }}" wire:key="{{ $role->name }}">
                                    {{ $role->name }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                    @else
                        <flux:text class="mb-4">
                            {{ __('The selected user has a single role.') }}<br/>
                            <strong>
                                {{ $roles->find($selectedRoleId)->name }}
                                @if( config('permission.teams') )
                                    ({{ $roles->find($selectedRoleId)->team->name }})
                                @endif
                            </strong><br/><br/>
                            {{ __('Please select a permission group to edit.') }}
                        </flux:text>
                    @endif
                    <flux:select wire:model.live="selectedPermissionGroupId" size="sm" placeholder="{{ __('Select permission group') }}" class="mb-4">
                        @foreach( $permissionGroups as $pmg )
                            <flux:select.option value="{{ $pmg->id }}" wire:key="pmg_{{ $pmg->id }}">{{ $pmg->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                @endif
                @if( $selectedPermissionGroup !== null )
                    <flux:table container:class="max-h-80">
                        <flux:table.rows>
                            <flux:table.row>
                                <flux:table.cell style="background-color:#EEEEEE;border-top: 2px solid #888888;border-bottom: 2px solid #888888;"><strong>{{ $selectedPermissionGroup->name }}</strong></flux:table.cell>
                                <flux:table.cell align="center" class="text-center" style="border-top: 2px solid #888888;border-bottom: 2px solid #888888;width:40px;background-color:#EEEEEE;">&nbsp;</flux:table.cell>
                            </flux:table.row>
                            @foreach( $selectedPermissionGroup->permissions as $pml )
                                <flux:table.row wire:key="pml-{{ $pml->id }}">
                                    <flux:table.cell>{{ $pml->name }}</flux:table.cell>
                                    <flux:table.cell align="center" class="text-center" style="width:40px;text-align:center;">
                                        @if( in_array( $pml->name, $userPermissions ) === true )
                                            <flux:icon.check-circle wire:click="removeDirectPermission({{ $pml->id }})" variant="micro" class="text-green-500 dark:text-green-300 curser-pointer" style="cursor:pointer;"/>
                                        @else
                                            <flux:icon.x-circle wire:click="addDirectPermission({{ $pml->id }})" variant="micro" class="text-red-500 dark:text-red-300 cursor-pointer" />
                                        @endif
                                    </flux:table.cell>
                                </flux:table.row>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
                @endif
            </form>
        </div>
    </flux:modal>
</div>
