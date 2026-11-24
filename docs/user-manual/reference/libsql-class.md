---
title: LibSQL
description: Reference for the Darkterminal\TursoHttp\LibSQL class.
---

# LibSQL

`Darkterminal\TursoHttp\LibSQL` is the main client.

## Constructor

```php
use Darkterminal\TursoHttp\LibSQL;

$db = new LibSQL(string $dsn, array $options = []);
```

## Constants

| Constant | Value | Meaning |
|---|---:|---|
| `LIBSQL_ASSOC` | 1 | Fetch associative rows |
| `LIBSQL_NUM` | 2 | Fetch numeric rows |
| `LIBSQL_BOTH` | 3 | Fetch both associative and numeric keys |
| `LIBSQL_ALL` | 4 | Return raw result structure |

## Methods

| Method | Returns | Description |
|---|---|---|
| `getBaseUrl()` | `string` | Normalized HTTP base URL. |
| `getAuthToken()` | `string` | Configured auth token. |
| `getConnection()` | `Request` | Underlying Hrana request handler. |
| `version()` | `string` | Calls `GET /version`. May fail on Turso Cloud. |
| `query(string $sql, array $parameters = [])` | `LibSQLResult` | Execute query and return result. |
| `execute(string $sql, array $parameters = [])` | `int` | Execute statement and return affected rows. |
| `executeBatch(array|string $queries)` | `void` | Execute statements in a transaction; rollback on error. |
| `prepare(string $sql)` | `LibSQLStatement` | Prepare a statement. |
| `transaction(string $mode = 'deferred')` | `LibSQLTransaction` | Begin interactive transaction (`read`, `write`, `deferred`). |
| `changes()` | `int` | Affected rows from last tracked operation. |
| `isAutoCommit()` | `bool` | Auto-commit flag. |
| `close()` | `void` | Send close request. |