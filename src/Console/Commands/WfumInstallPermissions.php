<?php

namespace WebFresh\UserManager\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use WebFresh\UserManager\Models\PermissionGroup;
use WebFresh\UserManager\Models\Team;
use App;

#[Signature('wfum:installpermissions')]
#[Description('Command description')]
class WfumInstallPermissions extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Installing Webfresh User Manager permissions...');

        if( !App::isProduction() ) {
            $confirmed = $this->confirm(
                'Do you want to use the ENV data to install the permissions?',
                false);
        }

        $first_name = env('WFUM_USER_FIRST_NAME', 'Webfresh');
        $last_name = env('WFUM_USER_LAST_NAME', 'Administrator');
        $email = env('WFUM_USER_EMAIL', 'roel@webfresh.nl');
        $password = env('WFUM_USER_PASSWORD', 'WebFresh2026');

        if( $confirmed === false ) {
            $first_name = $this->ask('What is the first name?');
            $last_name = $this->ask('What is the last name?');
            $email = $this->ask('What is the email address?');
            $password = $this->secret('What is the password? Save this, you\'ll never see it again.');
        }

        foreach( config('wfusermanager.permissions') as $permissionGroup => $permissionList ) {
            $pg = PermissionGroup::create([
                'name' => $permissionGroup,
            ]);
            $this->info("Permission group $permissionGroup was created");
            foreach ($permissionList as $permission => $guard) {
                Permission::create([
                    'name' => $permission,
                    'guard_name' => 'web',
                    'permission_group_id' => $pg->id,
                ]);
                $this->info("Permission $permission was created");
            }
        }

        $this->info('Installation completed, enjoy!');
        //
    }
}
