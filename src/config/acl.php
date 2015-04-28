<?php

/**
 * @package   thinkingmik/acl-manager-laravel
 * @author    Michele Andreoli <michi.andreoli[at]gmail.com>
 * @copyright Copyright (c) Michele Andreoli
 * @license   http://mit-license.org/
 * @link      https://github.com/thinkingmik/acl-manager-laravel
 */
return [

    /*
      |--------------------------------------------------------------------------
      | Cache the results of a query (unit are minutes)
      |--------------------------------------------------------------------------
      |
      | While the results are cached, the query will not be run against the database, and the results
      | will be loaded from the default cache driver specified for your application.
      | If you don't want cache the queries you can set this parameter to 0 minutes.
      |
     */
    'cache_results' => 0,
    /*
      |--------------------------------------------------------------------------
      | Access token param name, for RESTful apis
      |--------------------------------------------------------------------------
      |
      | Set this attribute to FALSE if you don't have RESTful apis protected by access token,
      | otherwise specify access token param name to retrieve user id
      |
     */
    'access_token_param' => FALSE,
    /*
      |--------------------------------------------------------------------------
      | Custom callback to retrieve user id from access token
      |--------------------------------------------------------------------------
      |
      | If you set access_token_param, specify the query to get user id from access token
      |
     */
    'callback' => function($token) {
        $ret = \DB::table('oauth_access_tokens_table')
                ->leftJoin('oauth_sessions', 'oauth_access_tokens.session_id', '=', 'oauth_sessions.id')
                ->where('oauth_access_tokens.id', $token)
                ->first();

        if ($ret) {
            return $ret->owner_id;
        }
    }
];
