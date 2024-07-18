<?php

/**
 * THIS IS POOR TESTING, IF YOU HAVE KNOWLEDGE ABOUT UNIT TESTING
 * PULL REQUEST ARE VERY WELCOME!
 */

use Darkterminal\TursoHttp\LibSQL;

require_once getcwd() . '/vendor/autoload.php';

$dbname = getenv('DB_URL');
$authToken = getenv('DB_TOKEN');
$db = new LibSQL("dbname=$dbname&authToken=$authToken");

$createContact = $db->execute("INSERT INTO contacts (name, email, phone) VALUES (?, ?, ?)", [
    'Imam Ali Mustofa',
    'darkterminal@duck.com',
    '08123456789'
]);
var_dump($createContact);
