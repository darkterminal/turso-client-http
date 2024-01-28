<?php

return [
    'list'      => [
        'method'    => 'GET',
        'url'       => $baseURL . '/auth/api-tokens'
    ],
    'create'    => [
        'method'    => 'POST',
        'url'       => $baseURL . '/auth/api-tokens/{tokenName}'
    ],
    'validate'  => [
        'method'    => 'GET',
        'url'       => $baseURL . '/auth/validate'
    ],
    'revoke'    => [
        'method'    => 'DELETE',
        'url'       => $baseURL . '/auth/api-tokens/{tokenName}'
    ]
];
