---
title: Getting Started
description: Install turso-client-http and run your first Hrana-over-HTTP query.
---

# Getting Started

## Requirements

| Component | Requirement |
|---|---|
| PHP | 8.3, 8.4, or 8.5 |
| ext-curl | required |
| ext-json | required |
| ext-mbstring | required |
| libSQL endpoint | Turso Cloud or self-hosted `sqld`/libsql-server |

No native libSQL extension is required.

## Install

```bash
composer require darkterminal/turso-client-http
```

## Connect

The `LibSQL` constructor takes a database URL and an optional options array. For Turso Cloud, pass the `libsql://` URL and an auth token:

```php
<?php

require 'vendor/autoload.php';

use Darkterminal\TursoHttp\LibSQL;

$db = new LibSQL(
    'libsql://your-db-your-org.turso.io',
    ['authToken' => getenv('TURSO_AUTH_TOKEN')]
);

echo $db->version(), PHP_EOL;
```

For a local `sqld`, use `http://127.0.0.1:8081` (or your server URL). Auth token can be omitted if your local server does not require it.

```php
$db = new LibSQL('http://127.0.0.1:8081');
```

> [!NOTE]
> `version()` calls the `/version` endpoint. Self-hosted sqld supports it; Turso Cloud may return HTTP 404 for `/version`. For a portable connectivity check, use `SELECT 1` through `query()`.

## Create a table and write data

```php
$db->executeBatch("
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT UNIQUE
    );
    INSERT INTO users (name, email) VALUES ('Ada', 'ada@example.com');
");
```

## Query data

```php
$result = $db->query('SELECT id, name, email FROM users WHERE id = ?', [1]);
$rows = $result->fetchArray(LibSQL::LIBSQL_ASSOC);

print_r($rows);
```

## Use helpers (optional)

The package ships global helpers that read connection details from environment variables:

```php
use function Darkterminal\TursoHttp\core\useDB;

$db = useDB(); // reads TURSO_DB_URL / TURSO_DB_TOKEN
```

For explicit configuration, prefer constructing `LibSQL` directly. See [Configuration](../configuration/).