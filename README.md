# Turso (HTTP SDK)

The `TursoHTTP` library is a PHP wrapper for Turso HTTP Database API. It simplifies interaction with Turso databases using the Hrana over HTTP protocol. This library provides an object-oriented approach to build and execute SQL queries, retrieve query results, and access various response details.

## Installation

You can install the `TursoHTTP` library using Composer. Add the following to your `composer.json` file:

```json
{
    "require": {
        "darkterminal/turso-http": "^1.0"
    }
}
```

Then run:

```bash
composer install
```

## Usage Example

```php
use Darkterminal\TursoHTTP;

require_once 'vendor/autoload.php';

$databaseName       = "your-database-name";
$organizationName   = "your-organization-name";
$token              = "your-turso-database-token";

$tursoAPI = new TursoHTTP($databaseName, $organizationName, $token);

$queryResponse = $tursoAPI
    ->addRequest("execute", "CREATE TABLE users (userID TEXT NOT NULL PRIMARY KEY, firstName TEXT NOT NULL, lastName TEXT NOT NULL, email TEXT NOT NULL UNIQUE)")
    ->addRequest("close")
    ->queryDatabase()
    ->toJSON();

echo $queryResponse;
```

## Features

- **Chainable Methods:** Easily build complex queries using chainable methods for adding requests.
- **Response Accessors:** Retrieve specific details from the query response using dedicated accessor methods.
- **Error Handling:** Detect cURL errors and gracefully handle them.
- **JSON Output:** Convert the response to JSON for convenient use.

## Documentation

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

## License

This library is licensed under the MIT License - see the [LICENSE](https://github.com/darkterminal/turso-http/blob/main/LICENSE) file for details.
