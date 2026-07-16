<?php

namespace WebFresh\UserManager\Livewire;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use WebFresh\UserManager\Models\WfumUser;

#[Title('Platform Settings')]
class UserSettings extends Component
{
    private $user;

    public $shadow_opt_out = false;

    public function rules()
    {
        return [
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount(): void {
        $this->user = WfumUser::find(auth()->user()->id);
        $this->shadow_opt_out = $this->user->shadow_opt_out;
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        $this->user = WfumUser::find(auth()->user()->id);
        return view('wfum::livewire.usersettings');
    }

    public function updatePlatformAdministration(): void
    {
        $this->user = WfumUser::find(auth()->user()->id);
        $this->user->shadow_opt_out = $this->shadow_opt_out;
        $this->user->save();

        Flux::toast(text: __('Platform settings updated'));
    }
}
