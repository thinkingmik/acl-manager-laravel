PHP ACL Manager for Laravel 5
================

[![Latest Version](http://img.shields.io/github/release/thinkingmik/acl-manager-laravel.svg?style=flat-square)](https://packagist.org/packages/thinkingmik/acl-manager-laravel)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/thinkingmik/acl-manager-laravel/master.svg?style=flat-square)](https://travis-ci.org/thinkingmik/acl-manager-laravel)
[![Code Quality](https://img.shields.io/scrutinizer/g/thinkingmik/acl-manager-laravel.svg?style=flat-square)](https://scrutinizer-ci.com/g/thinkingmik/acl-manager-laravel/?branch=master)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/thinkingmik/acl-manager-laravel.svg?style=flat-square)](https://scrutinizer-ci.com/g/thinkingmik/acl-manager-laravel/code-structure)
[![Total Downloads](https://img.shields.io/packagist/dt/thinkingmik/acl-manager-laravel.svg?style=flat-square)](https://packagist.org/packages/thinkingmik/acl-manager-laravel)

## Summary

- [Introduction](#introduction)
- [Installation](#installation)
- [Setup](#setup)
- [Migrations and configuration file](#deploy)
  - [Migrations](#migrations)
  - [Configuration](#configuration)
- [Usage](#usage)
  - [Routing](#routing)
  - [Facade](#facade)
- [License](#license)

## Introduction

Adds ACL to Laravel 5.
This ACL solution for Laravel 5 is useful when you need to store policy rules or users' roles into a database.
ACL Manager are composed by three entities:

1. Roles
2. Resources
3. Permissions

## Installation

Add the following line to the `require` section of `composer.json`:

```json
{
    "require": {
        "thinkingmik/acl-manager-laravel": "2.*"
    }
}
```
## Setup

1. Add `'ThinKingMik\AclManager\AclManagerServiceProvider',` into the service **provider** list in `config/app.php`.
2. Add `'Acl' => 'ThinKingMik\AclManager\Facades\AclManagerFacade',` into the list of **aliases** in `config/app.php`.
3. Add `'ThinKingMik\AclManager\Middleware\AclManagerExceptionHandler',` into the list of **middlewere** in `app/Http/Kernel.php`.

## Migration and configuration file<a name="deploy"></a>

In order to use the Acl manager publish its configuration and migrations first

```
php artisan vendor:publish
```

> **Note:** migrations are only published, remember to run them when ready.

### Migrations

This package comes with all the migrations you need to run a full featured Acl manager.
First of all you need to check the reference to the user table name:

* Check the users table name of your Laravel installation in `config/auth.php` at the section:
```php
/*
 |--------------------------------------------------------------------------
 | Authentication Table
 |--------------------------------------------------------------------------
 |
 | When using the "Database" authentication driver, we need to know which
 | table should be used to retrieve your users. We have chosen a basic
 | default value but you may easily change it to any table you like.
 |
 */

'table' => 'system_users',
```
* Change the table reference for `user_id` foreign key in these files `database/migrations`: 
```    
xxx_000006_create_acl_users_roles_table.php
xxx_000005_create_acl_users_policies_table.php

```
* Check `user_id` foreign key in all the above files to be sure that the user table name is the same as `'table' => 'system_users'`:
```php
$table->foreign('user_id')->references('id')->on('system_users')
```
* Now you can run:
```
php artisan migrate
```

### Configuration

Edit the file ```config/acl.php``` to suit your needs.

## Usage

### Routing

You can use Acl manager in routes as a filter
```php
Route::get('/private', array('before' => 'auth|acl:admin.*', 'uses' => function() {

}));
```

All checks are made on `user_id` attribute retrieved from session.
You can define many different `acl:` filter:
```php
acl:role.resource.permission //check if logged user has role and the permission on resource
acl:*.resource.permission    //check if logged user has permission on resource
acl:role.*                   //check if logged user has role
acl:role.resource.*          //check if logged user has role and any permissions on resource
acl:*.resource.*             //check if logged user has any permissions on resource
```
You can also combine these filters with `;` separator like:
```php
acl:admin.*;guest.*
```

### Facade

The `AclManager` is available through the Facade `Acl` or through the acl service in the IOC container.
The methods available are:
```php
/**
 * Check if user ID has a specified policy/policies
 * @param integer $user User ID
 * @param string $policies The policies used in routing
 * return boolean
 **/
Acl::isRouteAllowed(1, 'admin.*;guest.*');

/**
 * Check if user has permission on resource
 * @param array $users Array of user objects or array of user IDs
 * @param array $resources Array of resource objects or array of resource IDs
 * @param array $permissions Array of permission objects or array of permission IDs
 * return boolean
 **/
Acl::isAllowed(array(1), array('post', 'dashboard'), array('edit', 'view'));

/**
 * Check if role has permission on resource
 * @param array $roles Array of role objects or array of role IDs
 * @param array $resources Array of resource objects or array of resource IDs
 * @param array $permissions Array of permission objects or array of permission IDs
 * return boolean
 **/
Acl::areAnyRolesAllowed(array(1), array('post', 'dashboard'), array('edit', 'view'));

/**
 * Check if user has roles
 * @param array $users Array of user objects or array of user IDs
 * @param array $roles Array of role objects or array of role IDs
 * return boolean
 **/
Acl::hasRole(array(1), array('1', '2'));

/**
 * Add permissions on resources for users specified
 * @param array $users Array of user objects or array of user IDs
 * @param array $resources Array of resource objects or array of resource IDs
 * @param array $permissions Array of permission objects or array of permission IDs
 * @param date [$expire] Optionally you can specify an expiration date for policies  
 * return boolean
 **/
Acl::allowUsers(array(1), array('post', 'dashboard'), array('edit', 'view'), '2099-11-01');

/**
 * Add permissions on resources for roles specified
 * @param array $roles Array of role objects or array of role IDs
 * @param array $resources Array of resource objects or array of resource IDs
 * @param array $permissions Array of permission objects or array of permission IDs
 * @param date [$expire] Optionally you can specify an expiration date for policies  
 * return boolean
 **/
Acl::allowRoles(array(1), array('post', 'dashboard'), array('edit', 'view'), '2099-11-01');

/**
 * Remove permissions on resources for users specified
 * @param array $users Array of user objects or array of user IDs
 * @param array $resources Array of resource objects or array of resource IDs
 * @param array $permissions Array of permission objects or array of permission IDs  
 * return integer The number of deleted policies
 **/
Acl::denyUsers(array(1), array('post', 'dashboard'), array('edit', 'view'));

/**
 * Remove permissions on resources for roles specified
 * @param array $roles Array of role objects or array of role IDs
 * @param array $resources Array of resource objects or array of resource IDs
 * @param array $permissions Array of permission objects or array of permission IDs
 * return integer The number of deleted policies
 **/
Acl::denyRoles(array(1), array('post', 'dashboard'), array('edit', 'view'));

/**
 * Add roles to users
 * @param array $users Array of user objects or array of user IDs
 * @param array $roles Array of role objects or array of role IDs
 * @param char [$main] Optionally you can specify the main role  
 * return boolean
 **/
Acl::addUsersRoles(array(1), array('1', '2'), 'Y');
```

## License

This package is released under the MIT License.