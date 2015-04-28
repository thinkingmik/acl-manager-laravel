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
class AclServerErrorException extends AclException {

    /**
     * Throw an AclServerErrorException exception
     */
    public function __construct($parameter) {
	$this->httpStatusCode = 500;
	$this->errorType = 'acl_server_error';
        parent::__construct($parameter);
    }

}
