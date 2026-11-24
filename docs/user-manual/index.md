---
title: User Manual
description: Source-of-truth documentation for Turso Client HTTP — a pure-PHP Hrana over HTTP client for libSQL.
---

# User Manual

Turso Client HTTP is a **pure-PHP** client for [libSQL](https://github.com/tursodatabase/libsql). It talks to Turso Cloud or a self-hosted `sqld`/libsql-server over **Hrana over HTTP**. There is no native extension to compile, no FFI, and no `php.ini` change.

This manual is the source of truth for the package. The GitHub wiki is kept for historical links, but new and updated documentation belongs here.

## What this package is

- **HTTP client** — uses `ext-curl` to call the libSQL Hrana v2 pipeline endpoint.
- **Portable** — works on Linux, macOS, Windows, CI runners, and shared hosting where PHP extensions cannot be installed.
- **Familiar** — `LibSQL` provides `query()`, `execute()`, `executeBatch()`, `prepare()`, and `transaction()`.
- **Batteries included** — query builder, schema builder, and Laravel service provider/facade.

## What this package is not

- Not the native libSQL PHP extension. For embedded replicas, native sync, or offline-write flavors, use the native extension / native-client packages.
- Not a SQLite file driver. It connects to an HTTP endpoint.

## Quick map

| Goal | Start here |
|---|---|
| Install and run first query | [Getting Started](getting-started/) |
| Configure DSN, env vars, hooks | [Configuration](configuration/) |
| Connect to local sqld | [Local / sqld](local-connection/) |
| Connect to Turso Cloud | [Remote / Turso Cloud](remote-connection/) |
| Fluent SQL builder | [Query Builder](query-builder/) |
| Create/alter tables | [Schema Builder](schema-builder/) |
| Transactions and batching | [Transactions & Pipeline](transactions/) |
| Use in Laravel | [Laravel](laravel/) |
| Error behavior | [Error Semantics](error-semantics/) |
| Deploy safely | [Production Deployment](production-deployment/) |
| Common failures | [Troubleshooting](troubleshooting/) |
| Class reference | [LibSQL](reference/libsql-class/), [LibSQLResult](reference/result-class/) |