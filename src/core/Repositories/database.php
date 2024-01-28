<?php

return [
    'list'      => [
        'method'    => 'GET',
        'url'       => $baseURL . '/organizations/{organizationName}/databases'
    ],
    'create'    => [
        'method'    => 'POST',
        'url'       => $baseURL . '/organizations/{organizationName}/databases'
    ],
    'retrive'   => [
        'method'    => 'GET',
        'url'       => $baseURL . '/organizations/{organizationName}/databases/{databaseName}'
    ],
    'usage'   => [
        'method'    => 'GET',
        'url'       => $baseURL . '/organizations/{organizationName}/databases/{databaseName}/usage'
    ],
    'delete'   => [
        'method'    => 'DELETE',
        'url'       => $baseURL . '/organizations/{organizationName}/databases/{databaseName}'
    ],
    'list_instances'   => [
        'method'    => 'GET',
        'url'       => $baseURL . '/organizations/{organizationName}/databases/{databaseName}/instances'
    ],
    'get_instance'   => [
        'method'    => 'GET',
        'url'       => $baseURL . '/organizations/{organizationName}/databases/{databaseName}/instances/{instanceName}'
    ],
    'create_token'   => [
        'method'    => 'POST',
        'url'       => $baseURL . '/organizations/{organizationName}/databases/{databaseName}/auth/tokens'
    ],
    'invalidate_tokens'   => [
        'method'    => 'POST',
        'url'       => $baseURL . '/organizations/{organizationName}/databases/{databaseName}/auth/rotate'
    ],
    'upload_dump'   => [
        'method'    => 'POST',
        'url'       => $baseURL . '/organizations/{organizationName}/databases/dumps'
    ],
];
