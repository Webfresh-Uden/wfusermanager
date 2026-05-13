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
    public $teams, $roles, $permissions, $permissionGroups;

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
        DB::table('role_has_permissions')->insertOrIgnore([
            'permission_id' => $permission->id,
            'role_id' => $role_id
        ]);

        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        Flux::toast(text: __('Permission :permission added', ['permission' => $permission->name]));
    }

    public function removePermission( $permission_id, $role_id ): void
    {
        $permission = Permission::find($permission_id);

        DB::table('role_has_permissions')->where('permission_id', $permission->id)->where('role_id', $role_id)->delete();

        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        Flux::toast(text: __('Permission :permission removed', ['permission' => $permission->name]));
    }
}
