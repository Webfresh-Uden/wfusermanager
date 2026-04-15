<?php
namespace WebFresh\UserManager;

use Illuminate\Support\ServiceProvider;

class UserManagerServiceProvider extends ServiceProvider
{
  public function register()
  {
    //
  }

  public function boot()
  {
    $this->publishes([
        __DIR__.'/../config/wfusermanager.php' => config_path('wfusermanager.php'),
    ], 'config');
  }
}
