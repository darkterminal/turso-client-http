---
title: Local / sqld
description: Connect turso-client-http to a local or self-hosted libsql-server (sqld).
---

# Local / sqld

For local development and self-hosted deployments, run a libSQL server (`sqld` / libsql-server) and point the client at its HTTP URL.

## Start sqld

Use your preferred sqld/libsql-server setup. The client expects an HTTP endpoint, typically:

```text
http://127.0.0.1:8081
```

## Connect

```php
use Darkterminal\TursoHttp\LibSQL;

$db = new LibSQL('http://127.0.0.1:8081');
```

If your local server requires auth, pass a token:

```php
$db = new LibSQL('http://127.0.0.1:8081', [
    'authToken' => getenv('DB_TOKEN'),
]);
```

## Version check

Self-hosted sqld exposes `/version`, so this works:

```php
echo $db->version();
// sqld 0.24.x (...)
```

Turso Cloud may not expose `/version`; do not use it as a portable health check.

## Local dev detection

The package treats URLs containing `127.0.0.1`, `localhost`, or `http://` as local-dev style URLs. This affects how DSN parsing and scheme normalization behave. For production, prefer explicit HTTPS URLs and auth tokens.