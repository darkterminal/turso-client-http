![TursoHTTP](https://i.imgur.com/URmAyKX.png)

The `TursoHTTP` library is a PHP wrapper for Turso HTTP Database API (Only). It simplifies interaction with Turso databases using the Hrana over HTTP protocol. This library provides an object-oriented approach to build and execute SQL queries with same API Interface like PHP Native Extension [Turso Client PHP](https://github.com/tursodatabase/turso-client-php).

<p align="center">
  <a href="https://discord.gg/turso">
    <picture>
      <source media="(prefers-color-scheme: dark)" srcset="https://i.imgur.com/UhuW3zm.png">
      <source media="(prefers-color-scheme: light)" srcset="https://i.imgur.com/vljWbfr.png">
      <img alt="Shows a black logo in light color mode and a white one in dark color mode." src="https://i.imgur.com/vGCC0I4.png">
    </picture>
  </a>
</p>

---

## Requirements

- Intention and Courage
- Instinct as a Software Freestyle Engineer
- Strong determination!
- [Turso](https://tur.so/dt) Account
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

## Environment Variables

| Variable Name                             | Value                              | Description                                                                   |
| ----------------------------------------- | ---------------------------------- | ----------------------------------------------------------------------------- |
| `DB_URL` or `TURSO_URL`                   | Your Turso database URL            | -                                                                             |
| `DB_TOKEN` or `TURSO_TOKEN`               | Your Turso database TOKEN          | -                                                                             |
| `DB_TIMEZONE` or `TURSO_TIMEZONE`         | `Asia/Jakarta`                     | See the list of timezones [here](https://www.php.net/manual/en/timezones.php) |
| `DB_STRICT_QUERY` or `TURSO_STRICT_QUERY` | `true` / default: `false`          | Use strict query when using `explain` method in Query Builder                 |
| `DB_LOG_DEBUG` or `TURSO_LOG_DEBUG`       | `true` / default: `false`          | Set Query logs to save all query activities into a log file                   |
| `DB_LOG_NAME` or `TURSO_LOG_NAME`         | `libsql_debug`                     | Set Query channel name                                                        |
| `DB_LOG_PATH` or `TURSO_LOG_PATH`         | `$HOME/.turso-http/logs/debug.log` | Log file location                                                             |

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

use Darkterminal\TursoHttp\core\Enums\DataType;
use Darkterminal\TursoHttp\LibSQL;
use Darkterminal\TursoHttp\core\Builder\LibSQLBlueprint;
use Darkterminal\TursoHttp\core\Builder\LibSQLSchemaBuilder;

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

use Darkterminal\TursoHttp\LibSQL;
use Darkterminal\TursoHttp\core\Builder\LibSQLQueryBuilder;

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

## License

This library is licensed under the MIT License - see the [LICENSE](https://github.com/darkterminal/turso-http/blob/main/LICENSE) file for details.
