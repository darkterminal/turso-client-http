---
title: Query Builder
description: Fluent query builder for turso-client-http.
---

# Query Builder

`LibSQLQueryBuilder` provides a fluent API on top of the same HTTP client. It is useful for application code that prefers composable queries over hand-written SQL strings.

## Create a builder

```php
use Darkterminal\TursoHttp\LibSQL;
use Darkterminal\TursoHttp\core\Builder\LibSQLQueryBuilder;

$db = new LibSQL($url, ['authToken' => $token]);
$builder = new LibSQLQueryBuilder($db);
```

Or use the `LibSQL::queryBuilder()` helper if available in your installed version; constructing the builder directly is always explicit.

## Select

```php
$rows = $builder->table('users')->get();
$rows = $builder->table('users')->select(['id', 'name'])->where('active', '=', 1)->get();
```

`get()` returns rows as associative arrays and resets builder state.

## Insert

```php
$idCount = $builder->table('users')->insert([
    'name' => 'Ada',
    'email' => 'ada@example.com',
]);
```

Use `insertBatch()` for multiple rows:

```php
$builder->table('users')->insertBatch([
    ['name' => 'Grace'],
    ['name' => 'Edsger'],
]);
```

## Update / delete

```php
$builder->table('users')->where('id', '=', 1)->update(['name' => 'Ada Lovelace']);
$builder->table('users')->where('id', '=', 1)->delete();
```

## Raw queries

```php
$rows = $builder->queryRaw('SELECT * FROM users WHERE id = ?', [1]);
$affected = $builder->executeRaw('UPDATE users SET name = ? WHERE id = ?', ['Ada', 1]);
```

## Strict query mode

Set `DB_STRICT_QUERY=true` to make the builder explain queries before execution and reject potentially expensive plans. This is a guardrail for development; evaluate carefully before enabling in production.

## SQL helpers

The package includes many `sqlite_*` helper functions (aggregates, JSON, math, date/time). They return SQL fragments. Use them only when you understand the generated SQL and have validated input. Avoid passing untrusted user input as raw SQL fragments.