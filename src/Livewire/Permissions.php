<?php

namespace WebFresh\UserManager\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WebFresh\UserManager\Models\PermissionGroup;
use WebFresh\UserManager\Models\WfumPermission as Permission;
use WebFresh\UserManager\Models\WfumRole as Role;
use WebFresh\UserManager\Models\Team;
use Flux\Flux;

#[Title('Permissions')]
class Permissions extends Component
{
    use WithPagination;

    private $permissions;

    public string $sortBy = 'name';

    public Collection $roles;

    public Collection $teams;

    public Collection $permissionGroups;

    #[Validate('string|in:desc,asc', message: 'Invalid sort field')]
    public string $sortDirection = 'asc';

    #[Validate('int|nullable', message: 'Invalid permission group ID')]
    public $permission_group_id = null;

    public bool $showPermissionWriteModal = false;

    public bool $showPermissionDeleteModal = false;

    #[Validate('string|max:255', message: 'Permission name invalid')]
    public string $name = '';

    public string $id = '';

    public function rules()
    {
        return [
            'name' => 'string|max:255',
            'permission_group_id' => 'int|nullable'
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount(): void {
        $this->roles = Role::orderBy('name', 'ASC')->get();
        $this->teams = Team::orderBy('name', 'ASC')->get();
        $this->permissionGroups = PermissionGroup::orderBy('name', 'ASC')->get();
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        $this->permissions = DB::table('permissions')->orderBy('name', $this->sortDirection)->paginate(15);

        return view('wfum::livewire.permissions', [
            'permissions' => $this->permissions,
            'permissionGroups' => $this->permissionGroups
        ]);
    }

    public function writePermissionAction(): void
    {
        $permission = Permission::updateOrCreate([
            'id' => $this->id,
        ], [
            'name' => $this->name,
            'permission_group_id' => $this->permission_group_id,
        ]);

        $this->clearFieldData();
        $this->showPermissionWriteModal = false;

        Flux::toast(text: __('Permission :permission updated', ['permission' => $this->name]));
    }

    public function showWritePermissionModal($permission_id): void
    {
        $permission = Permission::find($permission_id);
        $this->name = $permission->name;
        $this->permission_group_id = $permission->permission_group_id;
        $this->id = $permission->id;
        $this->selectedRoles = DB::table('role_has_permissions')->where('permission_id', $permission->id)->pluck('role_id')->toArray();
        $this->showPermissionWriteModal = true;
    }

    public function showDeletePermissionModal($permission_id): void
    {
        $this->id = $permission_id;
        $this->showPermissionDeleteModal = true;
    }

    public function clearFieldData(): void
    {
        $this->id = '';
        $this->name = '';
        $this->permission_group_id = null;
        $this->selectedRoles = [];
    }

    public function deletePermissionAction(): void
    {
        $permission = Permission::find($this->id);
        $permissionName = $permission->name;
        $permission->delete();
        $this->clearFieldData();
        $this->showPermissionDeleteModal = false;

        Flux::toast(text: __('Permission :permission removed', ['permission' => $permissionName]));
    }

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }
}
