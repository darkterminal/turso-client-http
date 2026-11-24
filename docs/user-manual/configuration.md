---
title: Configuration
description: Configure turso-client-http with DSN strings, constructor options, environment variables, and Laravel config.
---

# Configuration

## Constructor

```php
use Darkterminal\TursoHttp\LibSQL;

$db = new LibSQL(string $dsn, array $options = []);
```

### DSN / URL

The first argument is the database URL. Accepted forms:

| Form | Example | Notes |
|---|---|---|
| Turso Cloud | `libsql://db-org.turso.io` | Converted to `https://...` internally |
| Local HTTP | `http://127.0.0.1:8081` | Used as-is; local-dev detection applies |
| Local HTTPS | `https://my-sqld.example.com` | Used as-is |

For native-client-compatible DSN style, the package also supports `dbname=<url>&authToken=<token>` through `Utils::parseDsn()`; the plain URL + options array is the clearer form for new code.

### Options

```php
$db = new LibSQL($url, [
    'authToken' => '...',
    'before_hook' => function ($request) { /* inspect/modify */ },
    'after_hook' => function ($response) { /* inspect */ },
]);
```

| Option | Type | Purpose |
|---|---|---|
| `authToken` | string | Bearer token sent to Hrana endpoint. Required for Turso Cloud. |
| `before_hook` | callable | Called before request execution. |
| `after_hook` | callable | Called after request execution. |

## Environment variables

Global helpers and some builder behaviors read environment variables:

| Variable | Fallback | Purpose | Default |
|---|---|---|---|
| `DB_URL` | `TURSO_URL` | libSQL HTTP endpoint | `http://127.0.0.1:8081` |
| `DB_TOKEN` | `TURSO_TOKEN` | Auth token | public local-test fixture |
| `DB_TIMEZONE` | — | Timezone for `DATETIME` cast in result decoding | `UTC` |
| `DB_STRICT_QUERY` | — | When `true`, query builder explains and rejects expensive plans | `false` |
| `DB_LOG_DEBUG` | — | When `true`, logs Hrana pipeline payloads | off |
| `DB_LOG_NAME` | — | Log channel/file name | `libsql_debug` |
| `DB_LOG_PATH` | — | Log file path | `~/.turso-http/logs/<name>.log` |

> [!WARNING]
> The default token is a **public test fixture** for local seeded `sqld` containers. Never use it against Turso Cloud or any real deployment. Always set `DB_URL`/`TURSO_URL` and `DB_TOKEN`/`TURSO_AUTH_TOKEN` explicitly outside local tests.

## Laravel configuration

The package ships `Darkterminal\TursoHttp\Laravel\TursoServiceProvider`. It is auto-discovered by Laravel package discovery.

Publish config if needed:

```bash
php artisan vendor:publish --provider="Darkterminal\\TursoHttp\\Laravel\\TursoServiceProvider"
```

Configure environment variables as expected by the published config/service provider. See [Laravel](../laravel/).