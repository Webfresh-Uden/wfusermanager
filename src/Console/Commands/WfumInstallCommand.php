<?php

namespace WebFresh\UserManager\Console\Commands;

use App;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use WebFresh\UserManager\Models\Team;

#[Signature('wfum:install')]
#[Description('Command description')]
class WfumInstallCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Installing Webfresh User Manager...');

        if (! App::isProduction()) {
            $confirmed = $this->confirm('Do you want to use the ENV data to create a user?', false);
        }

        $first_name = env('WFUM_USER_FIRST_NAME', 'Webfresh');
        $last_name = env('WFUM_USER_LAST_NAME', 'Administrator');
        $email = env('WFUM_USER_EMAIL', 'roel@webfresh.nl');
        $password = env('WFUM_USER_PASSWORD', 'WebFresh2026');

        if ($confirmed === false) {
            $first_name = $this->ask('What is the first name?');
            $last_name = $this->ask('What is the last name?');
            $email = $this->ask('What is the email address?');
            $password = $this->secret('What is the password? Save this, you\'ll never see it again.');
        }

        $user = User::create([
            'name' => sprintf('%s %s', $first_name, $last_name),
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        $team = Team::create([
            'name' => 'Administrators',
            'team_id' => 0,
        ]);

        $role = Role::create(['name' => 'Developer', 'team_id' => $team->id]);

        setPermissionsTeamId($team->id);

        $user->assignRole($role);

        $this->info('User $first_name $last_name was created');

        $this->info('Installation completed, enjoy!');
    }
}
