<?php

namespace WebFresh\UserManager;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use WebFresh\UserManager\Console\Commands\WfumInstallCommand;
use WebFresh\UserManager\Console\Commands\WfumInstallPermissions;

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
                WfumInstallPermissions::class,
            ]);
        }

        $this->publishes([__DIR__.'/../config/wfusermanager.php' => config_path('wfusermanager.php')], 'config');
        $this->publishes([__DIR__.'/../database/migrations/' => database_path('migrations')], 'migrations');
        $this->publishes([__DIR__.'/../resources/views' => resource_path('views/vendor/wfum')], 'wfum-views');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadJsonTranslationsFrom(__DIR__.'/../lang', 'wfum');

        Livewire::addNamespace(
            namespace: 'wfum',
            classNamespace: 'WebFresh\\UserManager\\Livewire',
            classPath: __DIR__.'/Livewire',
            classViewPath: __DIR__.'/../resources/views/livewire',
        );

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'wfum');
    }
}
