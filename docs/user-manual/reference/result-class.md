---
title: LibSQLResult
description: Reference for query result fetching in turso-client-http.
---

# LibSQLResult

`Darkterminal\TursoHttp\core\Http\LibSQLResult` wraps Hrana query results.

## Fetching

```php
$result = $db->query('SELECT id, name FROM users');

$assoc = $result->fetchArray(LibSQL::LIBSQL_ASSOC);
$num   = $result->fetchArray(LibSQL::LIBSQL_NUM);
$both  = $result->fetchArray(LibSQL::LIBSQL_BOTH);
$all   = $result->fetchArray(LibSQL::LIBSQL_ALL);
```

## Methods

| Method | Returns | Description |
|---|---|---|
| `fetchArray(int $mode = LibSQL::LIBSQL_BOTH)` | `array` | Rows in selected mode. |
| `lazyFetchArray()` | `Generator` | Yields merged assoc+num rows. |
| `finalize()` | `void` | No-op; rows are in-memory arrays. |
| `reset()` | `void` | No-op; result is immutable after construction. |
| `getStats()` | `array` | `rows_read`, `rows_written`, `query_duration_ms`. |
| `columnName(int $column)` | `string` | Column name by index. |
| `columnType(int $column)` | `?string` | Declared column type. |
| `numColumns()` | `int` | Column count. |

## Value decoding

Typed Hrana cells are decoded to PHP values. `DATETIME` strings in `Y-m-d H:i:s` format may be converted according to `DB_TIMEZONE`/UTC settings. BLOB values are decoded from base64 transport representation.