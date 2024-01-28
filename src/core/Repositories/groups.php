<?php

return [
    'list'          => [
        'method'    => 'GET',
        'url'       => $baseURL . '/organizations/{organizationName}/groups'
    ],
    'create'        => [
        'method'    => 'POST',
        'url'       => $baseURL . '/organizations/{organizationName}/groups'
    ],
    'get_group'     => [
        'method'    => 'GET',
        'url'       => $baseURL . '/organizations/{organizationName}/groups/{groupName}'
    ],
    'delete'        => [
        'method'    => 'DELETE',
        'url'       => $baseURL . '/organizations/{organizationName}/groups/{groupName}'
    ],
    'transfer'      => [
        'method'    => 'POST',
        'url'       => $baseURL . '/organizations/{organizationName}/groups/{groupName}/transfer'
    ],
    'add_location'  => [
        'method'    => 'POST',
        'url'       => $baseURL . '/organizations/{organizationName}/groups/{groupName}/locations/{location}'
    ],
    'delete_location'   => [
        'method'    => 'DELETE',
        'url'       => $baseURL . '/organizations/{organizationName}/groups/{groupName}/locations/{location}'
    ],
    'update_version'=> [
        'method'    => 'POST',
        'url'       => $baseURL . '/organizations/{organizationName}/groups/{groupName}/update'
    ],
    'create_token'  => [
        'method'    => 'POST',
        'url'       => $baseURL . '/organizations/{organizationName}/groups/{groupName}/auth/tokens'
    ],
    'invalidete_tokens'    => [
        'method'    => 'POST',
        'url'       => $baseURL . '/organizations/{organizationName}/groups/{groupName}/auth/rotate'
    ]
];
