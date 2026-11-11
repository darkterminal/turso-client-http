<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Turso HTTP database connection
    |--------------------------------------------------------------------------
    |
    | Mirror the shape of getenv()/$_ENV fallbacks used by the plain-PHP
    | client: DB_URL / DB_TOKEN (or TURSO_URL / TURSO_TOKEN).
    */

    'url' => env('DB_URL', env('TURSO_URL', 'http://127.0.0.1:8081')),

    'token' => env('DB_TOKEN', env('TURSO_TOKEN', '')),

    /*
    | Optional client options (e.g. before_hook / after_hook closures).
    | Example:
    |   'options' => [
    |       'before_hook' => fn () => logger()->debug('libsql call'),
    |       'after_hook' => fn () => null,
    |   ],
    */
    'options' => [],
];
