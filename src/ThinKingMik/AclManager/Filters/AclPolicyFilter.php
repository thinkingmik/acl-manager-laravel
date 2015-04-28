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
use ThinKingMik\AclManager\Exceptions\AclPolicyException;

class AclPolicyFilter extends AclBaseFilter {

    /**
     * Run the acl filter
     *
     * @internal param mixed $route, mixed $request, mixed $scope,...
     * @return void a bad response in case the request is invalid
     */
    public function filter() {
        $value = null;

        if (func_num_args() > 2) {
            $args = func_get_args();
            $value = array_slice($args, 2)[0];
        }

        $userId = $this->getSessionUserId();

        if ($this->tokenParam !== false && is_null($userId)) {
            $token = $this->getAccessToken();

            if (!empty($token)) {
                $userId = call_user_func($this->getCallback(), $token);
            }
        }

        if (!empty($userId)) {
            $ret = $this->acl->isRouteAllowed($userId, $value);
        } else {
            $msg = ($this->tokenParam !== false) ? ' ' . \Lang::get('acl-manager-laravel::messages.token_not_found') : '.';
            throw new AclServerErrorException(\Lang::get('acl-manager-laravel::messages.userid_not_found') . $msg);
        }

        if (!$ret) {
            throw new AclPolicyException($value);
        }
    }

}
