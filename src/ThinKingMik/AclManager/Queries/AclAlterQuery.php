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

class AclAlterQuery extends AclBaseQuery {

    public function addResourcesPermissionsToUsers($users, $resources, $permissions, $expires) {
        try {
            $array = AclQueryUtils::arraysPolicyPermutations('user_id', $users, $resources, $permissions, $expires);
            $res = \DB::table('acl_users_policies')->insert($array);
            return $res;
        } catch (\Exception $ex) {
            throw new AclQueryException('addResourcesPermissionsToUsers. ' . $ex->getMessage());
        }
    }

    public function addResourcesPermissionsToRoles($roles, $resources, $permissions, $expires) {
        try {
            $array = AclQueryUtils::arraysPolicyPermutations('role_id', $roles, $resources, $permissions, $expires);
            $res = \DB::table('acl_roles_policies')->insert($array);
            return $res;
        } catch (\Exception $ex) {
            throw new AclQueryException('addResourcesPermissionsToRoles. ' . $ex->getMessage());
        }
    }

    public function deleteResourcesPermissionsFromUsers($users, $resources, $permissions) {
        try {
            $res = \DB::table('acl_users_policies')
                    ->whereIn('acl_users_policies.user_id', $users)
                    ->whereIn('acl_users_policies.resource_id', $resources)
                    ->whereIn('acl_users_policies.permission_id', $permissions)
                    ->delete();
            return $res;
        } catch (\Exception $ex) {
            throw new AclQueryException('deleteResourcesPermissionsFromUsers. ' . $ex->getMessage());
        }
    }

    public function deleteResourcesPermissionsFromRoles($roles, $resources, $permissions) {
        try {
            $res = \DB::table('acl_roles_policies')
                    ->whereIn('acl_roles_policies.role_id', $roles)
                    ->whereIn('acl_roles_policies.resource_id', $resources)
                    ->whereIn('acl_roles_policies.permission_id', $permissions)
                    ->delete();
            return $res;
        } catch (\Exception $ex) {
            throw new AclQueryException('deleteResourcesPermissionsFromRoles. ' . $ex->getMessage());
        }
    }

    public function addUsersToRoles($users, $roles, $main) {
        try {
            $array = AclQueryUtils::arraysRolesPermutations($users, $roles, $main);
            $res = \DB::table('acl_users_roles')->insert($array);
            return $res;
        } catch (\Exception $ex) {
            throw new AclQueryException('addUsersToRoles. ' . $ex->getMessage());
        }
    }

}
