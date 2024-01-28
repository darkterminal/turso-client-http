<?php

$baseURL = 'https://api.turso.tech/v1';

return [
    'tokens' => require_once 'tokens.php',
    'databases' => require_once 'database.php',
    'groups' => require_once 'groups.php',
    'locations' => require_once 'locations.php',
    'organizations' => require_once 'organizations.php',
    'audit_logs' => require_once 'audit_logs.php'
];
