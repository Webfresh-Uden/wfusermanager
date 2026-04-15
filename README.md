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

