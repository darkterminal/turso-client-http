---
title: Production Deployment
description: Production considerations for turso-client-http.
---

# Production Deployment

## Use explicit credentials

Set real endpoint and token via environment/configuration. Do not rely on the package's local test defaults.

```dotenv
TURSO_DATABASE_URL=libsql://your-db-your-org.turso.io
TURSO_AUTH_TOKEN=your-token
```

## HTTPS and tokens

Use `libsql://` (Turso Cloud) or `https://` for remote servers. Treat auth tokens as secrets; never commit them to source control.

## Timeouts and retries

The client uses cURL through `Utils::makeRequest()`. For production workloads, wrap calls with application-level timeouts/retries/circuit breakers where appropriate. Avoid retrying non-idempotent writes blindly.

## Observability

- Enable `DB_LOG_DEBUG=true` only for targeted debugging; pipeline logs can contain sensitive query structure/arguments.
- Record `LibSQLError` messages and HTTP status context in your application logs.
- Monitor Turso/libsql-server availability separately from application health.

## Transactions

Use transactions for multi-statement invariants. For simple idempotent setup scripts, `executeBatch()` is convenient; for business-critical flows, prefer explicit `transaction()` with careful error handling.

## Know the limits

This is an HTTP client. It does not provide embedded replicas, local sync files, or native offline-write semantics. If those are requirements, use the native libSQL PHP extension/ecosystem packages instead.