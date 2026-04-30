<?php

namespace WebFresh\UserManager\Livewire;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WebFresh\UserManager\Models\Team;

#[Title('Teams')]
class Teams extends Component
{
    use WithPagination;

    private $teams;

    public string $sortBy = 'name';

    public string $sortDirection = 'asc';

    public bool $showTeamWriteModal = false;

    public bool $showTeamDeleteModal = false;

    public string $name = '';

    public string $id = '';

    public function mount(): void {}

    #[Layout('layouts.app')]
    public function render(): View
    {
        $this->teams = DB::table('wfum_teams')->orderBy('name', $this->sortDirection)->paginate(15);

        return view('wfum::livewire.teams', [
            'teams' => $this->teams,
        ]);
    }

    public function writeTeamAction(): void
    {
        Team::updateOrCreate([
            'id' => $this->id,
        ], [
            'name' => $this->name,
        ]);

        $this->clearFieldData();
        $this->showTeamWriteModal = false;

        Flux::toast(text: __('Team :team updated', ['team' => $this->name]));
    }

    public function showWriteTeamModal($team_id): void
    {
        $team = Team::find($team_id);

        $this->name = $team->name;
        $this->id = $team->id;
        $this->showTeamWriteModal = true;
    }

    public function showDeleteTeamModal($team_id): void
    {
        $this->id = $team_id;
        $this->showTeamDeleteModal = true;
    }

    public function clearFieldData(): void
    {
        $this->id = '';
        $this->name = '';
    }

    public function deleteTeamAction(): void
    {
        $team = Team::find($this->id);
        $teamName = $team->name;
        $team->delete();
        $this->clearFieldData();
        $this->showTeamDeleteModal = false;

        Flux::toast(text: __('Team :team removed', ['team' => $teamName]));
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
