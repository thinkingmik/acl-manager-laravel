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
class AclQueryException extends AclException {

    /**
     * Throw an AclQueryException exception
     */
    public function __construct($parameter) {
	$this->httpStatusCode = 500;
	$this->errorType = 'acl_query_error';
        parent::__construct(trans('acl-manager-laravel::messages.acl_query_error'));
    }

}
