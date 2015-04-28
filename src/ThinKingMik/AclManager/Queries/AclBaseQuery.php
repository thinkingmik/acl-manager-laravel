<?php

/**
 * @package   thinkingmik/acl-manager-laravel
 * @author    Michele Andreoli <michi.andreoli[at]gmail.com>
 * @copyright Copyright (c) Michele Andreoli
 * @license   http://mit-license.org/
 * @link      https://github.com/thinkingmik/acl-manager-laravel
 */

namespace ThinKingMik\AclManager\Queries;

class AclBaseQuery {

    /**
     * The users table name
     * @var string
     */
    protected $userTable;

    /**
     * The query caching minutes
     * @var integer
     */
    protected $caching;

    /**
     * @param string $table
     */
    public function __construct($table) {
        $this->userTable = $table;
        $this->caching = \Config::get('acl-manager-laravel::acl.cache_results');
    }

}
