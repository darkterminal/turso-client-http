<?php

namespace Darkterminal\TursoHttp\core\Repositories;

class MemberRepository
{
    public static function endpoints($action): array
    {
        $items = [
            'members' => [
                'method' => 'GET',
                'url' => platform_api_url('/organizations/{organizationName}/members')
            ],
            'add_member' => [
                'method' => 'POST',
                'url' => platform_api_url('/organizations/{organizationName}/members')
            ],
            'remove_member' => [
                'method' => 'DELETE',
                'url' => platform_api_url('/organizations/{organizationName}/members/{username}')
            ],
        ];

        return $items[$action] ?? [];
    }
}
