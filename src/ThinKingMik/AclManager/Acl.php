<?php

/**
 * @package   thinkingmik/acl-manager-laravel
 * @author    Michele Andreoli <michi.andreoli[at]gmail.com>
 * @copyright Copyright (c) Michele Andreoli
 * @license   http://mit-license.org/
 * @link      https://github.com/thinkingmik/acl-manager-laravel
 */

namespace ThinKingMik\AclManager;

use ThinKingMik\AclManager\Queries\AclSelectQuery;
use ThinKingMik\AclManager\Queries\AclAlterQuery;
use ThinKingMik\AclManager\Queries\AclQueryUtils;

class Acl {

    /**
     * The query object
     * @var AclSelectQuery
     */
    private $selector;

    /**
     * The query object
     * @var AclAlterQuery
     */
    private $alters;

    /**
     * @param string $table
     */
    public function __construct($table) {
        $this->selector = new AclSelectQuery($table);
        $this->alters = new AclAlterQuery($table);
    }

    public function isRouteAllowed($userId, $policy) {
        $policies = AclQueryUtils::getPoliciesFromFilter($policy);
        $query = $this->selector->selectAllByUserId($userId);

        for ($i = 0; $i < count($query); $i++) {
            $record = $query[$i];
            $role = trim($record->role);
            $resource = trim($record->resource);
            $permission = trim($record->permission);

            if (array_key_exists('*', $policies) || array_key_exists($role, $policies)) {
                if (array_key_exists('*', $policies)) {
                    $role = '*';
                }
                if (array_key_exists('*', $policies[$role])) {
                    return true;
                } else if (array_key_exists($resource, $policies[$role])) {
                    if (array_key_exists('*', $policies[$role][$resource]) || array_key_exists($permission, $policies[$role][$resource])) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function isAllowed($users, $resources, $permissions) {
        $users = AclQueryUtils::getIdsFromObjects($users);
        $resources = AclQueryUtils::getIdsFromObjects($resources);
        $permissions = AclQueryUtils::getIdsFromObjects($permissions);

        $query = $this->selector->selectResourcesByUsersId($users, $resources, $permissions);
        $result = count($query);

        if ($result > 0) {
            return true;
        }

        return false;
    }

    public function areAnyRolesAllowed($roles, $resources, $permissions) {
        $roles = AclQueryUtils::getIdsFromObjects($roles);
        $resources = AclQueryUtils::getIdsFromObjects($resources);
        $permissions = AclQueryUtils::getIdsFromObjects($permissions);

        $query = $this->selector->selectResourcesByRoles($roles, $resources, $permissions);
        $result = count($query);

        if ($result > 0) {
            return true;
        }

        return false;
    }

    public function hasRole($users, $roles) {
        $users = AclQueryUtils::getIdsFromObjects($users);
        $roles = AclQueryUtils::getIdsFromObjects($roles);

        $query = $this->selector->selectRolesByUsersId($users, $roles);
        $result = count($query);

        if ($result > 0) {
            return true;
        }

        return false;
    }

    public function allowUsers($users, $resources, $permissions, $expires = null) {
        $users = AclQueryUtils::getIdsFromObjects($users);
        $resources = AclQueryUtils::getIdsFromObjects($resources);
        $permissions = AclQueryUtils::getIdsFromObjects($permissions);

        return $this->alters->addResourcesPermissionsToUsers($users, $resources, $permissions, $expires);
    }

    public function allowRoles($roles, $resources, $permissions, $expires = null) {
        $roles = AclQueryUtils::getIdsFromObjects($roles);
        $resources = AclQueryUtils::getIdsFromObjects($resources);
        $permissions = AclQueryUtils::getIdsFromObjects($permissions);

        return $this->alters->addResourcesPermissionsToRoles($roles, $resources, $permissions, $expires);
    }

    public function denyUsers($users, $resources, $permissions) {
        $users = AclQueryUtils::getIdsFromObjects($users);
        $resources = AclQueryUtils::getIdsFromObjects($resources);
        $permissions = AclQueryUtils::getIdsFromObjects($permissions);

        return $this->alters->deleteResourcesPermissionsFromUsers($users, $resources, $permissions);
    }

    public function denyRoles($roles, $resources, $permissions) {
        $roles = AclQueryUtils::getIdsFromObjects($roles);
        $resources = AclQueryUtils::getIdsFromObjects($resources);
        $permissions = AclQueryUtils::getIdsFromObjects($permissions);

        return $this->alters->deleteResourcesPermissionsFromRoles($roles, $resources, $permissions);
    }

    public function addUsersRoles($users, $roles, $main = 'N') {
        $users = AclQueryUtils::getIdsFromObjects($users);
        $roles = AclQueryUtils::getIdsFromObjects($roles);

        return $this->alters->addUsersToRoles($users, $roles, $main);
    }

}
