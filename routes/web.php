<?php

use Illuminate\Support\Facades\Route;
use WebFresh\UserManager\Livewire\Teams;
use WebFresh\UserManager\Livewire\Users;
use WebFresh\UserManager\Livewire\Roles;
use WebFresh\UserManager\Livewire\Permissions;

Route::group([
    'prefix' => 'admin',
    'middleware' => ['web', 'auth'],
], function () {
    Route::livewire('teams', Teams::class)->name('teams.index');
    Route::livewire('users', Users::class)->name('users.index');
    Route::livewire('roles', Roles::class)->name('roles.index');
    Route::livewire('permissions', Permissions::class)->name('permissions.index');
});
