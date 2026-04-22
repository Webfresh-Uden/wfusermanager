<?php

namespace WebFresh\UserManager\Livewire;

use Illuminate\Support\Collection;
use Livewire\Attributes\Title;
use Livewire\Component;
use WebFresh\UserManager\Models\Team;

#[Title('Users')]
class Users extends Component
{
    public Collection $teams;

    public function mount() {
        $this->teams = Team::all()->sortBy('name');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('wfum::livewire.users');
    }
}
