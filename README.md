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

```shell
[php/sail] artisan migrate
```

### Seed the database

To seed the permissions run:

```shell
[php/sail] artisan wfum:install
[php/sail] artisan wfum:installpermissions
```

The user manager is installed, have fun!

### view components
Shadow login Component:
- __resources/views/layouts/app/sidebar.blade.php__
Add this to add a header component which is shown when someone is using a shadow login:
```
<livewire:wfum::components.shadow-login />
```
Profile settings component:
- __resources/views/components/settings/layout.blade.php__
Add this to add a link to the User Manager settings for users:
```
<flux:navlist.item :href="route('wfum_usersettings.edit')" wire:navigate>{{ __('wfum::wfum.profile_menu_title') }}</flux:navlist.item>
```
Main navigation:
- __resources/views/layouts/app/sidebar.blade.php__
Add this to add a link to the User Manager settings for admins:
```
<flux:dropdown>
    <flux:button class="w-full" icon="user" align="start" icon:trailing="chevron-down">User management</flux:button>
    <flux:navmenu>
        @if( config('permission.teams') )
            <flux:navmenu.item :href="route('teams.index')" icon="user-group">{{ __('Teams') }}</flux:navmenu.item>
        @endif
        <flux:navmenu.item :href="route('users.index')" icon="user">{{ __('Users') }}</flux:navmenu.item>
        <flux:navmenu.item :href="route('roles.index')" icon="identification">{{ __('Roles') }}</flux:navmenu.item>
        <flux:navmenu.item :href="route('permissions.index')" icon="swatch">{{ __('Permissions') }}</flux:navmenu.item>
        <flux:navmenu.item :href="route('permissions.groups')" icon="inbox-stack">{{ __('Permission Groups') }}</flux:navmenu.item>
        <flux:navmenu.item :href="route('permissions.matrix')" icon="inbox-stack">{{ __('Permissions Matrix') }}</flux:navmenu.item>
    </flux:navmenu>
</flux:dropdown>
```
Dashboard statistics component:
- __resources/views/dashboard.blade.php__
Add this to show some basic statistics on the dashboard:
```
<livewire:wfum::components.dashboard/>
```
## Testing
The package uses Pest for testing. To run the tests, add the following lines to the project's composer.json:
```
"autoload-dev": {
    "psr-4": {
        "WebFresh\\UserManager\\Tests\\": "vendor/webfresh/usermanager/tests/",
        "WebFresh\\UserManager\\Database\\Factories\\": "vendor/webfresh/usermanager/database/factories"
    }
},  
```
Then run the usual Laravel project test to run the tests

## Done
- Create package
- Configure composer.json's dependencies
- Create Service Provider
- Create Config file
- Create testing framework
- Create console command for easy installation
- Created migration for team definitions (We want named teams!)
- Created migrations for Shadow Logins and Blocked Users
- Created Livewire components for Shadow Logins and User Settings
- Create Tests
- Disable deletion of said roles and permissions
## ToDo
### Console and back-end updates
- Finish console command
- Prepopulate permissions and roles for the user manager
  - Create a seeder to create the default permissions and roles for the user manager.
  - Assign the "User Manager" role to the user who runs the installation command.
- Update the user manager to operate without a team ID present.
### Default workflow hacks
- Add the appropriate team ID when a user registers by itself.
### GUI updates
- Add team switcher to sidebar


