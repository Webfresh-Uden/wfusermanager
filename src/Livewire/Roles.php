<?php

namespace WebFresh\UserManager\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Title('Roles')]
class Roles extends Component
{
    use WithPagination;

    private $roles;

    public string $sortBy = 'name';

    public string $sortDirection = 'asc';

    public bool $showRoleWriteModal = false;

    public bool $showRoleDeleteModal = false;

    public string $name = '';

    public string $id = '';

    public function mount(): void {}

    #[Layout('layouts.app')]
    public function render(): View
    {
        $this->roles = DB::table('roles')->orderBy('name', $this->sortDirection)->paginate(15);

        return view('wfum::livewire.roles', [
            'roles' => $this->roles,
        ]);
    }

    public function writeRoleAction(): void
    {
        Role::updateOrCreate([
            'id' => $this->id,
        ], [
            'name' => $this->name,
        ]);

        $this->clearFieldData();
        $this->showRoleWriteModal = false;
    }

    public function showWriteRoleModal($role_id): void
    {
        $role = Role::find($role_id);

        $this->name = $role->name;
        $this->id = $role->id;
        $this->showRoleWriteModal = true;
    }

    public function showDeleteRoleModal($role_id): void
    {
        $this->id = $role_id;
        $this->showRoleDeleteModal = true;
    }

    public function clearFieldData(): void
    {
        $this->id = '';
        $this->name = '';
    }

    public function deleteRoleAction(): void
    {
        Role::find($this->id)->delete();
        $this->clearFieldData();
        $this->showRoleDeleteModal = false;
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
