![TursoHTTP](https://i.imgur.com/UgNUwIj.png)

The `TursoHTTP` library is a PHP wrapper for Turso HTTP Database API. It simplifies interaction with Turso databases using the Hrana over HTTP protocol. This library provides an object-oriented approach to build and execute SQL queries, retrieve query results, and access various response details.

## Requirements
- Intention and Courage
- Instinct as a Software Freestyle Engineer
- Strong determination!
- [Turso](https://turso.tech/) Account
- Don't forget to install [PHP](https://php.net) on your machine
- A cup of coffee and the music you hate the most
- Dancing (optional: if you are willing)

## Installation

You can install the `TursoHTTP` library using Composer:

```bash
composer require darkterminal/turso-http
```

## Usage Example

```php
use Darkterminal\TursoHTTP;

require_once 'vendor/autoload.php';

$databaseName       = "your-database-name";
$organizationName   = "your-organization-name";
$token              = "your-turso-database-token";

$tursoAPI = new TursoHTTP($databaseName, $organizationName, $token);
$query = new SadQuery();

$createTableUsers = $query->createTable('users')
    ->addColumn('userId', DataType::INTEGER, ['PRIMARY KEY', 'AUTOINCREMENT'])
    ->addColumn('name', DataType::TEXT)
    ->addColumn('email', DataType::TEXT)
    ->endCreateTable();

$createNewUser = $query->insert('users', ['name' => 'darkterminal', 'email' => 'darkterminal@duck.com'])->getQuery();

$tableCreated = $tursoAPI
    ->addRequest("execute", $createTableUsers)
    ->addRequest("close")
    ->queryDatabase()
    ->toJSON();
echo $tableCreated . PHP_EOL; // display the result on terminal

$userCreated = $tursoAPI
    ->addRequest("execute", $createNewUser)
    ->addRequest("close")
    ->queryDatabase()
    ->toJSON();
echo $userCreated . PHP_EOL; // display the result on terminal
```

## Features

- **Chainable Methods:** Easily build complex queries using chainable methods for adding requests.
- **Response Accessors:** Retrieve specific details from the query response using dedicated accessor methods.
- **Error Handling:** Detect cURL errors and gracefully handle them.
- **JSON Output:** Convert the response to JSON for convenient use.

## Documentation

### TursoHTTP

| Method                | Parameters                                     | Types                                | Description                                                                                                              |
|-----------------------|------------------------------------------------|--------------------------------------|--------------------------------------------------------------------------------------------------------------------------|
| `__construct`         | `$databaseName`, `$organizationName`, `$authToken` | `string`, `string`, `string`     | Constructor for creating a new instance of TursoHTTP. Requires database name, organization name, and authentication token. |
| `addRequest`          | `$type`, `$stmt = ''`, `$baton = ''`           | `string`, `string`, `string`     | Build request query statement. Supports "execute" or "close" request types with optional SQL statement and baton.      |
| `queryDatabase`       | -                                              | -                                    | Run the query for Turso Database.                                                                                       |
| `get`                 | -                                              | -                                    | Return the full result database query in an associative array.                                                           |
| `toJSON`              | -                                              | -                                    | Return the full result database query in JSON format.                                                                   |
| `getBaton`            | -                                              | -                                    | Return only the baton (connection identifier).                                                                          |
| `getBaseUrl`          | -                                              | -                                    | Return only the base URL.                                                                                                |
| `getResults`          | -                                              | -                                    | Return the results for each of the requests made in the pipeline.                                                        |
| `getCols`             | -                                              | -                                    | Return the list of columns for the returned rows.                                                                       |
| `getRows`             | -                                              | -                                    | Return the rows returned for the query.                                                                                 |
| `getAffectedRowCount` | -                                              | -                                    | Return the number of rows affected by the query.                                                                        |
| `getLastInsertRowId`  | -                                              | -                                    | Return the ID of the last inserted row.                                                                                 |
| `getReplicationIndex` | -                                              | -                                    | Return the replication timestamp at which this query was executed.                                                      |

### SadQuery

| Method | Parameter & Type | Description |
|--------|-------------------|-------------|
| `createTable` | `string $tableName` | Start building a CREATE TABLE query for the specified table. |
| `addColumn` | `string $columnName, string $dataType, array $constraints = []` | Add a column definition to the table. |
| `endCreateTable` |  | Generate the final CREATE TABLE SQL query based on added columns. |
| `renameColumn` | `string $tableName, string $oldColumnName, string $newColumnName, string $dataType` | Rename a column in the specified table. |
| `dropTable` | `string $tableName` | Drop a table if it exists. |
| `select` | `array $columns` | Start building a SELECT query with specified columns. |
| `from` | `string $table` | Specify the table to SELECT FROM. |
| `where` | `string $condition` | Add a WHERE clause to the SELECT query. |
| `orderBy` | `array $columns` | Add an ORDER BY clause to the SELECT query. |
| `limit` | `int $limit` | Add a LIMIT clause to the SELECT query. |
| `between` | `string $column, $value1, $value2` | Add a WHERE clause to check if a column value is between two values. |
| `in` | `string $column, array $values` | Add a WHERE clause to check if a column value is in a list of values. |
| `like` | `string $column, string $pattern` | Add a WHERE clause to check if a column value matches a pattern using LIKE. |
| `isNull` | `string $column` | Add a WHERE clause to check if a column value is NULL. |
| `glob` | `string $column, string $pattern` | Add a WHERE clause to check if a column value matches a pattern using GLOB. |
| `join` | `string $table, string $condition` | Add a JOIN clause to the SELECT query. |
| `innerJoin` | `string $table, string $condition` | Add an INNER JOIN clause to the SELECT query. |
| `leftJoin` | `string $table, string $condition` | Add a LEFT JOIN clause to the SELECT query. |
| `crossJoin` | `string $table` | Add a CROSS JOIN clause to the SELECT query. |
| `selfJoin` | `string $table, string $condition` | Add a self JOIN clause to the SELECT query. |
| `fullOuterJoin` | `string $table, string $condition` | Add a FULL OUTER JOIN clause to the SELECT query. |
| `groupBy` | `array $columns` | Add a GROUP BY clause to the SELECT query. |
| `having` | `string $condition` | Add a HAVING clause to the SELECT query. |
| `union` | `string $query` | Add a UNION clause to the SELECT query. |
| `except` | `string $query` | Add an EXCEPT clause to the SELECT query. |
| `intersect` | `string $query` | Add an INTERSECT clause to the SELECT query. |
| `subquery` | `string $subquery` | Add a subquery to the SELECT query. |
| `exists` | `string $subquery` | Add an EXISTS clause with a subquery to the SELECT query. |
| `case` | `array $conditions, string $else` | Add a CASE statement to the SELECT query. |
| `insert` | `string $table, array $values` | Add an INSERT INTO statement to the query. |
| `update` | `string $table, array $values` | Add an UPDATE statement to the query. |
| `delete` | `string $table` | Add a DELETE FROM statement to the query. |
| `replace` | `string $table, array $values` | Add a REPLACE INTO statement to the query. |
| `beginTransaction` |  | Begin a transaction in the query. |
| `commit` |  | Commit the current transaction in the query. |
| `rollback` |  | Rollback the current transaction in the query. |
| `getQuery` |  | Get the current query string. |

## License

This library is licensed under the MIT License - see the [LICENSE](https://github.com/darkterminal/turso-http/blob/main/LICENSE) file for details.
