<?php

namespace Darkterminal\TursoHttp\core\Repositories;

class Endpoints
{
    public static function use($type, $action): array
    {
        $repositories = [
            'tokens' => TokenRepository::endpoints($action),
            'databases' => DatabaseRepository::endpoints($action),
            'groups' => GroupRepository::endpoints($action),
            'locations' => LocationRepository::endpoints($action),
            'organizations' => OrganizationRepository::endpoints($action),
            'members' => MemberRepository::endpoints($action),
            'invites' => InviteRepository::endpoints($action),
            'audit_logs' => AuditLogsRepository::endpoints()
        ];

        return $repositories[$type];
    }
}
