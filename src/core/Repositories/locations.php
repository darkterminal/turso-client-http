<?php

function locationRepository($action): array
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
