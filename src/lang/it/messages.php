<?php

/**
 * @package   thinkingmik/acl-manager-laravel
 * @author    Michele Andreoli <michi.andreoli[at]gmail.com>
 * @copyright Copyright (c) Michele Andreoli
 * @license   http://mit-license.org/
 * @link      https://github.com/thinkingmik/acl-manager-laravel
 */

return array(
	'acl_query_error' => 'Errore durante l\'interrogazione alla base dati: :error',
	'acl_invalid_policy' => 'Non puoi accedere alla risorsa. Controlla se l\'utente possiede il permesso <b>:name</b>.',
	'invalid_callback' => 'ACL callback non impostata correttamente nel file di configurazione.',
	'invalid_token_param' => 'Parametro per il token non impostato correttamente nel file di configurazione.',
	'userid_not_found' => 'Impossibile verificare i permessi. ID utente non trovato in sessione',
	'token_not_found' => 'o dal token.'
);