---
title: Remote / Turso Cloud
description: Connect turso-client-http to Turso Cloud over Hrana HTTP.
---

# Remote / Turso Cloud

Turso Cloud exposes libSQL databases over HTTPS. Use the `libsql://` database URL and a database auth token.

## Create database and token

Using the Turso CLI:

```bash
turso db create my-db
turso db show my-db --url
turso db tokens create my-db
```

## Connect

```php
use Darkterminal\TursoHttp\LibSQL;

$db = new LibSQL(
    'libsql://my-db-my-org.turso.io',
    ['authToken' => getenv('TURSO_AUTH_TOKEN')]
);
```

The client converts `libsql://` to `https://` for HTTP transport.

## Query

```php
$rows = $db->query('SELECT sqlite_version() AS v')
    ->fetchArray(LibSQL::LIBSQL_ASSOC);
```

## Cloud note: `/version`

`LibSQL::version()` calls `GET /version`. This endpoint is available on self-hosted sqld, but Turso Cloud can return `404 route not found`. Handle it as optional diagnostics:

```php
try {
    $version = $db->version();
} catch (\Darkterminal\TursoHttp\core\LibSQLError $e) {
    $version = 'unavailable on this endpoint';
}
```

For a portable connectivity check, run `SELECT 1`.