<?php

use Illuminate\Support\Facades\Route;
use WebFresh\UserManager\Livewire\Teams;
use WebFresh\UserManager\Livewire\Users;
use WebFresh\UserManager\Livewire\Roles;
use WebFresh\UserManager\Livewire\Permissions;
use WebFresh\UserManager\Livewire\UserSettings;
use WebFresh\UserManager\Livewire\PermissionsMatrix;
use WebFresh\UserManager\Livewire\PermissionGroups;

Route::group([
    'prefix' => 'admin',
    'middleware' => ['web', 'auth'],
], function () {
    Route::livewire('teams', Teams::class)->name('teams.index');
    Route::livewire('users', Users::class)->name('users.index');
    Route::livewire('roles', Roles::class)->name('roles.index');
    Route::livewire('profile/platform', UserSettings::class)->name('wfum_usersettings.edit');
    Route::livewire('permissions', Permissions::class)->name('permissions.index');
    Route::livewire('permissions-matrix', PermissionsMatrix::class)->name('permissions.matrix');
    Route::livewire('permission-groups', PermissionGroups::class)->name('permissions.groups');
});
