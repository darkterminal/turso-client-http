---
title: Error Semantics
description: How turso-client-http reports HTTP, SQL, and transaction errors.
---

# Error Semantics

## LibSQLError

Most client failures throw `Darkterminal\TursoHttp\core\LibSQLError`.

```php
use Darkterminal\TursoHttp\core\LibSQLError;

try {
    $db->query('SELEC 1'); // invalid SQL
} catch (LibSQLError $e) {
    echo $e->getMessage();
    echo $e->getCode(); // normalized int; string codes become 0
}
```

The constructor accepts `(string $message, string $code)`. Non-numeric codes are normalized to `0` internally, while the original code is passed as the message context by callers where relevant. Inspect the message for server-side codes like Hrana/SQL errors.

## HTTP errors

`Utils::makeRequest()` treats non-2xx responses as errors. It attempts to extract a server `error` message from JSON bodies:

```text
HTTP 401: Unauthorized
HTTP 404: route not found
```

cURL transport failures throw `LibSQLError` with code `CURL_ERROR`.

## SQL/pipeline errors

Hrana pipeline error responses are surfaced as `LibSQLError` by `Utils::removeCloseResponses()` and request execution paths.

## Transaction errors

- Invalid transaction mode: `UNDEFINED_TRANSACTION_MODE`.
- `executeBatch()` rolls back and rethrows as `LibSQLError` with code `EXECUTE_BATCH_ERROR`.

## Strict query mode

When `DB_STRICT_QUERY=true`, the query builder may throw `LibSQLError` with code `ERR_STRICT_QUERY` before executing an expensive query plan. This is intentional guardrail behavior, not a server error.