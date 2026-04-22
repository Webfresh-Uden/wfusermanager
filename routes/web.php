<?php

use Illuminate\Support\Facades\Route;
use WebFresh\UserManager\Livewire\Teams;
use WebFresh\UserManager\Livewire\Users;

Route::group([
    'prefix' => 'admin',
    'middleware' => ['web', 'auth'],
], function () {
    Route::livewire('teams', Teams::class)->name('teams.index');
    Route::livewire('users', Users::class)->name('users.index');
});
