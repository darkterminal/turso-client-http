---
title: Transactions & Pipeline
description: Interactive transactions, executeBatch, and Hrana pipeline behavior in turso-client-http.
---

# Transactions & Pipeline

## Interactive transactions

Use `transaction()` for explicit control:

```php
$tx = $db->transaction(); // default: DEFERRED

try {
    $tx->execute("INSERT INTO users (name) VALUES (?)", ['Ada']);
    $tx->execute("INSERT INTO users (name) VALUES (?)", ['Grace']);
    $tx->commit();
} catch (\Throwable $e) {
    $tx->rollback();
    throw $e;
}
```

Supported modes are `write`, `read`, and `deferred` (case-insensitive). Invalid modes throw `LibSQLError` with code `UNDEFINED_TRANSACTION_MODE`.

## executeBatch

`executeBatch()` accepts a SQL string or array of statements. It runs statements inside a transaction and rolls back on error.

```php
$db->executeBatch([
    "CREATE TABLE IF NOT EXISTS t(id INTEGER PRIMARY KEY, name TEXT)",
    "INSERT INTO t(name) VALUES ('a')",
]);
```

String input is split on semicolons after simple minification; prefer array form for clarity.

## prepare / query / execute

```php
$stmt = $db->prepare('SELECT * FROM users WHERE id = ?');
$result = $stmt->query([1]);
$rows = $result->fetchArray(LibSQL::LIBSQL_ASSOC);
```

`execute()` returns affected row count. `query()` returns `LibSQLResult`.

## Pipeline / close

The HTTP client sends Hrana pipeline requests. `close()` sends a close request to the connection. In ordinary HTTP usage, you usually do not need to manually close after every query; use it when you want to explicitly release the connection baton/state managed by the client.