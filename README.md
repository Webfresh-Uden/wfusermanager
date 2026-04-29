# wfusermanager
User Manager for Laravel, based on Laravel, Spatie's permissions package, Livewire and Tailwind CSS.

## Installation
__First require the package via composer:__
- composer require webfresh/wfusermanager

__The publish the config file and migrations:__
- php artisan vendor:publish --provider="Webfresh\UserManager\UserManagerServiceProvider"

__In addition to this plugin, Spatie's permissions package will be installed.__
- php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

If you don't wish to fragment the user base with teams/groups, you can skip the configuration.
However is you wish to have more control; find the "teams" key in config/permissions.php after publishing to use the teams functionality.

### Migrate the database

The following tables will be created:
- Spatie's permissions tables
- User manager tables

Run the following command to migrate the database:

- php artisan migrate

The user manager is installed, have fun!

### view components
Shadow login Component:
- __resources/views/layouts/app/sidebar.blade.php__
Add this to add a header component which is shown when someone is using a shadow login:
<livewire:wfum::components.shadow-login />

Profile settings component:
- __resources/views/components/settings/layout.blade.php__
Add this to add a link to the User Manager settings for users:
- <flux:navlist.item :href="route('wfum_usersettings.edit')" wire:navigate>{{ __('wfum::wfum.profile_menu_title') }}</flux:navlist.item>

Main navigation:
- __resources/views/layouts/app/sidebar.blade.php__
Add this to add a link to the User Manager settings for admins:

<flux:dropdown>
    <flux:button class="w-full" icon="user" align="start" icon:trailing="chevron-down">User management</flux:button>
    <flux:navmenu>
        @if( config('permission.teams') )
            <flux:navmenu.item :href="route('teams.index')" icon="user-group">{{ __('Teams') }}</flux:navmenu.item>
        @endif
        <flux:navmenu.item :href="route('users.index')" icon="user">{{ __('Users') }}</flux:navmenu.item>
        <flux:navmenu.item :href="route('roles.index')" icon="identification">{{ __('Roles') }}</flux:navmenu.item>
        <flux:navmenu.item :href="route('permissions.index')" icon="swatch">{{ __('Permissions') }}</flux:navmenu.item>
    </flux:navmenu>
</flux:dropdown>

## Done
- Create package
- Configure composer.json's dependencies
- Create Service Provider
- Create Config file
- Create testing framework
- Create console command for easy installation
- Created migration for team definitions (We want named teams!)

## ToDo
- Finish console command
- Create migrations
- Create Interface / Livewire components for user management
- Create Tests

