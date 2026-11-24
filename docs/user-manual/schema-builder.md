---
title: Schema Builder
description: Create and alter libSQL tables with turso-client-http schema builder.
---

# Schema Builder

`LibSQLSchemaBuilder` provides a small schema DSL for creating, altering, and dropping tables.

## Create

```php
use Darkterminal\TursoHttp\LibSQL;
use Darkterminal\TursoHttp\core\Builder\LibSQLSchemaBuilder;
use Darkterminal\TursoHttp\core\Enums\DataType;

$db = new LibSQL($url, ['authToken' => $token]);
$schema = new LibSQLSchemaBuilder($db);

$schema->create('users', function ($table) {
    $table->increments('id');
    $table->string('name');
    $table->unique('email', DataType::TEXT);
    $table->timestamps();
})->execute();
```

## Alter

```php
$schema->table('users', function ($table) {
    $table->addColumn(DataType::TEXT, 'bio');
})->execute();
```

## Drop

```php
$schema->drop('users')->execute();
```

## SQL preview

Use `toSql()` to inspect generated SQL before executing:

```php
$sql = $schema->create('users', function ($table) {
    $table->increments('id');
    $table->string('name');
})->toSql('users');
```

`execute()` runs accumulated queries inside a transaction and resets the query list. If an error occurs, it rolls back and rethrows.