<?php

/**
 * @package   thinkingmik/acl-manager-laravel
 * @author    Michele Andreoli <michi.andreoli[at]gmail.com>
 * @copyright Copyright (c) Michele Andreoli
 * @license   http://mit-license.org/
 * @link      https://github.com/thinkingmik/acl-manager-laravel
 */

return array(
	'acl_query_error' => 'An error occurred on a query to database: :error',
	'acl_invalid_policy' => 'The requested resource is not accessible. Check if user has <b>:name</b> policy.',
	'invalid_callback' => 'Null or non-callable ACL callback set in config file.',
	'invalid_token_param' => 'Null or empty ACL access token param name set.',
	'userid_not_found' => 'Unable to verify ACL policies. User ID not found in session',
	'token_not_found' => 'or from access token.'
);