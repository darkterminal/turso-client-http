<?php

require_once getcwd() . '/src/core/Repositories/database.php';
require_once getcwd() . '/src/core/Repositories/groups.php';
require_once getcwd() . '/src/core/Repositories/organizations.php';
require_once getcwd() . '/src/core/Repositories/members.php';
require_once getcwd() . '/src/core/Repositories/invites.php';
require_once getcwd() . '/src/core/Repositories/tokens.php';
require_once getcwd() . '/src/core/Repositories/locations.php';
require_once getcwd() . '/src/core/Repositories/audit_logs.php';

function endpoints($type, $action): array
{
    $repositories = [
        'tokens' => tokenRepository($action),
        'databases' => databaseRepository($action),
        'groups' => groupsRepository($action),
        'locations' => locationRepository($action),
        'organizations' => organizationsRepository($action),
        'members' => membersRepository($action),
        'invites' => invitesRepository($action),
        'audit_logs' => auditLogsRepository()
    ];

    return $repositories[$type];
}
