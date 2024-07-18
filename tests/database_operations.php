<?php

/**
 * THIS IS POOR TESTING, IF YOU HAVE KNOWLEDGE ABOUT UNIT TESTING
 * PULL REQUEST ARE VERY WELCOME!
 */

use Darkterminal\TursoHttp\LibSQL;

require_once getcwd() . '/vendor/autoload.php';

$dbname = getenv('DB_URL');
$authToken = getenv('DB_TOKEN');
$db = new LibSQL("dbname=$dbname&authToken=$authToken");

function cleanUp()
{
    global $db;
    $db->execute("DROP TABLE IF EXISTS users");
}

function demo(string $menu)
{
    global $db;

    switch ($menu) {
        case 'version':
            echo "TEST: Get libSQL Server Version" . PHP_EOL;
            echo $db->version() . PHP_EOL;
            break;
        case 'create_table':
            echo "TEST: Creating users table" . PHP_EOL;
            $db->execute("CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, email TEXT)");
            break;
        case 'error_create_table':
            echo "TEST: Error creating users table" . PHP_EOL;
            $db->execute("CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, email TEXT)");
            break;
        case 'create_user_with_positional_args':
            echo "TEST: Create a user" . PHP_EOL;
            $db->execute("INSERT INTO users (name, email) VALUES (?, ?)", ['Test User 1', 'testuser1@email.com']);
            break;
        case 'create_user_with_named_args':
            echo "TEST: Create a user" . PHP_EOL;
            $db->execute("INSERT INTO users (name, email) VALUES (:name, :email)", [
                ':name' => 'Test User 2',
                ':email' => 'testuser2@email.com'
            ]);
            break;
        case 'display_users':
            echo "TEST: Display all users" . PHP_EOL;
            var_dump($db->query("SELECT * FROM users")->fetchArray(LibSQL::LIBSQL_ASSOC));
            break;
        case 'get_changes':
            $db->execute("UPDATE users SET name = ? WHERE id = ?", ['Test User 1 Updated', 1]);
            var_dump($db->changes());
            break;
        case 'is_autocommit':
            echo "TEST: Checking is transaction auto commit" . PHP_EOL;
            var_dump($db->isAutoCommit());
            break;
        case 'using_prepare_execute':
            echo "TEST: Update user 1 using prepare execute" . PHP_EOL;
            var_dump($db->prepare("UPDATE users SET name = ? WHERE id = ?")->execute(['Update Test User 1', 1]));
            break;
        case 'using_prepare_query':
            echo "TEST: Update user 2 using prepare query" . PHP_EOL;
            var_dump($db->prepare("UPDATE users SET name = ? WHERE id = ?")->query(['Update Test User 2', 2])->fetchArray(LibSQL::LIBSQL_ALL));
            break;
        case 'transaction':
            echo "TEST: Transaction" . PHP_EOL;
            $db->transaction()
                ->execute("UPDATE users SET name = ? WHERE id = ?", ['Test User 1', 1])
                ->execute("UPDATE users SET name = ? WHERE id = ?", ['Test User 2', 2])
                ->commit();
            break;

        default:
            echo "Done!" . PHP_EOL;
            break;
    }
    sleep(3);
}

$tests = [
    'version',
    'create_table',
    'create_user_with_positional_args',
    'create_user_with_named_args',
    'display_users',
    'get_changes',
    'is_autocommit',
    'using_prepare_execute',
    'using_prepare_query',
    'transaction',
    'error_create_table',
    'done'
];

cleanUp();
foreach ($tests as $case) {
    demo($case);
}
cleanUp();
