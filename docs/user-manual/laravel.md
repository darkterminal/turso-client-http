---
title: Laravel
description: Use turso-client-http in Laravel with the auto-discovered service provider and facade.
---

# Laravel

Turso Client HTTP ships with a Laravel service provider and facade.

## Install

```bash
composer require darkterminal/turso-client-http
```

Laravel package discovery registers `Darkterminal\TursoHttp\Laravel\TursoServiceProvider`.

## Configure

Set environment variables:

```dotenv
TURSO_DATABASE_URL=libsql://your-db-your-org.turso.io
TURSO_AUTH_TOKEN=your-token
```

Publish config if you need to customize it:

```bash
php artisan vendor:publish --provider="Darkterminal\\TursoHttp\\Laravel\\TursoServiceProvider"
```

## Use the binding

```php
use Darkterminal\TursoHttp\LibSQL;

class UserController
{
    public function index(LibSQL $db)
    {
        return $db->query('SELECT * FROM users')->fetchArray(LibSQL::LIBSQL_ASSOC);
    }
}
```

## Facade

```php
use Darkterminal\TursoHttp\Laravel\Turso;

$rows = Turso::query('SELECT 1')->fetchArray();
```

## Notes

- The package is an HTTP client, not Laravel's default SQL driver. Use it as a client/service, or through the query builder layer it provides.
- For Eloquent/Doctrine/native-driver integrations, evaluate the dedicated packages in the Turso PHP ecosystem. This package's job is portable HTTP access.