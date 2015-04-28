<?php

/**
 * @package   thinkingmik/acl-manager-laravel
 * @author    Michele Andreoli <michi.andreoli[at]gmail.com>
 * @copyright Copyright (c) Michele Andreoli
 * @license   http://mit-license.org/
 * @link      https://github.com/thinkingmik/acl-manager-laravel
 */

namespace ThinKingMik\AclManager;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\ServiceProvider;
use ThinKingMik\AclManager\Exceptions\AclException;
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
        $this->package('thinkingmik/acl-manager-laravel');

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
        $this->registerErrorHandlers();
        $this->registerAclManager();
        $this->registerFilterBindings();
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
            $param = $app['config']->get('acl-manager-laravel::acl.access_token_param');
            $callback = $app['config']->get('acl-manager-laravel::acl.callback');
            $filter = new AclPolicyFilter($app['acl-manager.acl']);
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

    /**
     * Register the AclManager error handlers
     * @return void
     */
    private function registerErrorHandlers() {
        $this->app->error(function(AclException $ex) {
            if (\Request::ajax() && \Request::wantsJson()) {
                return new JsonResponse([
                    'error' => $ex->errorType,
                    'error_description' => $ex->getMessage()
                        ], $ex->httpStatusCode, $ex->getHttpHeaders()
                );
            }

            return \View::make('acl-manager-laravel::acl_error', array(
                        'header' => $ex->getHttpHeaders()[0],
                        'code' => $ex->httpStatusCode,
                        'error' => $ex->errorType,
                        'message' => $ex->getMessage()
            ));
        });
    }

}
