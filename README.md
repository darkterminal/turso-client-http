![TursoHTTP](https://i.imgur.com/URmAyKX.png)

The `TursoHTTP` library is a pure-PHP wrapper for Turso's HTTP Database API
(**Hrana over HTTP**), mirroring the API of the native
[turso-client-php](https://github.com/tursodatabase/turso-client-php)
extension. It works without any PHP extension beyond `ext-curl` — it is a
good fit for shared hosting, CI runners, and anywhere the native `.so` can't
be installed.

## Requirements

| Component | Requirement |
|---|---|
| PHP | 8.3, 8.4, 8.5 (CI-verified on all three) |
| ext-curl | required |
| ext-json | required |
| ext-mbstring | required |
| Composer | recommended (auto-loading) |

## Installation

```bash
composer require darkterminal/turso-client-http
```

## Usage

DSN syntax (kept API-compatible with the native extension):

```php
use Darkterminal\TursoHttp\LibSQL;

$db = new LibSQL("dbname=libsql://<db>-<org>.turso.io&authToken=<token>");

$version = $db->version();
$result  = $db->query('SELECT count(*) AS c FROM artists');
```

### Plain PHP (no framework)

```php
use Darkterminal\TursoHttp\LibSQL;

$db = new LibSQL("dbname=libsql://your-url.turso.io&authToken=your-token");

$db->execute('CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, name TEXT)');
$rows = $db->query('SELECT * FROM users ORDER BY id')->fetchArray(LibSQL::LIBSQL_ASSOC);
```

### Laravel — service provider + facade

The package ships an auto-discovered `TursoServiceProvider` and a `Turso`
facade. After `composer require`, Laravel discovers the provider
automatically; the binding resolves to a shared `LibSQL` client.

```dotenv
DB_URL=libsql://your-url.turso.io
DB_TOKEN=your-token
```

```php
use Darkterminal\TursoHttp\Laravel\Turso;

// .env above feeds config/turso-http.php
$version = Turso::version();
Turso::execute('CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, name TEXT)');
$rows = Turso::query('SELECT * FROM users')->fetchArray(\Darkterminal\TursoHttp\LibSQL::LIBSQL_ASSOC);
```

Publish the config if you want to see/override defaults:

```bash
php artisan vendor:publish --provider="Darkterminal\TursoHttp\Laravel\TursoServiceProvider" --tag=turso-http-config
```

For a real end-to-end Laravel 12 app, see `docker/laravel-proof/` in this
repository (builds a fresh skeleton, installs the package via a path
repository, and asserts SP discovery / facade resolution / facade round trip
/ transactions).

### non-Laravel verification

Pest suite covers 60 tests against a local `libsql-server` on `127.0.0.1:8081` — set `DB_URL`/`DB_TOKEN` or rely on the local sqld (seeds automatically). CI runs the same matrix on PHP 8.3/8.4/8.5.

## Features

- [libSQL Native Extension](https://github.com/tursodatabase/turso-client-php)-like API (`LibSQL::query`/`execute`, `LibSQLResult::fetchArray`, transactions)
- Schema Builder and Query Builder (fluent API)
- Full pipeline v2 protocol over HTTP (works against Turso Cloud and self-hosted sqld)
- Timezone support for DATETIME columns
- PHP 8.3 / 8.4 / 8.5 verified in CI

## What is NOT supported

- Offline / sqld journaled-writes flavors — those need the native extension
  (`tutorial/docs` note: this client speaks HTTP; sqld offline mode requires
  native sync semantics)
- Vector casts in the Query Builder — write raw `vector(...)` SQL if you want
  vector search, exactly like with the native client

## Environment variables

| Variable | Default | Purpose |
|---|---|---|
| `DB_URL` or `TURSO_URL` | `http://127.0.0.1:8081` | sqld/HTTP endpoint |
| `DB_TOKEN` or `TURSO_TOKEN` | public test token | auth |
| `DB_TIMEZONE` | `UTC` | DATETIME conversion zone |
| `DB_STRICT_QUERY` | `false` | strict-query mode for `explain` |

## Testing

```bash
composer install
./vendor/bin/pest
```

The suite expects a local `libsql-server` on `127.0.0.1:8081` (or whatever
`DB_URL` points at). CI provisions a service container automatically.

## License

MIT — see [LICENSE](LICENSE).
