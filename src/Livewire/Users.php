<?php

namespace WebFresh\UserManager\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WebFresh\UserManager\Models\Team;
use WebFresh\UserManager\Models\WfumRole as Role;
use WebFresh\UserManager\Models\WfumUser;
use WebFresh\UserManager\Models\WfumUser as User;

#[Title('Users')]
class Users extends Component
{
    use WithPagination;

    private $users;

    public string $sortBy = 'name';

    public string $sortDirection = 'asc';

    public bool $showUserWriteModal = false;

    public bool $showUserDeleteModal = false;

    public bool $showAssignRoleModal = false;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public array $userRoles = [];

    public bool $blocked = false;

    public Collection $roles;

    public Collection $teams;

    public string $id = '';

    public function mount(): void {}

    #[Layout('layouts.app')]
    public function render(): View
    {
        $this->users = DB::table('users')->orderBy('name', $this->sortDirection)->paginate(15);
        $this->roles = Role::all();
        $this->teams = Team::all();

        return view('wfum::livewire.users', [
            'users' => $this->users,
        ]);
    }

    public function assignRoleAction()
    {
        // Loop, add and remove role link to user
        $user = User::find($this->id);
        $roles = Role::whereIn('name', $this->userRoles)->get();
        DB::transaction(function () use ($user, $roles) {
            DB::table('model_has_roles')->where('model_id', $user->id)->where('model_type', 'App\Models\User')->delete();
            foreach ($roles as $role) {
                DB::table('model_has_roles')->insert([
                    'model_id' => $user->id,
                    'role_id' => $role->id,
                    'model_type' => 'App\Models\User',
                    'team_id' => $role->team_id,
                ]);
            }
        });

        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $this->showAssignRoleModal = false;
    }

    public function writeUserAction(): void
    {
        $confidentialData = [];
        if ($this->password != '') {
            $confidentialData['password'] = bcrypt($this->password);
        }

        $this->password = '';

        User::updateOrCreate([
            'id' => $this->id,
        ], array_merge([
            'name' => $this->name,
            'email' => $this->email,
        ], $confidentialData));

        $this->clearFieldData();
        $this->showUserWriteModal = false;
    }

    public function showWriteUserModalWindow($user_id): void
    {
        $user = User::find($user_id);

        $this->name = $user->name;
        $this->email = $user->email;
        $this->id = $user->id;
        $this->showUserWriteModal = true;
    }

    public function showDeleteUserModalWindow($user_id): void
    {
        $this->id = $user_id;
        $this->showUserDeleteModal = true;
    }

    public function clearFieldData(): void
    {
        $this->id = '';
        $this->name = '';
        $this->email = '';
        $this->userRoles = [];
    }

    public function deleteUserAction(): void
    {
        User::find($this->id)->delete();
        $this->clearFieldData();
        $this->showUserDeleteModal = false;
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

    public function changeUserStatus($user_id)
    {
        $user = User::find($user_id);
        $user->blocked = ! ($user->blocked === true);
        $user->save();
    }

    public function showAssignRoleModalWindow($user_id): void
    {
        $user = User::find($user_id);
        $this->id = $user->id;
        $this->userRoles = $user->userRoles();
        $this->showAssignRoleModal = true;
    }

    public function shadowlogin($user_id){
        $user = WfumUser::find($user_id);
        if( $user->shadow_opt_out === false ) {
            $this->dispatch('shadowlogin', $user_id);
        }
    }
}
