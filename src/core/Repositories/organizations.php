<?php

return [
    'list' => [
        'method'    => 'GET',
        'url'       => $baseURL . '/organizations'
    ],
    'update' => [
        'method'    => 'PATCH',
        'url'       => $baseURL . '/organizations/{organizationName}'
    ],
    'members' => [
        'method'    => 'GET',
        'url'       => $baseURL . '/organizations/{organizationName}/members'
    ],
    'add_member' => [
        'method'    => 'POST',
        'url'       => $baseURL . '/organizations/{organizationName}/members'
    ],
    'remove_member' => [
        'method'    => 'DELETE',
        'url'       => $baseURL . '/organizations/{organizationName}/members/{username}'
    ],
    'invite_lists' => [
        'method'    => 'GET',
        'url'       => $baseURL . '/organizations/{organizationName}/invites'
    ],
    'create_invite' => [
        'method'    => 'POST',
        'url'       => $baseURL . '/organizations/{organizationName}/invites'
    ]
];
