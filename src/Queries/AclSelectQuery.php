<?php

/**
 * @package   thinkingmik/acl-manager-laravel
 * @author    Michele Andreoli <michi.andreoli[at]gmail.com>
 * @copyright Copyright (c) Michele Andreoli
 * @license   http://mit-license.org/
 * @link      https://github.com/thinkingmik/acl-manager-laravel
 */

namespace ThinKingMik\AclManager\Queries;

use ThinKingMik\AclManager\Exceptions\AclQueryException;

class AclSelectQuery extends AclBaseQuery {

    private function getResults($query, $key = null) {
        if ($this->caching > 0) {
            return $query->remember($this->caching, $key)->get();
        }
        return $query->get();
    }

    public function selectResourcesByUsersId($users, $resources, $permissions) {
        try {
            $first = \DB::table($this->userTable . ' AS system_users')
                    ->select('acl_roles.name AS role', 'acl_roles_policies.resource_id AS resource', 'acl_roles_policies.permission_id AS permission')
                    ->join('acl_users_roles', 'acl_users_roles.user_id', '=', 'system_users.id')
                    ->join('acl_roles', 'acl_users_roles.role_id', '=', 'acl_roles.id')
                    ->join('acl_roles_policies', 'acl_users_roles.role_id', '=', 'acl_roles_policies.role_id')
                    ->whereIn('system_users.id', $users)
                    ->where(function ($subquery) {
                        $subquery->where('acl_roles_policies.expiration', '>', new \DateTime('today'))
                        ->orWhere('acl_roles_policies.expiration', '=', '0000-00-00')
                        ->orWhereNull('acl_roles_policies.expiration');
                    })
                    ->whereIn('acl_roles_policies.resource_id', $resources)
                    ->whereIn('acl_roles_policies.permission_id', $permissions);

            $last = \DB::table($this->userTable . ' AS system_users')
                    ->select(\DB::raw('\'*\' AS role'), 'acl_users_policies.resource_id AS resource', 'acl_users_policies.permission_id AS permission')
                    ->join('acl_users_policies', 'system_users.id', '=', 'acl_users_policies.user_id')
                    ->whereIn('system_users.id', $users)
                    ->where(function ($subquery) {
                        $subquery->where('acl_users_policies.expiration', '>', new \DateTime('today'))
                        ->orWhere('acl_users_policies.expiration', '=', '0000-00-00')
                        ->orWhereNull('acl_users_policies.expiration');
                    })
                    ->whereIn('acl_users_policies.resource_id', $resources)
                    ->whereIn('acl_users_policies.permission_id', $permissions)
                    ->union($first);

            return $this->getResults($last);
        } catch (\Exception $ex) {
            throw new AclQueryException('selectResourcesByUsersId. ' . $ex->getMessage());
        }
    }

    public function selectResourcesByRoles($roles, $resources, $permissions) {
        try {
            $query = \DB::table('acl_roles')
                    ->select('acl_roles.name AS role', 'acl_roles_policies.resource_id', 'acl_roles_policies.permission_id')
                    ->join('acl_roles_policies', 'acl_roles.id', '=', 'acl_roles_policies.role_id')
                    ->whereIn('acl_roles.name', $roles)
                    ->where(function ($subquery) {
                        $subquery->where('acl_roles_policies.expiration', '>', new \DateTime('today'))
                        ->orWhere('acl_roles_policies.expiration', '=', '0000-00-00')
                        ->orWhereNull('acl_roles_policies.expiration');
                    })
                    ->whereIn('acl_roles_policies.resource_id', $resources)
                    ->whereIn('acl_roles_policies.permission_id', $permissions);

            return $this->getResults($query);
        } catch (\Exception $ex) {
            throw new AclQueryException('selectResourcesByRoles. ' . $ex->getMessage());
        }
    }

    public function selectRolesByUsersId($users, $roles) {
        try {
            $query = \DB::table($this->userTable . ' AS system_users')
                    ->select('acl_roles.name')
                    ->join('acl_users_roles', 'acl_users_roles.user_id', '=', 'system_users.id')
                    ->join('acl_roles', 'acl_users_roles.role_id', '=', 'acl_roles.id')
                    ->whereIn('system_users.id', $users)
                    ->whereIn('acl_roles.name', $roles);

            return $this->getResults($query);
        } catch (\Exception $ex) {
            throw new AclQueryException('selectRolesByUsersId. ' . $ex->getMessage());
        }
    }

    public function selectAllByUserId($userId) {
        try {
            $first = \DB::table($this->userTable . ' AS system_users')
                    ->select('acl_roles.name AS role', 'acl_roles_policies.resource_id AS resource', 'acl_roles_policies.permission_id AS permission')
                    ->join('acl_users_roles', 'acl_users_roles.user_id', '=', 'system_users.id')
                    ->join('acl_roles', 'acl_users_roles.role_id', '=', 'acl_roles.id')
                    ->leftJoin('acl_roles_policies', 'acl_users_roles.role_id', '=', 'acl_roles_policies.role_id')
                    ->where('system_users.id', $userId);

            $last = \DB::table($this->userTable . ' AS system_users')
                    ->select(\DB::raw('\'*\' AS role'), 'acl_users_policies.resource_id AS resource', 'acl_users_policies.permission_id AS permission')
                    ->join('acl_users_policies', 'system_users.id', '=', 'acl_users_policies.user_id')
                    ->where('system_users.id', $userId)
                    ->union($first);

            return $this->getResults($last);
        } catch (\Exception $ex) {
            throw new AclQueryException('selectAllByUserId. ' . $ex->getMessage());
        }
    }

}
