<?php

namespace MedianetDev\LaravelAuthApi;

use Illuminate\Support\ServiceProvider;

class LaravelAuthApiServiceProvider extends ServiceProvider
{
    protected $commands = [
        \MedianetDev\LaravelAuthApi\Console\Commands\PublishApiUserModel::class,
    ];

    /**
     * Bootstrap the application services.
     */
    public function boot(\Illuminate\Routing\Router $router)
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-auth-api');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-auth-api');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->loadConfigs();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-auth-api.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-auth-api'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-auth-api'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-auth-api'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-auth-api');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-auth-api', function () {
            return new LaravelAuthApi;
        });

        // register the artisan commands
        $this->commands($this->commands);
    }

    public function loadConfigs()
    {
        // dd(config('laravel-auth-api.user_model_fqn'));
        // add the backpack_users authentication provider to the configuration
        app()->config['auth.providers'] = app()->config['auth.providers'] +
        [
            'apiauth' => [
                'driver'  => 'eloquent',
                'model'   => config('laravel-auth-api.user_model_fqn') ?: \MedianetDev\LaravelAuthApi\Models\ApiUser::class,
            ],
        ];
        // add the backpack_users password broker to the configuration
        app()->config['auth.passwords'] = app()->config['auth.passwords'] +
        [
            'apiauth' => [
                'provider'  => 'apiauth',
                'table'     => 'password_resets',
                'expire'    => 15,
            ],
        ];
        // add the backpack_users guard to the configuration
        app()->config['auth.guards'] = app()->config['auth.guards'] +
        [
            'apiauth' => [
                'driver'   => 'passport',
                'provider' => 'apiauth',
            ],
        ];
        // just to resolve the Auth::guard()->attempt() problem in the login controller
        app()->config['auth.guards'] = app()->config['auth.guards'] +
        [
            'apiauthweb' => [
                'driver'   => 'session',
                'provider' => 'apiauth',
            ],
        ];
    }
}
