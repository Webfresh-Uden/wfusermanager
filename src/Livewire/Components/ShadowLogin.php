<?php

namespace WebFresh\UserManager\Livewire\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use WebFresh\UserManager\Models\WfumUser as User;

class ShadowLogin extends Component
{
    protected $listeners = [
        'shadowlogin' => 'shadowLoginAction',
        'shadowlogout' => 'shadowLogoutAction'
    ];

    public $originalUser = null;
    public $currentUser = null;

    public function render(): View
    {
        $this->detectCurrentUser();
        $this->detectOriginalUser();
        return view('wfum::livewire.components.shadow_login', [
            'originalUser' => $this->originalUser,
            'currentUser' => $this->currentUser
        ]);
    }

    public function shadowLogoutAction()
    {
        $uid = session('uid');
        session()->forget('uid');
        Auth::login(User::find($uid));
        return redirect(request()->header('Referer'));
    }

    public function shadowLoginAction($user_id)
    {
        session(['uid' => Auth::user()->id]);
        Auth::login(User::find($user_id));
        return redirect(request()->header('Referer'));
    }

    public function detectCurrentUser()
    {
        $this->currentUser = Auth::user();
    }

    public function detectOriginalUser(): void
    {
        if( session()->has('uid') ) {
            $this->originalUser = User::find(session('uid'));
        } else {
            $this->originalUser = null;
        }
    }
}
