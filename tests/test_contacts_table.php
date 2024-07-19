<?php

/**
 * THIS IS POOR TESTING, IF YOU HAVE KNOWLEDGE ABOUT UNIT TESTING
 * PULL REQUEST ARE VERY WELCOME!
 */

use Darkterminal\TursoHttp\LibSQL;

require_once getcwd() . '/vendor/autoload.php';

$dbname     = getenv('DB_URL');
$authToken  = getenv('DB_TOKEN');
$db         = new LibSQL("dbname=$dbname;authToken=$authToken");

$query = <<<SQL
INSERT INTO contacts (name, email, phone, address) VALUES (?, ?, ?, ?)
SQL;

$createContact = $db->execute($query, [
    'Imam Ali Mustofa',
    'darkterminal@duck.com',
    '08123456789',
    'Punk Univers'
]);

var_dump($createContact);
