<?php

namespace WebFresh\UserManager\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use WebFresh\UserManager\Models\PermissionGroup;
use WebFresh\UserManager\Models\Team;
use WebFresh\UserManager\Models\WfumUser as User;
use WebFresh\UserManager\Models\WfumRole as Role;
use Flux\Flux;
use Spatie\Permission\Models\Permission;

#[Title('Permissions Matrix')]
class PermissionsMatrix extends Component
{
    public $teams, $roles, $permissions, $permissionGroups, $pmgid;

    public function mount(): void
    {
        $this->teams = Team::all();
        $this->roles = Role::all();
        $this->permissionGroups = PermissionGroup::all();
    }

    public function render(): View
    {
        return view('wfum::livewire.permissionsmatrix', [
            'teams' => $this->teams,
            'roles' => $this->roles,
            'permissionGroups' => $this->permissionGroups
        ]);
    }

    public function addPermission( $permission_id, $role_id ): void
    {
        $permission = Permission::find($permission_id);
        $role = Role::find($role_id);
        $role->givePermissionTo($permission);

        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        Flux::toast(text: __('Permission :permission added', ['permission' => $permission->name]));
    }

    public function removePermission( $permission_id, $role_id ): void
    {
        $permission = Permission::find($permission_id);
        $role = Role::find($role_id);
        $role->revokePermissionTo($permission);

        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        Flux::toast(text: __('Permission :permission removed', ['permission' => $permission->name]));
    }

    public function showPermissionGroup($pmgId=0){
        $this->pmgid = 0;
        if( $pmgId !== 0 ) {
            $this->pmgid = $pmgId;
        }
    }
}
