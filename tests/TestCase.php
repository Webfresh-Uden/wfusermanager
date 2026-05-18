<?php

namespace WebFresh\UserManager\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use WebFresh\UserManager\Models\WfumUser;
use WebFresh\UserManager\UserManagerServiceProvider;

abstract class TestCase extends Orchestra
{
    /**
     * add the package provider
     *
     * @param $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            UserManagerServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function tearDown(): void
    {
        DirectoryEmulator::teardown();
        parent::tearDown();
    }

    protected function user()
    {
        return (WfumUser::factory()->create());
    }
}
