<?php

namespace WebFresh\UserManager\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

#[Title('Permissions')]
class Permissions extends Component
{
    use WithPagination;

    private $permissions;

    public string $sortBy = 'name';

    public string $sortDirection = 'asc';

    public bool $showPermissionWriteModal = false;

    public bool $showPermissionDeleteModal = false;

    public string $name = '';

    public string $id = '';

    public function mount(): void {}

    #[Layout('layouts.app')]
    public function render(): View
    {
        $this->permissions = DB::table('permissions')->orderBy('name', $this->sortDirection)->paginate(15);

        return view('wfum::livewire.permissions', [
            'permissions' => $this->permissions,
        ]);
    }

    public function writePermissionAction(): void
    {
        Permission::updateOrCreate([
            'id' => $this->id,
        ], [
            'name' => $this->name,
        ]);

        $this->clearFieldData();
        $this->showPermissionWriteModal = false;
    }

    public function showWritePermissionModal($permission_id): void
    {
        $permission = Permission::find($permission_id);

        $this->name = $permission->name;
        $this->id = $permission->id;
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
    }

    public function deletePermissionAction(): void
    {
        Permission::find($this->id)->delete();
        $this->clearFieldData();
        $this->showPermissionDeleteModal = false;
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
