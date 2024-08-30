<?php

use Darkterminal\TursoHttp\LibSQL;
use Darkterminal\TursoHttp\core\Builder\LibSQLQueryBuilder;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Disable strict query in test
putenv("DB_STRICT_QUERY=false");

$dbname = getenv('DB_URL');
$authToken = getenv('DB_TOKEN');
$db = new LibSQL("dbname=$dbname&authToken=$authToken");
$builder = new LibSQLQueryBuilder($db);

uses()->beforeAll(function () use ($db, $builder) {
    $builder->dropAllTables();

    $directory = getcwd() . '/tests/_files/database';
    $pattern = '/\.sql$/';

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
    $regexIterator = new RegexIterator($iterator, $pattern, RegexIterator::MATCH );

    $samples = [];
    foreach ($regexIterator as $file) {
        $samples[] = $file->getPathname();
    }

    $createTables = $samples[0];
    array_shift($samples);

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
            'PRAGMA foreign_keys=ON'
        ]);
    }
})->in('Feature');

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
    ->afterAll(function () use ($builder) {
        $builder->dropAllTables();
    })
    ->in('Feature');

uses()
    ->beforeAll(function () use ($db) {
        $db->executeBatch([
            "CREATE TABLE artists_backup(artistid INTEGER PRIMARY KEY AUTOINCREMENT, name NVARCHAR)",
            "INSERT INTO artists_backup SELECT artistid,name FROM artists"
        ]);
    })->afterAll(function () use ($builder) {
        $builder->dropTable('artists_backup');
    })
    ->group('thatNeedsSampleTableWithData');

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
