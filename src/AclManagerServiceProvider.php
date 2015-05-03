<?php

/**
 * @package   thinkingmik/acl-manager-laravel
 * @author    Michele Andreoli <michi.andreoli[at]gmail.com>
 * @copyright Copyright (c) Michele Andreoli
 * @license   http://mit-license.org/
 * @link      https://github.com/thinkingmik/acl-manager-laravel
 */

namespace ThinKingMik\AclManager;

use Illuminate\Support\ServiceProvider;
use ThinKingMik\AclManager\Filters\AclPolicyFilter;

class AclManagerServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {
        $this->loadViewsFrom(__DIR__ . '/views', 'acl-manager-laravel');
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'acl-manager-laravel');
        $this->bootFilters();
    }

    /**
     * Boot the filters
     * @return void
     */
    private function bootFilters() {
        $this->app['router']->filter('acl', 'ThinKingMik\AclManager\Filters\AclPolicyFilter');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->registerAssets();
        $this->registerAclManager();
        $this->registerFilterBindings();
    }

    /**
     * Register the assets to be published
     * @return void
     */
    public function registerAssets()
    {
        $configPath = __DIR__ . '/config/acl.php';
        $mFrom = __DIR__ . '/migrations/';
        $mTo = $this->app['path.database'] . '/migrations/';
        $this->mergeConfigFrom($configPath, 'acl');
        $this->publishes([$configPath => config_path('acl.php')], 'config');
        $this->publishes([
            $mFrom . '2014_10_24_102511_create_acl_resources_table.php' => $mTo . '2015_04_25_000001_create_acl_resources_table.php',
            $mFrom . '2014_10_24_102554_create_acl_roles_table.php' => $mTo . '2015_04_25_000002_create_acl_roles_table.php',
            $mFrom . '2014_10_24_102615_create_acl_permissions_table.php' => $mTo . '2015_04_25_000003_create_acl_permissions_table.php',
            $mFrom . '2014_10_24_102703_create_acl_roles_policies_table.php' => $mTo . '2015_04_25_000004_create_acl_roles_policies_table.php',
            $mFrom . '2014_10_24_102717_create_acl_users_policies_table.php' => $mTo . '2015_04_25_000005_create_acl_users_policies_table.php',
            $mFrom . '2014_10_24_102730_create_acl_users_roles_table.php' => $mTo . '2015_04_25_000006_create_acl_users_roles_table.php'
        ], 'migrations');
    }

    /**
     * Register AclManager with the IoC container
     * @return void
     */
    public function registerAclManager() {
        $this->app->bindShared('acl-manager.acl', function ($app) {
            $table = $app['config']->get('auth.table');
            $acl = new Acl($table);
            return $acl;
        });

        $this->app->bind('ThinKingMik\AclManager\Acl', function($app) {
            return $app['acl-manager.acl'];
        });
    }

    /**
     * Register the Filters to the IoC container because some filters need additional parameters
     * @return void
     */
    public function registerFilterBindings() {
        $this->app->bindShared('ThinKingMik\AclManager\Filters\AclPolicyFilter', function ($app) {
            $filter = new AclPolicyFilter($app['acl-manager.acl']);
            $param = $app['config']->get('acl.access_token_param');
            $callback = $app['config']->get('acl.callback');
            $filter->setTokenParam($param);
            $filter->setCallback($callback);
            return $filter;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return array('acl-manager.acl');
    }
}
