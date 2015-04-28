<?php

/**
 * @package   thinkingmik/acl-manager-laravel
 * @author    Michele Andreoli <michi.andreoli[at]gmail.com>
 * @copyright Copyright (c) Michele Andreoli
 * @license   http://mit-license.org/
 * @link      https://github.com/thinkingmik/acl-manager-laravel
 */

namespace ThinKingMik\AclManager\Exceptions;

/**
 * Exception class
 */
class AclPolicyException extends AclException {

    /**
     * Throw an AclPolicyException exception
     */
    public function __construct($parameter) {
	$this->httpStatusCode = 401;
	$this->errorType = 'acl_invalid_policy';
        parent::__construct(\Lang::get('acl-manager-laravel::messages.acl_invalid_policy', array('name' => $parameter)));
    }

}
