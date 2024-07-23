<?php

use Darkterminal\TursoHttp\LibSQL;
use Darkterminal\TursoHttp\sadness\LibSQLQueryBuilder;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$dbname = getenv('DB_URL');
$authToken = getenv('DB_TOKEN');
$db = new LibSQL("dbname=$dbname&authToken=$authToken");
$builder = new LibSQLQueryBuilder($db);

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

uses()->beforeEach(function () use ($dbname, $authToken, $db, $builder) {
    test()->dbname = $dbname;
    test()->authToken = $authToken;
    test()->db = $db;
    test()->builder = $builder;
})->in('Feature');

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

function createUserTable()
{
    test()->db->execute("CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, email TEXT)");
}

function dropTables(string $table)
{
    $result = test()->db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$table'");
    expect($result)->not->toBeEmpty();

    test()->builder->dropTable($table);
}
