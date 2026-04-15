<?php
namespace WebFresh\UserManager;

use Illuminate\Support\ServiceProvider;
use WebFresh\UserManager\Console\Commands\WfomInstallCommand;

class UserManagerServiceProvider extends ServiceProvider
{
  public function register()
  {
    //
  }

  public function boot()
  {
    // Register the command if we are using the application via the CLI
    if ($this->app->runningInConsole()) {
      $this->commands([
          WfomInstallCommand::class,
      ]);
    }

    $this->publishes([
        __DIR__.'/../config/wfusermanager.php' => config_path('wfusermanager.php'),
    ], 'config');
  }
}
