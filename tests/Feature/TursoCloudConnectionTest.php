<?php

declare(strict_types=1);

/*
NOTE:
    This test exercises this package against a REAL Turso Cloud database
    (libsql://<db>-<org>.turso.io) — it needs a live endpoint and a valid token.

    It is skipped unless both variables are set:

      TURSO_CLOUD_DB_URL=libsql://<db>-<org>.turso.io
      TURSO_CLOUD_DB_TOKEN=<token>

    The test creates and drops its own table (turso_http_cloud_proof_*) and
    always attempts to clean up afterwards, even on failure.
*/

use Darkterminal\TursoHttp\LibSQL;

function shouldSkipCloudTests(): bool
{
    return ! (getenv('TURSO_CLOUD_DB_URL') && getenv('TURSO_CLOUD_DB_TOKEN'));
}

function cloudDb(): LibSQL
{
    return new LibSQL(
        'dbname=' . getenv('TURSO_CLOUD_DB_URL') . '&authToken=' . getenv('TURSO_CLOUD_DB_TOKEN')
    );
}

function cloudProofTable(): string
{
    return 'turso_http_cloud_proof_' . bin2hex(random_bytes(6));
}

test('turso cloud: connection, version and typed value round trip', function () {
    if (shouldSkipCloudTests()) {
        $this->markTestSkipped('TURSO_CLOUD_DB_URL / TURSO_CLOUD_DB_TOKEN not set — needs a real Turso Cloud database');
    }

    $table = cloudProofTable();
    $db = cloudDb();

    try {
        $v = $db->query('SELECT sqlite_version() AS v')->fetchArray(LibSQL::LIBSQL_ASSOC)[0]['v'] ?? null;
        expect($v)->not->toBeEmpty();

        $db->execute("CREATE TABLE {$table} (id INTEGER PRIMARY KEY, name TEXT, rating FLOAT, note TEXT)");
        $db->execute("INSERT INTO {$table} (name, rating, note) VALUES (?, ?, ?)", ['Ada', 9.5, null]);
        $db->execute("INSERT INTO {$table} (name, rating, note) VALUES (?, ?, ?)", ['Grace', 8.25, 'notes']);

        $rows = $db->query("SELECT name, rating, note FROM {$table} ORDER BY rating DESC")
            ->fetchArray(LibSQL::LIBSQL_ASSOC);

        expect($rows)->toHaveCount(2);
        expect($rows[0]['name'])->toBe('Ada')
            ->and($rows[0]['rating'])->toBeFloat()
            ->and($rows[0]['note'])->toBeNull()
            ->and($rows[1]['name'])->toBe('Grace')
            ->and($rows[1]['note'])->toBe('notes');

        // integer round trip through the Hrana typed-cell codec
        $id = (int) $db->query("SELECT id FROM {$table} WHERE name = 'Ada'")
            ->fetchArray(LibSQL::LIBSQL_ASSOC)[0]['id'];
        expect($id)->toBeInt()->toBeGreaterThan(0);
    } finally {
        try {
            $db->execute("DROP TABLE IF EXISTS {$table}");
        } catch (\Throwable) {
            // best effort cleanup
        }
    }
});

test('turso cloud: transaction commit and rollback', function () {
    if (shouldSkipCloudTests()) {
        $this->markTestSkipped('TURSO_CLOUD_DB_URL / TURSO_CLOUD_DB_TOKEN not set — needs a real Turso Cloud database');
    }

    $table = cloudProofTable();
    $db = cloudDb();

    try {
        $db->execute("CREATE TABLE {$table} (id INTEGER PRIMARY KEY, name TEXT)");

        $tx = $db->transaction('deferred');
        $tx->execute("INSERT INTO {$table} (name) VALUES (?)", ['committed']);
        $tx->commit();

        $committed = (int) $db->query("SELECT count(*) AS c FROM {$table} WHERE name = 'committed'")
            ->fetchArray(LibSQL::LIBSQL_ASSOC)[0]['c'];
        expect($committed)->toBe(1);

        $tx = $db->transaction('deferred');
        $tx->execute("INSERT INTO {$table} (name) VALUES (?)", ['rolled-back']);
        $tx->rollback();

        $rolledBack = (int) $db->query("SELECT count(*) AS c FROM {$table} WHERE name = 'rolled-back'")
            ->fetchArray(LibSQL::LIBSQL_ASSOC)[0]['c'];
        expect($rolledBack)->toBe(0);
    } finally {
        try {
            $db->execute("DROP TABLE IF EXISTS {$table}");
        } catch (\Throwable) {
            // best effort cleanup
        }
    }
});
