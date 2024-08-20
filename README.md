![TursoHTTP](https://i.imgur.com/URmAyKX.png)

The `TursoHTTP` library is a PHP wrapper for Turso HTTP Database API (Only). It simplifies interaction with Turso databases using the Hrana over HTTP protocol. This library provides an object-oriented approach to build and execute SQL queries with same API Interface like PHP Native Extension [Turso Client PHP](https://github.com/tursodatabase/turso-client-php).

---

<p align="center">
  <a href="https://tur.so/dt" target="_blank">🚀 I'll give you 10% off Turso Scaler and Pro for 1 Year 🚀</a>
</p>

---

## Requirements
- Intention and Courage
- Instinct as a Software Freestyle Engineer
- Strong determination!
- [Turso](https://tur.so/dt) Account (🚀 10% off Turso Scaler and Pro for 1 Year 🚀)
- Don't forget to install [PHP](https://php.net) on your machine
- A cup of coffee and the music you hate the most
- Dancing (optional: if you are willing)

## Features
- **[libSQL Native Extension](https://github.com/tursodatabase/turso-client-php)** like API Interface
- Schema Builder
- Query Builder
- Turso Platform API
- Timezone Support

## Installation

You can install the **TursoHTTP** library using Composer:

```bash
composer require darkterminal/turso-http
```

## Setting Up Timezone

Set the database display timezome in your `env` variable. See the list of timezones [here](https://www.php.net/manual/en/timezones.php)

```env
DB_TIMEZOME=Asia/Jakarta
```

## Usage Example

```php
use Darkterminal\TursoHttp\LibSQL;

require_once 'vendor/autoload.php';

$dbname     = getenv('DB_URL');
$authToken  = getenv('DB_TOKEN');
$db         = new LibSQL("dbname=$dbname&authToken=$authToken");

echo $db->version() . PHP_EOL;

$create_table = <<<SQL
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT, 
    name TEXT, 
    email TEXT
)
SQL;

$db->execute($create_table);
```

## LibSQL Schema Builder

```php
<?php

/**
 * THIS IS POOR TESTING, IF YOU HAVE KNOWLEDGE ABOUT UNIT TESTING
 * PULL REQUEST ARE VERY WELCOME!
 */

use Darkterminal\TursoHttp\core\Enums\DataType;
use Darkterminal\TursoHttp\LibSQL;
use Darkterminal\TursoHttp\sadness\LibSQLBlueprint;
use Darkterminal\TursoHttp\sadness\LibSQLSchemaBuilder;

require_once 'vendor/autoload.php';

try {
    $dbname = getenv('DB_URL');
    $authToken = getenv('DB_TOKEN');

    $db = new LibSQL("dbname=$dbname&authToken=$authToken");
    $schemaBuilder = new LibSQLSchemaBuilder($db);

    // Creating table
    $schemaBuilder->create('contacts', function(LibSQLBlueprint $table) {
        $table->increments('id');
        $table->string('name');
        $table->unique('email');
        $table->string('phone');
        $table->timestamps();
    })->execute();

    echo "Table created successfully.\n";

    // Add new column in the table
    $schemaBuilder->table('contacts', function(LibSQLBlueprint $table) {
        $table->addColumn(DataType::TEXT, 'address');
    })->execute();

    echo "Column added successfully.\n";

    // Drop the table
    $schemaBuilder->drop('contacts')->execute();

    echo "Table contacts successfully dropped!.\n";
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}
```

## Raw Query

```php
<?php

/**
 * THIS IS POOR TESTING, IF YOU HAVE KNOWLEDGE ABOUT UNIT TESTING
 * PULL REQUEST ARE VERY WELCOME!
 */

use Darkterminal\TursoHttp\LibSQL;

require_once getcwd() . '/vendor/autoload.php';

$dbname     = getenv('DB_URL');
$authToken  = getenv('DB_TOKEN');
$db         = new LibSQL("dbname=$dbname;authToken=$authToken");

$query = <<<SQL
INSERT INTO contacts (name, email, phone, address) VALUES (?, ?, ?, ?)
SQL;

$db->execute($query, [
    'Imam Ali Mustofa',
    'darkterminal@duck.com',
    '08123456789',
    'Punk Univers'
]);
```

## Query Builder

```php
<?php

/**
 * THIS IS POOR TESTING, IF YOU HAVE KNOWLEDGE ABOUT UNIT TESTING
 * PULL REQUEST ARE VERY WELCOME!
 */

use Darkterminal\TursoHttp\LibSQL;
use Darkterminal\TursoHttp\sadness\LibSQLQueryBuilder;

require_once getcwd() . '/vendor/autoload.php';

$dbname = getenv('DB_URL');
$authToken = getenv('DB_TOKEN');
$db = new LibSQL("dbname=$dbname;authToken=$authToken");

$sql = new LibSQLQueryBuilder($db);

$contacts = $sql->table('contacts')
    ->where('address', '=', 'Punk Universe')
    ->get();

var_dump($contacts);
```

## Turso Platform API - PHP

Manage databases, replicas, and teams with the Turso Platform API using PHP.

- [Quickstart](docs/PlatformAPI/README.md)
- [API Tokens](docs/PlatformAPI/APITokens.md)
- [Audit Logs](docs/PlatformAPI/AuditLogs.md)
- [Databases](docs/PlatformAPI/Databases.md)
- [Groups](docs/PlatformAPI/Groups.md)
- [Locations](docs/PlatformAPI/Locations.md)

## License

This library is licensed under the MIT License - see the [LICENSE](https://github.com/darkterminal/turso-http/blob/main/LICENSE) file for details.
