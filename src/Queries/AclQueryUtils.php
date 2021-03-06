<?php

/**
 * @package   thinkingmik/acl-manager-laravel
 * @author    Michele Andreoli <michi.andreoli[at]gmail.com>
 * @copyright Copyright (c) Michele Andreoli
 * @license   http://mit-license.org/
 * @link      https://github.com/thinkingmik/acl-manager-laravel
 */

namespace ThinKingMik\AclManager\Queries;

use ThinKingMik\AclManager\Exceptions\AclServerErrorException;

class AclQueryUtils {

    public static function getArray($item) {
        try {
            if (!is_array($item)) {
                $item = array($item);
            }
            return $item;
        } catch (\Exception $ex) {
            throw new AclServerErrorException($ex->getMessage());
        }
    }

    public static function getIdsFromObjects($objects, $idProperty = 'id') {
        try {
            $objects = self::getArray($objects);
            $ids = array();
            $max = count($objects);
            for ($i = 0; $i < $max; $i++) {
                if (gettype($objects[$i]) === 'object') {
                    array_push($ids, $objects[$i]->$idProperty);
                } else {
                    array_push($ids, $objects[$i]);
                }
            }
            return $ids;
        } catch (\Exception $ex) {
            throw new AclServerErrorException($ex->getMessage());
        }
    }

    public static function arraysPolicyPermutations($subjectField, $subjects, $resources, $permissions, $expires) {
        try {
            $array = array();
            $date = (new \DateTime())->format('Y-m-d H:i:s');
            $maxA = count($subjects);
            for ($i = 0; $i < $maxA; $i++) {
                $maxB = count($resources);
                for ($k = 0; $k < $maxB; $k++) {
                    $maxC = count($permissions);
                    for ($j = 0; $j < $maxC; $j++) {
                        array_push($array, array(
                            $subjectField => $subjects[$i],
                            'resource_id' => $resources[$k],
                            'permission_id' => $permissions[$j],
                            'expiration' => $expires,
                            'created_at' => $date,
                            'updated_at' => $date
                        ));
                    }
                }
            }
            return $array;
        } catch (\Exception $ex) {
            throw new AclServerErrorException($ex->getMessage());
        }
    }

    public static function arraysRolesPermutations($users, $roles, $main) {
        try {
            $array = array();
            $date = (new \DateTime())->format('Y-m-d H:i:s');
            $maxA = count($users);
            for ($i = 0; $i < $maxA; $i++) {
                $maxB = count($roles);
                for ($k = 0; $k < $maxB; $k++) {
                    array_push($array, array(
                        'user_id' => $users[$i],
                        'role_id' => $roles[$k],
                        'main' => $main,
                        'created_at' => $date,
                        'updated_at' => $date
                    ));
                }
            }
            return $array;
        } catch (\Exception $ex) {
            throw new AclServerErrorException($ex->getMessage());
        }
    }

    public static function getPoliciesFromFilter($policy) {
        try {
            $list = self::convertString2Array($policy, ';');
            $policies = array();
            $max = count($list);
            for ($i = 0; $i < $max; $i++) {
                $policy = $list[$i];
                $split = explode('.', $policy);

                $role = self::validatePolicy($split, 0);
                $resource = self::validatePolicy($split, 1);
                $permission = self::validatePolicy($split, 2);

                if (!array_key_exists($role, $policies)) {
                    $policies[$role] = array();
                }

                if (!array_key_exists($resource, $policies[$role])) {
                    $policies[$role][$resource] = array();
                }

                if (!array_key_exists($permission, $policies[$role][$resource])) {
                    $policies[$role][$resource][$permission] = true;
                }
            }
            return $policies;
        } catch (\Exception $ex) {
            throw new AclServerErrorException($ex->getMessage());
        }
    }

    private static function validatePolicy($part, $index) {
        try {
            if (array_key_exists($index, $part)) {
                return trim($part[$index]);
            }
            return null;
        } catch (\Exception $ex) {
            throw new AclServerErrorException($ex->getMessage());
        }
    }

    private static function convertString2Array($text, $separator) {
        try {
            $ret = array();
            $split = explode($separator, $text);
            $max = count($split);
            for ($i = 0; $i < $max; $i++) {
                if (trim($split[$i]) !== '') {
                    $ret[$i] = trim($split[$i]);
                }
            }
            return $ret;
        } catch (\Exception $ex) {
            throw new AclServerErrorException($ex->getMessage());
        }
    }

}
