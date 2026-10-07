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
    'middleware' => ['web', 'auth', 'verified'],
], function () {
    Route::get('teams', Teams::class)->name('teams.index');
    Route::get('users', Users::class)->name('users.index');
    Route::get('roles', Roles::class)->name('roles.index');
    Route::get('profile/platform', UserSettings::class)->name('wfum_usersettings.edit');
    Route::get('permissions', Permissions::class)->name('permissions.index');
    Route::get('permissions-matrix', PermissionsMatrix::class)->name('permissions.matrix');
    Route::get('permission-groups', PermissionGroups::class)->name('permissions.groups');
});
