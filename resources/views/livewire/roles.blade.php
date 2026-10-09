<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 grid-cols-1">
            @if(empty($teams))
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 flex justify-center items-center">
                <div class="text-center flex flex-col gap-4">
                    <h1 class="text-2xl font-bold">{{ __('Geen teams beschikbaar') }}</h1>
                    <p class="text-gray-500">{{ __('Om een rol aan te kunnen maken moet eerst een team beschikbaar
                        zijn.')
                        }}</p>
                    @if( config('permission.teams') && is_int(session('team_id')) && (int)session('team_id') === 0 )
                    <div class="flex justify-center">
                        <flux:button variant="primary" :href="route('teams.index')" icon="user-group">{{
                            __('Maak een team aan') }}</flux:button>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class=" relative aspect-video overflow-hidden rounded-xl border border-neutral-200
                            dark:border-neutral-700">
                <div class="absolute right-0 top-0 p-4 z-20">
                    <flux:modal.trigger name="role-write">
                        <flux:button>{{ __('Create role') }}</flux:button>
                    </flux:modal.trigger>
                </div>
                <div class="z-10 mt-4 mb-4">
                    <flux:heading size="xl" level="1" class="ms-4">{{ __('Role management') }}
                    </flux:heading>
                </div>
                <div class="ms-4 me-4">
                    <flux:table :paginate="$this->roles" class="z-10 table-fixed">
                        <flux:table.columns>
                            <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection"
                                wire:click="sort('name')">{{ __('Name') }}
                            </flux:table.column>
                            @if( config('permission.teams') && (int)session('team_id') == 0 )
                            <flux:table.column sortable :sorted="$sortBy === 'team'" :direction="$sortDirection"
                                wire:click="sort('team')">{{ __('Team') }}
                            </flux:table.column>
                            @endif
                            <flux:table.column class="max-w-32">{{ __('Actions') }}</flux:table.column>
                        </flux:table.columns>
                        <flux:table.rows>
                            @foreach( $roles as $role )
                            <flux:table.row wire:key="role-{{ $role->id }}">
                                @if( config('permission.teams') && (int)session('team_id') > 0 )
                                <flux:table.cell class="w-full">
                                    <div class="flex items-center gap-2">
                                        <span>{{ $role->name }}</span>
                                    </div>
                                </flux:table.cell>
                                @else
                                <flux:table.cell>
                                    <div class="flex items-center gap-2">
                                        <span>{{ $role->name }}</span>
                                    </div>
                                </flux:table.cell>
                                @endif
                                @if( config('permission.teams') && (int)session('team_id') == 0 )
                                <flux:table.cell class="w-full">
                                    @if( $role->id )
                                    <div class="flex items-center gap-2">
                                        <span>{{
                                            \WebFresh\UserManager\Models\WfumRole::find($role->id)->team()->first()->name
                                            }}</span>
                                    </div>
                                    @endif
                                </flux:table.cell>
                                @endif
                                <flux:table.cell>
                                    <flux:tooltip content="{{ __('Update role') }}">
                                        <flux:icon.pencil-square class="cursor-pointer text-gray-500 inline-block"
                                            wire:click="showWriteRoleModal({{ $role->id }})" />
                                    </flux:tooltip>
                                    <flux:tooltip content="{{ __('Delete role') }}">
                                        <flux:icon.x-circle class="cursor-pointer text-red-500 inline-block"
                                            wire:click="showDeleteRoleModal({{ $role->id }})" />
                                    </flux:tooltip>
                                </flux:table.cell>
                            </flux:table.row>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
                </div>
            </div>
            @endif
        </div>
    </div>

    <flux:modal name="role-delete" class="md:w-96" wire:model.self="showRoleDeleteModal">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Delete role') }}</flux:heading>
                <flux:text class="mt-2">{{ __('Are you sure you want to delete this role? This action cannot be
                    undone.') }}</flux:text>
            </div>
            <div class="flex gap-2">
                <flux:button variant="outline" @click="showRoleDeleteModal = false">{{ __('Cancel') }}
                </flux:button>
                <flux:button variant="danger" wire:click="deleteRoleAction()">{{ __('Delete role') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="role-write" class="md:w-96" wire:model.self="showRoleWriteModal" wire:close="clearFieldData()">
        <div class="space-y-6">
            <div>
                @if( $this->id !== '' )
                <flux:heading size="lg">{{ __('Update role') }}</flux:heading>
                <flux:text class="mt-2">{{ __('Update the selected role') }}</flux:text>
                @else
                <flux:heading size="lg">{{ __('Create role') }}</flux:heading>
                <flux:text class="mt-2">{{ __('Create a new role by providing a name below') }}</flux:text>
                @endif
            </div>
            <form wire:submit="writeRoleAction">
                <flux:input wire:model="name" label="{{ __('Name') }}" placeholder="{{ __('Role name') }}"
                    class="mb-4" />
                @if( config('permission.teams') && (int)session('team_id') === 0 )
                <flux:dropdown class="w-full mb-4">
                    <flux:button icon:trailing="chevron-down" align="start" class="w-full mb-4">{{ __('Select
                        team') }}
                    </flux:button>
                    <flux:menu>
                        <flux:menu.radio.group wire:model="team_id">
                            @foreach( $teams as $i => $team )
                            @if($team->id === $this->id)
                            <flux:menu.radio checked :value="$team->id">{{ $team->name }} | {{$team->id }}-{{
                                $this->id}}</flux:menu.radio>
                            @else
                            <flux:menu.radio :value="$team->id">{{ $team->name }}</flux:menu.radio>
                            @endif
                            @endforeach
                        </flux:menu.radio.group>
                    </flux:menu>
                </flux:dropdown>
                @endif
                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">{{ __('Save role') }}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>


</div>
