<?php

namespace WebFresh\UserManager\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use WebFresh\UserManager\Models\PermissionGroup;
use WebFresh\UserManager\Models\Team;
use WebFresh\UserManager\Models\WfumRole as Role;
use WebFresh\UserManager\Models\WfumUser as User;
use Flux\Flux;

#[Title('Users')]
class Users extends Component
{
    use WithPagination;

    private $users;
    public string $sortBy = 'name';
    #[Validate('string|in:desc,asc', message: 'Invalid sort field')]
    public string $sortDirection = 'asc';
    public bool $showUserWriteModal = false;
    public bool $showUserDeleteModal = false;
    public bool $showAssignRoleModal = false;
    public bool $showAssignPermissionsModal = false;
    #[Validate('string|max:255', message: 'User name invalid')]
    public string $name = '';
    #[Validate('string|email', message: 'Invalid email address')]
    public string $email = '';
    #[Validate('string|min:8', message: 'Password must be at least 8 characters')]
    public string $password = '';
    public array $userRoles = [];
    public array $userPermissions = [];
    public bool $blocked = false;
    public Collection $roles;
    public Collection $teams;
    public string $id = '';
    #[Validate('string|nullable', message: 'Invalid permission group ID')]
    public $permissionGroups;

    // Direct permission variables
    public $selectedTeamId;
    public $availableTeams = [];
    public $selectedRoleId;
    public $availableRoles = [];
    public $selectedPermissionGroupId = '';
    public $selectedPermissionGroup = null;

    public function rules()
    {
        return [
            'name' => 'string|max:255',
            'email' => 'string|email',
            'password' => 'string|min:8'
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount(): void {
        $this->permissionGroups = PermissionGroup::all();
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        $this->users = User::orderBy('name', $this->sortDirection)->paginate(15);

        if ((int)session('team_id') > 0) {
            $filteredCollection = $this->users->filter(function ($user) {
                $teams = $user->userTeams();
                foreach ($teams as $team) {
                    if ($team['id'] === (int)session('team_id')) {
                        return $user;
                    }
                }
            });
            $this->users->setCollection($filteredCollection);
        }

        $this->roles = Role::all();
        $this->teams = Team::all();

        if( $this->selectedPermissionGroupId !== null )
        {
            $this->selectedPermissionGroup = PermissionGroup::find($this->selectedPermissionGroupId);
        }

        return view('wfum::livewire.users', [
            'users' => $this->users,
        ]);
    }

    public function assignRoleAction()
    {
        // Loop, add and remove role link to user
        $user = User::find($this->id);
        $roles = Role::whereIn('id', $this->availableRoles)->get();
        DB::transaction(function () use ($user, $roles) {
            DB::table('model_has_roles')->where('model_id', $user->id)->where('model_type', 'App\Models\User')->delete();
            foreach ($roles as $role) {
                if( config('permission.teams') ) {
                    $teamInsert = ['team_id' => $role->team_id];
                }
                DB::table('model_has_roles')->insert(
                    array_merge($teamInsert, [
                        'model_id' => $user->id,
                        'role_id' => $role->id,
                        'model_type' => 'App\Models\User',
                    ])
                );
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

        Flux::toast(text: __('User :user updated', ['user' => $this->name]));
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
        $this->userPermissions = [];
        $this->availableRoles = [];
        $this->selectedTeams = [];
    }

    public function deleteUserAction(): void
    {
        $user = User::find($this->id);
        $userName = $user->name;
        $user->delete();
        $this->clearFieldData();
        $this->showUserDeleteModal = false;

        Flux::toast(text: __('User :user removed', ['user' => $userName]));
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

        Flux::toast(text: __('User :user :status', ['user' => $user->name, 'status' => ($user->blocked === true ? 'blocked' : 'unblocked')]));
    }

    public function showAssignRoleModalWindow($user_id): void
    {
        $user = User::find($user_id);
        $this->id = $user->id;
        $this->userRoles = $user->userRoles();
        $this->availableRoles = array_map(function ($role) { return $role->id; }, $this->userRoles);
        $this->showAssignRoleModal = true;
    }

    public function shadowlogin($user_id){
        $user = User::find($user_id);
        if( $user->shadow_opt_out === false ) {
            $this->dispatch('shadowlogin', $user_id);
        }
    }

    public function updatedSelectedTeamId()
    {
        $user = User::find($this->id);
        $this->availableRoles = $user->userRoles();
        $this->userPermissions = $user->userPermissions($this->selectedTeamId);
        $roleList = [];
        foreach($this->availableRoles as $role) {
            if( intval($role->team_id) === intval($this->selectedTeamId) || $this->selectedTeamId === null ) {
                $roleList[] = $role;
            }
        }
        $this->availableRoles = $roleList;

        if( count($this->availableRoles) === 1 )
        {
            $this->selectedRoleId = $this->availableRoles[0]->id;
        }
    }

    public function showAssignPermissionsModalWindow($user_id): void
    {
        $user = User::find($user_id);
        $this->id = $user->id;
        $this->availableRoles = $user->userRoles();
        $this->userPermissions = $user->userPermissions();

        $this->availableTeams = $user->userTeams();

        if( config('permission.teams') )
        {
            if (count($this->availableTeams) > 1 && is_int(session('team_id')) && (int)session('team_id') !== 0) {
                $this->selectedTeamId = (int)session('team_id');
                $this->updatedSelectedTeamId();
            }
            if (count($this->availableTeams) === 1) {
                $this->selectedTeamId = $this->availableTeams[0]['id'];
                $this->updatedSelectedTeamId();
            }
        }

        $this->showAssignPermissionsModal = true;
    }

    public function addDirectPermission( $permission_id ): void
    {
        $user = User::find($this->id);
        $permission = Permission::find($permission_id);
        if( config('permission.teams') ) {
            $teamInsert = ['team_id' => $this->selectedTeamId];
        }
        DB::table('model_has_permissions')->insertOrIgnore(
            array_merge($teamInsert, [
                'permission_id' => $permission->id,
                'model_id' => $this->id,
                'model_type' => 'App\Models\User'
            ])
        );

        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $this->userPermissions = $user->userPermissions();

        Flux::toast(text: __('Permission :permission added', ['permission' => $permission->name]));
    }

    public function removeDirectPermission( $permission_id ): void
    {
        $user = User::find($this->id);
        $permission = Permission::find($permission_id);

        DB::table('model_has_permissions')->where('permission_id', $permission->id)->where('model_type', 'App\Models\User')->where('model_id', $this->id)->delete();

        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $this->userPermissions = $user->userPermissions();

        Flux::toast(text: __('Permission :permission removed', ['permission' => $permission->name]));
    }
}
