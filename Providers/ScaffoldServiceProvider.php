<?php
/**
 * ScaffoldServiceProvider.php
 * Created by @anonymoussc on 03/11/2019 7:30 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/16/19 7:36 AM
 */

namespace App\Components\Scaffold\Providers;

use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\ServiceProvider;

class ScaffoldServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $dispatcher = $this->app->make('events');

        $dispatcher->subscribe(\App\Components\Scaffold\Listeners\UserEventSubscriber::class);
        $dispatcher->subscribe(\App\Components\Scaffold\Listeners\RoleEventSubscriber::class);
        $dispatcher->subscribe(\App\Components\Scaffold\Listeners\PermissionEventSubscriber::class);

        $this->publishes([
            __DIR__ . '/../Resources/views/vendor' => base_path() . '/resources/views/vendor',
        ], 'vendor_views');

        $this->publishes([
            __DIR__ . '/../Database/Seeders' => base_path() . '/database/seeds',
        ], 'vendor_dbseeds');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);

        $this->app->bind(\App\Components\Scaffold\Repositories\UserRepositoryInterface::class, function($app) {
            return new \App\Components\Scaffold\Repositories\User\UserRepository();
        });

        $this->app->bind(\App\Components\Scaffold\Repositories\RoleRepositoryInterface::class, function($app) {
            return new \App\Components\Scaffold\Repositories\Role\RoleRepository();
        });

        $this->app->bind(\App\Components\Scaffold\Repositories\PermissionRepositoryInterface::class, function($app) {
            return new \App\Components\Scaffold\Repositories\Permission\PermissionRepository();
        });
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('scaffold.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php', 'scaffold'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/scaffold');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath,
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function($path) {
            return $path . '/modules/scaffold';
        }, \Config::get('view.paths')), [$sourcePath]), 'scaffold');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/scaffold');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'scaffold');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'scaffold');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (!app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
