<?php
namespace WebFresh\UserManager;

use Illuminate\Support\ServiceProvider;
use WebFresh\UserManager\Console\Commands\WfumInstallCommand;

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
          WfumInstallCommand::class,
      ]);
    }

    $this->publishes([
        __DIR__ . '/../config/wfusermanager.php' => config_path('wfusermanager.php'),
    ], 'config');

    $this->publishes([
      __DIR__ . '/../database/migrations/' => database_path('migrations'),
    ], 'migrations');
  }
}
