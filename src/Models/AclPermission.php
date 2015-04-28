<?php

/**
 * @package   thinkingmik/acl-manager-laravel
 * @author    Michele Andreoli <michi.andreoli[at]gmail.com>
 * @copyright Copyright (c) Michele Andreoli
 * @license   http://mit-license.org/
 * @link      https://github.com/thinkingmik/acl-manager-laravel
 */

namespace ThinKingMik\AclManager\Models;

class AclPermission extends \Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'acl_permissions';

    /**
     * The fillable fields.
     *
     * @var array
     */
    protected $fillable = array('name', 'description');

    /**
     * The guarded fields.
     *
     * @var array
     */
    protected $guarded = array('id', 'created_at', 'updated_at');

    /**
     * Get the unique identifier.
     *
     * @return mixed
     */
    public function getIdentifier() {
        return $this->getKey();
    }

}
