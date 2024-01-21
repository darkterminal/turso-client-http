<?php

use Darkterminal\DataType;
use Darkterminal\SadQuery;
use Darkterminal\TursoHTTP;

require_once getcwd() . '/src/SadQuery.php';
require_once getcwd() . '/src/TursoHTTP.php';

$databaseName       = "database-name";
$organizationName   = "organization-name";
$token              = "your-turso-database-token";

$tursoAPI = new TursoHTTP($databaseName, $organizationName, $token);
$query = new SadQuery();

$createTableUsers = $query->createTable('users')
    ->addColumn('userId', DataType::INTEGER, ['PRIMARY KEY', 'AUTOINCREMENT'])
    ->addColumn('name', DataType::TEXT)
    ->addColumn('email', DataType::TEXT)
    ->endCreateTable();

$createNewUser = $query->insert('users', ['name' => 'darkterminal', 'email' => 'darkterminal@duck.com'])->getQuery();

$tableCreated = $tursoAPI
    ->addRequest("execute", $createTableUsers)
    ->addRequest("close")
    ->queryDatabase()
    ->toJSON();
echo $tableCreated . PHP_EOL;

$userCreated = $tursoAPI
    ->addRequest("execute", $createNewUser)
    ->addRequest("close")
    ->queryDatabase()
    ->toJSON();
echo $userCreated . PHP_EOL;
