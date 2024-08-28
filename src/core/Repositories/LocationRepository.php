<?php

namespace Darkterminal\TursoHttp\core\Repositories;

class LocationRepository
{
    public static function endpoints($action): array
    {
        $items = [
            'list' => [
                'method' => 'GET',
                'url' => platform_api_url('/locations')
            ],
            'closest_region' => [
                'method' => 'GET',
                'url' => 'https://region.turso.io'
            ]
        ];

        return $items[$action] ?? [];
    }
}
