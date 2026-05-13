<?php

namespace WebFresh\UserManager\Livewire\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use WebFresh\UserManager\Models\WfumUser as User;
use WebFresh\UserManager\Models\Team;

class Dashboard extends Component
{
    public int $teamCount = 0;
    public int $userCount = 0;
    public int $userActiveCount = 0;
    public int $userBlockedCount = 0;
    public int $roleCount = 0;
    public int $permissionCount = 0;

    public function mount()
    {
        $this->teamCount = Team::count();
        $this->userCount = User::count();
        $this->userActiveCount = User::where('blocked', 0)->count();
        $this->userBlockedCount = User::where('blocked', 1)->count();
        $this->roleCount = Role::count();
        $this->permissionCount = Permission::count();
    }

    public function render(): View
    {
        return view('wfum::livewire.components.dashboard');
    }
}
