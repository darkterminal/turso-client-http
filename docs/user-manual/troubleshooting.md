---
title: Troubleshooting
description: Common turso-client-http errors and fixes.
---

# Troubleshooting

## `HTTP 401: Unauthorized` / `HTTP 403`

- Check `authToken` / `TURSO_AUTH_TOKEN`.
- Confirm the token belongs to the target database/environment.
- For local sqld, confirm whether the server actually requires auth.

## `HTTP 404: route not found` on `version()`

Turso Cloud may not expose `GET /version`. Use a query like `SELECT 1` for connectivity checks.

## cURL errors

`LibSQLError: cURL error (HTTP 0): ...`

- Verify DNS/network/firewall access to the endpoint.
- Verify TLS/certificates for HTTPS endpoints.
- Confirm `ext-curl` is installed and enabled.

## Invalid SQL / Hrana pipeline error

The server returns SQL errors through the Hrana pipeline. Inspect the `LibSQLError` message. Reproduce with the raw SQL in `query()` before debugging builder-generated SQL.

## Wrong environment variables

The client has local-test defaults for helpers. If you accidentally hit `127.0.0.1:8081`, check:

- `DB_URL` / `TURSO_URL`
- `DB_TOKEN` / `TURSO_TOKEN`
- whether you constructed `LibSQL` directly or used global helpers

## Strict query rejection

`ERR_STRICT_QUERY` means `DB_STRICT_QUERY=true` and the builder judged the plan potentially expensive. Inspect the explanation, add indexes, or narrow the query. Do not disable the guard blindly in development.

## Date/time looks wrong

`DATETIME` values are cast through `DB_TIMEZONE` / default `UTC` when detected as `Y-m-d H:i:s`. Set `DB_TIMEZONE` explicitly if your application expects a specific display timezone, but prefer storing UTC in the database.