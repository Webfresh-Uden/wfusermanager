<?php

namespace WebFresh\UserManager\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WebFresh\UserManager\Models\Team;
use WebFresh\UserManager\Models\WfumRole as Role;
use Flux\Flux;

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

    public string $team_id = '';

    public string $guard_name = 'web';

    public Collection $teams;

    public function mount(): void {}

    #[Layout('layouts.app')]
    public function render(): View
    {
        $this->roles = Role::orderBy('name', $this->sortDirection)->paginate(15);
        if ((int)session('team_id') > 0) {
            $filteredCollection = $this->roles->filter(function ($role) {
                return $role->team_id === (int)session('team_id');
            });
            $this->roles->setCollection($filteredCollection);
        }
        $this->teams = Team::all();

        return view('wfum::livewire.roles', [
            'roles' => $this->roles,
        ]);
    }

    public function writeRoleAction(): void
    {
        if( (int)$this->team_id > 0 ) {
            $teamMerge = ['team_id' => (int)$this->team_id];
        }
        if( (int)session('team_id') > 0 ) {
            $teamMerge = ['team_id' => (int)session('team_id')];
        }
        Role::updateOrCreate([
            'id' => $this->id,
        ], array_merge($teamMerge, [
            'name' => $this->name,
            'guard_name' => $this->guard_name,
        ]));

        $this->clearFieldData();
        $this->showRoleWriteModal = false;

        Flux::toast(text: __('Role :role updated', ['role' => $this->name]));
    }

    public function showWriteRoleModal($role_id): void
    {
        $role = Role::find($role_id);

        $this->name = $role->name;
        $this->guard_name = $role->guard_name;
        $this->id = $role->id;
        $this->team_id = $role->team_id;
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
        $this->team_id = '';
    }

    public function deleteRoleAction(): void
    {
        $role = Role::find($this->id);
        $roleName = $role->name;
        $role->delete();
        $this->clearFieldData();
        $this->showRoleDeleteModal = false;

        Flux::toast(text: __('Permission :role removed', ['role' => $roleName]));
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
