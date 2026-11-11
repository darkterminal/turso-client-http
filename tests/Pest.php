<?php

use Darkterminal\TursoHttp\LibSQL;
use Darkterminal\TursoHttp\core\Builder\LibSQLQueryBuilder;
use Dotenv\Dotenv;

// Load .env if present (local development convenience), otherwise rely on
// environment variables / defaults so CI needs no secrets.
if (file_exists(__DIR__ . '/../.env')) {
    Dotenv::createUnsafeImmutable(__DIR__ . '/..')->load();
}

// Disable strict query in test
putenv("DB_STRICT_QUERY=false");

$dbname = getenv('DB_URL') ?: 'http://127.0.0.1:8081';
$authToken = getenv('DB_TOKEN') ?: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJFZERTQSJ9.eyJpYXQiOjE3MzY2MzU1MTUsIm5iZiI6MTczNjYzNTUxNSwiZXhwIjoxNzM3MjQwMzE1LCJqdGkiOiJkYjEifQ.5sm4FN4PosAJ5h9wLay6q3ryAxbGRGuETU1A3F_Tr3WXpAEnr98tmAa92qcpZz_YZN0T_h4RqjGlEMgrSwIJAQ';
$db = new LibSQL("dbname=$dbname&authToken=$authToken");
$builder = new LibSQLQueryBuilder($db);

/*
|--------------------------------------------------------------------------
| One-time database seed
|--------------------------------------------------------------------------
|
| Pest's uses()->beforeAll() is unusable here: hooks bound via uses() are
| applied at the first per-test setUp() of each generated class, which is
| AFTER PHPUnit's setUpBeforeClass() runs. The seed therefore never
| executed, and the matching afterAll(dropAllTables()) wiped the fixtures
| after EVERY test file.
|
| Instead the suite seeds a fresh cutover database once, eagerly, at
| bootstrap time (once per phpunit process). Set TURSO_HTTP_SKIP_SEED=1 to
| opt out (e.g. read-only smoke tests).
|
*/
if (getenv('TURSO_HTTP_SKIP_SEED') !== '1') {
    $builder->dropAllTables();

    $directory = __DIR__ . '/_files/database';

    $samples = glob($directory . '/*.sql');
    sort($samples);

    $createTables = array_shift($samples);

    $createTableQueries = array_map(function ($query) {
        return trim($query);
    }, explode('--##', file_get_contents($createTables)));

    foreach ($createTableQueries as $query) {
        $db->execute($query);
    }

    foreach ($samples as $sample) {
        $db->executeBatch([
            'PRAGMA foreign_keys=OFF',
            file_get_contents($sample),
            'PRAGMA foreign_keys=ON',
        ]);
    }

    // Fixture copy used by the thatNeedsSampleTableWithData group (DeleteTest)
    $db->executeBatch([
        "CREATE TABLE artists_backup(artistid INTEGER PRIMARY KEY AUTOINCREMENT, name NVARCHAR)",
        "INSERT INTO artists_backup SELECT artistid,name FROM artists",
    ]);
}

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses()
    ->beforeEach(function () use ($dbname, $authToken, $db, $builder) {
        test()->dbname = $dbname;
        test()->authToken = $authToken;
        test()->db = $db;
        test()->builder = $builder;
    })
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/


/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/
function makeItOneLine($sql)
{
    // Remove line breaks and extra spaces
    $oneLineSql = preg_replace('/\s+/', ' ', $sql);
    // Remove spaces before and after parentheses
    $oneLineSql = preg_replace('/\s*\(\s*/', '(', $oneLineSql);
    $oneLineSql = preg_replace('/\s*\)\s*/', ')', $oneLineSql);
    $oneLineSql = trim($oneLineSql);
    return $oneLineSql;
}
