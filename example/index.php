<?php

use Darkterminal\TursoHTTP;

require_once '../src/TursoHTTP.php';

$databaseName       = "database-name";
$organizationName   = "organization-name";
$token              = "your-turso-database-token";

$tursoAPI = new TursoHTTP($dabaseBane, $organizationName, $token);

$queryResponse = $tursoAPI
    ->addRequest("execute", "CREATE TABLE users (userID TEXT NOT NULL PRIMARY KEY, firstName TEXT NOT NULL, lastName TEXT NOT NULL, email TEXT NOT NULL UNIQUE)")
    ->addRequest("close")
    ->queryDatabase()
    ->toJSON();

echo $queryResponse;
