<?php

namespace WebFresh\UserManager\Livewire\Components;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use WebFresh\UserManager\Models\Team;
use WebFresh\UserManager\Models\WfumUser as User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class TeamSwitcher extends Component
{
    public $team_id = 0;
    public Collection $teams;

    public function mount()
    {
        setPermissionsTeamId(session('team_id'));
        $this->team_id = session('team_id');
        $this->teams = Team::all();
    }

    public function render(): View
    {
        $this->team_id = session('team_id');
        return view('wfum::livewire.components.team_switcher');
    }

    public function updateTeamId()
    {
        session(['team_id' => (int)$this->team_id]);
        setPermissionsTeamId((int)$this->team_id);
        $teamName = ((int)$this->team_id === 0 ? 'Global':$this->teams->firstWhere('id', $this->team_id)?->name);
        $requestPath = URL::previousPath();
        Auth::user()->unsetRelation('roles')->unsetRelation('permissions');
        return Redirect::to( $requestPath )->with('message', "Switched to team: $teamName");
    }
}
