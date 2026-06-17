<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 grid-cols-1">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="z-10 mt-4 mb-4">
                    <flux:heading size="xl" level="1" class="ms-4">{{ __('Permissions matrix') }}</flux:heading>
                </div>
                <div class="ms-4 me-4 overflow-x-hidden" style="overflow-y: scroll;display: block;max-height: calc(100% - 64px);">
                    <flux:table container:class="max-h-80">
                        <flux:table.columns sticky>
                            <flux:table.column class="bg-zinc-800! hover:bg-zinc-700!" style="width: auto;"></flux:table.column>
                            @php $permissionColumns = []; @endphp
                            @if( (int)session('team_id') > 0 )
                                @php $subteam = $teams->where('id', (int)session('team_id'))->first(); @endphp
                                @php $permissionColumns[] = 'T'; @endphp
                                @foreach( $roles as $role )
                                    @if($role->team_id === $subteam->id )
                                        <flux:table.column style="width:40px;align-content: flex-end;"><div style="writing-mode:sideways-lr;">{{ $role->name }}</div></flux:table.column>
                                        @php $permissionColumns[] = 'R'; @endphp
                                    @endif
                                @endforeach
                            @else
                                @foreach( $teams as $team )
                                    <flux:table.column style="width:40px;align-content: flex-end;border-left: 2px solid #888888;border-right: 2px solid #888888;"><div style="writing-mode:sideways-lr;"><strong>{{ $team->name }}</strong></div></flux:table.column>
                                    @php $permissionColumns[] = 'T'; @endphp
                                    @foreach( $roles as $role )
                                        @if($role->team_id === $team->id )
                                            <flux:table.column style="width:40px;align-content: flex-end;"><div style="writing-mode:sideways-lr;">{{ $role->name }}</div></flux:table.column>
                                            @php $permissionColumns[] = 'R'; @endphp
                                        @endif
                                    @endforeach
                                @endforeach
                            @endif
                            <flux:table.column align="center" class="text-center" style="width:40px;">&nbsp;</flux:table.column>
                        </flux:table.columns>
                        <flux:table.rows>
                            @foreach( $permissionGroups as $pmg )
                                <flux:table.row>
                                    <flux:table.cell style="background-color:#EEEEEE;border-top: 2px solid #888888;border-bottom: 2px solid #888888;"><strong>{{ $pmg->name }}</strong></flux:table.cell>
                                    @for($i=0; $i<count($permissionColumns); $i++)
                                        @if( $permissionColumns[$i] === 'T' )
                                            <flux:table.cell align="center" class="text-center" style="border-top: 2px solid #888888;border-bottom: 2px solid #888888;width:40px;border-left: 2px solid #888888;border-right: 2px solid #888888;background-color:#EEEEEE;">&nbsp;</flux:table.cell>
                                        @else
                                            <flux:table.cell align="center" class="text-center" style="border-top: 2px solid #888888;border-bottom: 2px solid #888888;width:40px;background-color:#EEEEEE;">&nbsp;</flux:table.cell>
                                        @endif
                                    @endfor
                                </flux:table.row>
                                @foreach( $pmg->permissions as $pml )
                                    <flux:table.row wire:key="pml-{{ $pml->id }}">
                                        <flux:table.cell>{{ $pml->name }}</flux:table.cell>
                                        @if( (int)session('team_id') > 0 )
                                            @foreach( $roles as $role )
                                                @if($role->team_id === (int)session('team_id') )
                                                    <flux:table.cell align="center" class="text-center" style="width:40px;text-align:center;">
                                                        @if( $pmg->id === $pml->permission_group_id && $role->hasPermissionTo($pml->name) )
                                                            <flux:icon.check-circle wire:click="removePermission({{ $pml->id }}, {{ $role->id }})" variant="micro" class="text-green-500 dark:text-green-300 curser-pointer" style="cursor:pointer;"/>
                                                        @else
                                                            <flux:icon.x-circle wire:click="addPermission({{ $pml->id }}, {{ $role->id }})" variant="micro" class="text-red-500 dark:text-red-300 cursor-pointer" />
                                                        @endif
                                                    </flux:table.cell>
                                                @endif
                                            @endforeach
                                        @else
                                            @foreach( $teams as $team )
                                                <flux:table.cell align="center" variant="strong" class="text-center" style="background-color:#EEEEEE;width:40px;border-left: 2px solid #888888;border-right: 2px solid #888888;">&nbsp;</flux:table.cell>
                                                @foreach( $roles as $role )
                                                    @if($role->team_id === $team->id )
                                                        <flux:table.cell align="center" class="text-center" style="width:40px;text-align:center;">
                                                            @if( $pmg->id === $pml->permission_group_id && $role->hasPermissionTo($pml->name) )
                                                                <flux:icon.check-circle wire:click="removePermission({{ $pml->id }}, {{ $role->id }})" variant="micro" class="text-green-500 dark:text-green-300 curser-pointer" style="cursor:pointer;"/>
                                                            @else
                                                                <flux:icon.x-circle wire:click="addPermission({{ $pml->id }}, {{ $role->id }})" variant="micro" class="text-red-500 dark:text-red-300 cursor-pointer" />
                                                            @endif
                                                        </flux:table.cell>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endif
                                        <flux:table.cell align="center" class="text-center" style="width:40px;">&nbsp;</flux:table.cell>
                                    </flux:table.row>
                                @endforeach
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
                </div>
            </div>
        </div>
    </div>
</div>
