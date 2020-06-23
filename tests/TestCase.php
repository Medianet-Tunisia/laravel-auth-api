<?php

namespace MedianetDev\LaravelAuthApi\Tests;

use Faker\Factory;
use Laravel\Passport\PassportServiceProvider;
use MedianetDev\LaravelAuthApi\LaravelAuthApiServiceProvider;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

class TestCase extends TestbenchTestCase
{
    /**
     * Faker instance.
     *
     * @var \Faker\Generator
     */
    public $faker;

    public function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();

        // migrate the package tables
        $this->artisan('migrate');
        // load migration for testing only
        $this->loadMigrationsFrom(__DIR__.'/config/database/migrations');
        // install laravel passport
        $this->artisan('passport:install');
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelAuthApiServiceProvider::class,
            PassportServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('laravel-auth-api.auto_send_verify_email', false);
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
