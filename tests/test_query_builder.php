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
$db = new LibSQL("dbname=$dbname&authToken=$authToken");

$builder = new LibSQLQueryBuilder($db);

$users = $builder->table('users')->get();
var_dump($users);

$users = $builder->get('users');
var_dump($users);
