<?php

namespace Darkterminal\TursoHttp\core\Repositories;


class GroupRepository
{
    public static function endpoints($action): array
    {
        $items = [
            'list' => [
                'method' => 'GET',
                'url' => platform_api_url('/organizations/{organizationName}/groups')
            ],
            'create' => [
                'method' => 'POST',
                'url' => platform_api_url('/organizations/{organizationName}/groups')
            ],
            'get_group' => [
                'method' => 'GET',
                'url' => platform_api_url('/organizations/{organizationName}/groups/{groupName}')
            ],
            'delete' => [
                'method' => 'DELETE',
                'url' => platform_api_url('/organizations/{organizationName}/groups/{groupName}')
            ],
            'transfer' => [
                'method' => 'POST',
                'url' => platform_api_url('/organizations/{organizationName}/groups/{groupName}/transfer')
            ],
            'add_location' => [
                'method' => 'POST',
                'url' => platform_api_url('/organizations/{organizationName}/groups/{groupName}/locations/{location}')
            ],
            'delete_location' => [
                'method' => 'DELETE',
                'url' => platform_api_url('/organizations/{organizationName}/groups/{groupName}/locations/{location}')
            ],
            'unarchive' => [
                'method' => 'POST',
                'url' => 'https://api.turso.tech/v1/organizations/{organizationName}/groups/{groupName}/unarchive'
            ],
            'update_version' => [
                'method' => 'POST',
                'url' => platform_api_url('/organizations/{organizationName}/groups/{groupName}/update')
            ],
            'create_token' => [
                'method' => 'POST',
                'url' => platform_api_url('/organizations/{organizationName}/groups/{groupName}/auth/tokens')
            ],
            'invalidete_tokens' => [
                'method' => 'POST',
                'url' => platform_api_url('/organizations/{organizationName}/groups/{groupName}/auth/rotate')
            ]
        ];

        return $items[$action] ?? [];
    }
}
