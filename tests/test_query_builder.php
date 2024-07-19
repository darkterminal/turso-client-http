<?php

/**
 * THIS IS POOR TESTING, IF YOU HAVE KNOWLEDGE ABOUT UNIT TESTING
 * PULL REQUEST ARE VERY WELCOME!
 */

use Darkterminal\TursoHttp\LibSQL;
use Darkterminal\TursoHttp\sadness\LibSQLQueryBuilder;

require_once getcwd() . '/vendor/autoload.php';

$dbname = getenv('DB_URL');
$authToken = getenv('DB_TOKEN');
$db = new LibSQL("dbname=$dbname;authToken=$authToken");

$sql = new LibSQLQueryBuilder($db);

$contacts = $sql->table('contacts')
    ->where('address', '=', 'Punk Universe')
    ->get();

var_dump($contacts);

// $contacts = $sql->get('contacts');
// var_dump($contacts);
