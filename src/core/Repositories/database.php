<?php

function databaseRepository($action): array
{
    $items = [
        'list' => [
            'method' => 'GET',
            'url' => platform_api_url('/organizations/{organizationName}/databases')
        ],
        'create' => [
            'method' => 'POST',
            'url' => platform_api_url('/organizations/{organizationName}/databases')
        ],
        'retrieve' => [
            'method' => 'GET',
            'url' => platform_api_url('/organizations/{organizationName}/databases/{databaseName}')
        ],
        'retrieve_configuration' => [
            'method' => 'GET',
            'url' => platform_api_url('/organizations/{organizationName}/databases/{databaseName}/configuration')
        ],
        'update_configuration' => [
            'method' => 'PATCH',
            'url' => platform_api_url('/organizations/{organizationName}/databases/{databaseName}/configuration')
        ],
        'usage' => [
            'method' => 'GET',
            'url' => platform_api_url('/organizations/{organizationName}/databases/{databaseName}/usage')
        ],
        'stats' => [
            'method' => 'GET',
            'url' => platform_api_url('/organizations/{organizationName}/databases/{databaseName}/stats')
        ],
        'delete' => [
            'method' => 'DELETE',
            'url' => platform_api_url('/organizations/{organizationName}/databases/{databaseName}')
        ],
        'list_instances' => [
            'method' => 'GET',
            'url' => platform_api_url('/organizations/{organizationName}/databases/{databaseName}/instances')
        ],
        'retrieve_instance' => [
            'method' => 'GET',
            'url' => platform_api_url('/organizations/{organizationName}/databases/{databaseName}/instances/{instanceName}')
        ],
        'create_token' => [
            'method' => 'POST',
            'url' => platform_api_url('/organizations/{organizationName}/databases/{databaseName}/auth/tokens')
        ],
        'invalidate_tokens' => [
            'method' => 'POST',
            'url' => platform_api_url('/organizations/{organizationName}/databases/{databaseName}/auth/rotate')
        ],
        'upload_dump' => [
            'method' => 'POST',
            'url' => platform_api_url('/organizations/{organizationName}/databases/dumps')
        ],
    ];

    return $items[$action] ?? [];
}
