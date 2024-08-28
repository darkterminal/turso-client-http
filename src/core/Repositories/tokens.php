<?php

function tokenRepository($action): array
{
    $items = [
        'list' => [
            'method' => 'GET',
            'url' => platform_api_url('/auth/api-tokens')
        ],
        'create' => [
            'method' => 'POST',
            'url' => platform_api_url('/auth/api-tokens/{tokenName}')
        ],
        'validate' => [
            'method' => 'GET',
            'url' => platform_api_url('/auth/validate')
        ],
        'revoke' => [
            'method' => 'DELETE',
            'url' => platform_api_url('/auth/api-tokens/{tokenName}')
        ]
    ];

    return $items[$action] ?? [];
}
