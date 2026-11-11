> [!IMPORTANT]
> This package is a **pure-PHP HTTP client** (**Hrana over HTTP**) for libSQL — it is
> not the libSQL PHP extension, and it does not link against any native library. If you
> want the native extension, use the turso-driver for your framework instead; this one
> runs anywhere ext-curl works, including shared hosting and CI runners.

<p align="center">
  <a href="https://discord.gg/9eAaMnJtfY">
    <picture>
      <source media="(prefers-color-scheme: dark)" srcset="https://i.imgur.com/UhuW3zm.png">
      <source media="(prefers-color-scheme: light)" srcset="https://i.imgur.com/vljWbfr.png">
      <img alt="Shows a black logo in light color mode and a white one in dark color mode." src="https://i.imgur.com/vGCC0I4.png">
    </picture>
  </a>
</p>

<p align="center">
  <img alt="Turso + HTTP Client" src="https://i.imgur.com/URmAyKX.png">
</p>

<p align="center">
  <a href="LICENSE"><img src="https://img.shields.io/github/license/darkterminal/turso-client-http?color=F05340" alt="MIT License" /></a>
  <a href="https://tur.so/discord-php"><img src="https://img.shields.io/discord/933071162680958986?color=F05340" alt="Discord" /></a>
  <a href="#contributors"><img src="https://img.shields.io/github/contributors/darkterminal/turso-client-http?color=F05340" alt="Contributors" /></a>
  <a href="https://packagist.org/packages/darkterminal/turso-client-http"><img src="https://img.shields.io/packagist/dt/darkterminal/turso-client-http?color=F05340" alt="Total downloads" /></a>
  <a href="https://github.com/darkterminal/turso-client-http/tags"><img src="https://img.shields.io/github/v/tag/darkterminal/turso-client-http?label=version&color=F05340" alt="Version Tag" /></a>
  <a href="https://github.com/darkterminal/turso-client-http/actions/workflows/tests.yml"><img src="https://github.com/darkterminal/turso-client-http/actions/workflows/tests.yml/badge.svg" alt="Testing" /></a>
</p>

<p align="center">
  SQLite for Production. Powered by <a href="https://turso.tech/libsql">libSQL</a> and the <a href="https://github.com/tursodatabase/turso-client-php/">libSQL Database API</a>.
</p>

<p align="center">
  libSQL is an open-contribution fork of SQLite. This package provides a
  pure-PHP HTTP client (Hrana over HTTP) for libSQL — Turso Cloud and
  self-hosted libsql-server — with a Laravel-compatible service provider and
  facade. The <code>docker/laravel-proof/</code> harness and the test suite
  exercise transactions, the full Hrana pipeline, and the query builder end to end.
</p>

<p align="center">
  Full user manual & tutorial now lives on the
  <a href="https://github.com/darkterminal/turso-client-http/wiki">Wiki</a>.
</p>

---

## Requirements

| Component | Requirement |
|---|---|
| PHP | 8.3, 8.4 or 8.5 (CI-verified on all three) |
| ext-curl | required |
| ext-json | required |
| ext-mbstring | required |
| Turso Cloud or self-hosted libsql-server (`sqld`) | HTTP endpoint + token |
| Native libSQL extension | **not** required |

## Installation

```bash
composer require darkterminal/turso-client-http
```

## Configuration

Plain PHP — pass a DSN (token parameter kept for API symmetry with the native client):

```php
use Darkterminal\TursoHttp\LibSQL;

$db = new LibSQL("dbname=libsql://<db>-<org>.turso.io&authToken=<token>");
```

Fallbacks when no DSN argument is provided (used by the global helpers):
`DB_URL`/`TURSO_URL` (default `http://127.0.0.1:8081`, local sqld) and
`DB_TOKEN`/`TURSO_TOKEN`.

Laravel — after `composer require`, the auto-discovered
`TursoServiceProvider` shares one client; publish the config if you want to
change it:

```bash
php artisan vendor:publish --provider="Darkterminal\TursoHttp\Laravel\TursoServiceProvider" --tag=turso-http-config
```

`.env`:

```dotenv
DB_URL=libsql://your-url.turso.io
DB_TOKEN=your-token
```

```php
use Darkterminal\TursoHttp\Laravel\Turso;

$rows = Turso::query('SELECT * FROM users')->fetchArray(
    \Darkterminal\TursoHttp\LibSQL::LIBSQL_ASSOC
);
```

Everything else (timezone handling for DATETIME columns, `DB_STRICT_QUERY`,
`DB_TIMEZONE`) is on the
[Wiki · Configuration](https://github.com/darkterminal/turso-client-http/wiki/Configuration).

## Usage

```php
use Darkterminal\TursoHttp\LibSQL;

$db = new LibSQL("dbname=$DB_URL&authToken=$DB_TOKEN");

$db->execute('CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, name TEXT)');
$db->execute('INSERT INTO users (name) VALUES (?)', ['Ada']);

$rows = $db->query('SELECT * FROM users')->fetchArray(LibSQL::LIBSQL_ASSOC);
```

Transactions: `$tx = $db->transaction('deferred');` → `$tx->execute(...)` →
`$tx->commit();` / `$tx->rollback();`.

Full tour (fetch modes, transactions, query builder, `*Return` statements):
[Wiki · Basic Usage](https://github.com/darkterminal/turso-client-http/wiki/Basic-Usage).

## Documentation

The full user manual & tutorial lives on the **[Wiki](https://github.com/darkterminal/turso-client-http/wiki)**:

- [Installation](https://github.com/darkterminal/turso-client-http/wiki/Installation)
- [Configuration](https://github.com/darkterminal/turso-client-http/wiki/Configuration)
- [Basic Usage](https://github.com/darkterminal/turso-client-http/wiki/Basic-Usage)
- [Laravel Integration](https://github.com/darkterminal/turso-client-http/wiki/Laravel)
- [Query Builder](https://github.com/darkterminal/turso-client-http/wiki/Query-Builder)
- [Verification & Proof Harness](https://github.com/darkterminal/turso-client-http/wiki/Verification)
- [Known Limitations](https://github.com/darkterminal/turso-client-http/wiki/Known-Limitations)
- [Troubleshooting](https://github.com/darkterminal/turso-client-http/wiki/Troubleshooting)

## Verification

- Pest suite: **60 tests, 103 assertions, all green** — repeatedly re-seeded
  from a pristine database.
- Live **Turso Cloud** tests (`tests/Feature/TursoCloudConnectionTest.php`):
  **2 tests / 11 assertions** covering the typed round trip (Hrana v3 int /
  float / text / `NULL`) and transactions — against a real cloud database,
  env-gated on `TURSO_CLOUD_DB_URL` / `TURSO_CLOUD_DB_TOKEN` (skipped
  otherwise). Self-cleaning tables, safe to run repeatedly.
- CI: GitHub Actions matrix on **PHP 8.3 / 8.4 / 8.5** against a seeded
  libsql-server service container — all green.
- `docker/laravel-proof/`: fresh Laravel 12 app, package installed via path
  repository — **5/5 assertions green on all three PHP versions** (service
  provider discovery, facade resolution, connectivity, facade round trip,
  transaction commit).
- HTTP errors (`401` / `403` / `404`) surface as catchable
  `LibSQLError` exceptions with the server's message — no silent failures.

Links: [docker/laravel-proof](docker/laravel-proof/) · [CI workflow](.github/workflows/tests.yml) · [Releases](https://github.com/darkterminal/turso-client-http/releases)

## Testing

```bash
composer install
./vendor/bin/pest
```

The suite re-seeds a local `libsql-server` on `127.0.0.1:8081` itself (or
whatever `DB_URL` points at). Set `TURSO_HTTP_SKIP_SEED=1` to skip seeding.

The Turso Cloud tests run too when the live endpoint is configured (they
skip otherwise):

```dotenv
TURSO_CLOUD_DB_URL=libsql://<db>-<org>.turso.io
TURSO_CLOUD_DB_TOKEN=<token>
```

See [Wiki · Verification](https://github.com/darkterminal/turso-client-http/wiki/Verification).

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please do not open public issues for vulnerabilities. Report them privately via
[GitHub Security Advisories](https://github.com/darkterminal/turso-client-http/security/advisories/new).

## Contributors

![Contributors](https://contrib.nn.ci/api?no_bot=true&repo=darkterminal/turso-client-http)

## License

Licensed under the MIT License. See [LICENSE](LICENSE) for details.
