<?php

namespace WebFresh\UserManager\Livewire;

use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\WithPagination;
use WebFresh\UserManager\Models\PermissionGroup;

#[Title('Permission Groups')]
class PermissionGroups extends Component
{
    use WithPagination;

    private $groups;

    public string $sortBy = 'name';

    #[Validate('string|in:desc,asc', message: 'Invalid sort field')]
    public string $sortDirection = 'asc';

    public bool $showGroupWriteModal = false;

    public bool $showGroupDeleteModal = false;

    #[Validate('string|max:255', message: 'Permission group name invalid')]
    public string $name = '';

    public string $id = '';

    public function rules()
    {
        return [
            'name' => 'string|max:255',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount(): void {}

    public function render(): View
    {
        $this->groups = DB::table('permission_groups')->orderBy('name', $this->sortDirection)->paginate(15);

        return view('wfum::livewire.permission-groups', [
            'groups' => $this->groups,
        ]);
    }

    public function writeGroupAction(): void
    {
        PermissionGroup::updateOrCreate([
            'id' => $this->id,
        ], [
            'name' => $this->name,
        ]);

        $this->clearFieldData();
        $this->showGroupWriteModal = false;

        Flux::toast(text: __('Group :group updated', ['group' => $this->name]));
    }

    public function showWriteGroupModal($group_id): void
    {
        $group = PermissionGroup::find($group_id);

        $this->name = $group->name;
        $this->id = $group->id;
        $this->showGroupWriteModal = true;
    }

    public function showDeleteGroupModal($group_id): void
    {
        $this->id = $group_id;
        $this->showGroupDeleteModal = true;
    }

    public function clearFieldData(): void
    {
        $this->id = '';
        $this->name = '';
    }

    public function deleteGroupAction(): void
    {
        $group = PermissionGroup::find($this->id);
        $groupName = $group->name;
        $group->delete();
        $this->clearFieldData();
        $this->showGroupDeleteModal = false;

        Flux::toast(text: __('Group :group removed', ['group' => $groupName]));
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
