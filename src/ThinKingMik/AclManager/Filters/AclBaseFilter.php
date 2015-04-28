<?php

/**
 * @package   thinkingmik/acl-manager-laravel
 * @author    Michele Andreoli <michi.andreoli[at]gmail.com>
 * @copyright Copyright (c) Michele Andreoli
 * @license   http://mit-license.org/
 * @link      https://github.com/thinkingmik/acl-manager-laravel
 */

namespace ThinKingMik\AclManager\Filters;

use ThinKingMik\AclManager\Exceptions\AclServerErrorException;
use ThinKingMik\AclManager\Acl;

class AclBaseFilter {

    /**
     * The Acl instance
     * @var \ThinKingMik\AclManager\Acl
     */
    protected $acl;

    /**
     * The access token param name
     * @var string
     */
    protected $tokenParam;

    /**
     * The callable callback
     * @var callable
     */
    protected $callback;

    /**
     * @param Acl $acl
     */
    public function __construct(Acl $acl) {
        $this->acl = $acl;
    }

    /**
     * Set the access token param name
     * @param  string $param The access token param name
     * @return void
     */
    public function setTokenParam($param) {
        $this->tokenParam = $param;
    }

    /**
     * Return the access token param name
     * @return string
     */
    protected function getTokenParam() {
        if (empty($this->tokenParam) && $this->tokenParam !== false) {
            throw new AclServerErrorException(\Lang::get('acl-manager-laravel::messages.invalid_token_param'));
        }

        return $this->tokenParam;
    }

    /**
     * Set the callback to get user id
     * @param  callable $callback The callback function
     * @return void
     */
    public function setCallback(callable $callback) {
        $this->callback = $callback;
    }

    /**
     * Return the callback function
     * @return callable
     */
    protected function getCallback() {
        if (($this->tokenParam !== false || !empty($this->tokenParam)) && (is_null($this->callback) || !is_callable($this->callback))) {
            throw new AclServerErrorException(\Lang::get('acl-manager-laravel::messages.invalid_callback'));
        }

        return $this->callback;
    }

    /**
     * Return the input access token from request
     * @return string
     */
    protected function getAccessToken() {
        $param = $this->getTokenParam();
        $token = \Input::get($param);

        return $token;
    }

    /**
     * Return the user id of the logged in user
     * @return string
     */
    protected function getSessionUserId() {
        if (\Auth::check()) {
            return \Auth::user()->id;
        }

        return null;
    }

}
