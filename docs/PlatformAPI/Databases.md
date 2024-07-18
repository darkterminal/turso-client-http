# Database

| Method                | Parameters                                                       | Types                       | Description                                                              |
|-----------------------|------------------------------------------------------------------|-----------------------------|--------------------------------------------------------------------------|
| `__construct`         | `$token`                                                 | `string`                    | Constructor for the `Databases` class, sets the API token.               |
| `list`                | `$organizationName`                                      | `string`                    | List databases for a specific organization.                              |
| `create`              | `$organizationName`, `$databaseName`, `$group = 'default'` | `string`, `string`, `string` | Create a new database with optional group parameter.                    |
| `get_database`        | `$organizationName`, `$databaseName`           | `string`, `string`           | Get information about a specific database.                               |
| `usage`               | `$organizationName`, `$databaseName`           | `string`, `string`           | Get usage information for a specific database.                          |
| `delete`              | `$organizationName`, `$databaseName`           | `string`, `string`           | Delete a specific database.                                             |
| `list_instances`      | `$organizationName`, `$databaseName`           | `string`, `string`           | List instances for a specific database.                                  |
| `get_instance`        | `$organizationName`, `$databaseName`           | `string`, `string`           | Get information about a specific database instance.                     |
| `create_token`        | `$organizationName`, `$databaseName`, `$expiration = 'never'`, `$authorization = 'read-only'` | `string`, `string`, `string`, `string` | Create an access token for a specific database with optional parameters.|
| `invalidate_tokens`   | `$organizationName`, `$databaseName`           | `string`, `string`           | Invalidate access tokens for a specific database.                       |
| `upload_dump`         | `$organizationName`, `$filePath`               | `string`, `string`           | Upload a database dump.                                                  |
| `get`                 | -                                                                | `array`                     | Get the API response as an array.                                       |
| `toJSON`              | -                                                                | `string` or `array` or `null`         | Get the API response as a JSON string, array, or null if not applicable. |

**Example usage**

```php
<?php

// Assuming you have autoloading set up for your namespace

use Darkterminal\TursoHttp\core\Platform\Databases;

// Replace 'your_api_token', 'your_organization_name', and 'your_database_name' with actual values
$apiToken = 'your_api_token';
$organizationName = 'your_organization_name';
$databaseName = 'your_database_name';

// Create an instance of Databases with the provided API token
$databases = new Databases($apiToken);

// Example: List databases for a specific organization
$responseListDatabases = $databases->list($organizationName)->get();
print_r($responseListDatabases);

// Example: Create a new database
$responseCreateDatabase = $databases->create($organizationName, $databaseName)->get();
print_r($responseCreateDatabase);

// Example: Get information about a specific database
$responseGetDatabase = $databases->get_database($organizationName, $databaseName)->get();
print_r($responseGetDatabase);

// Example: Get usage information for a specific database
$responseUsage = $databases->usage($organizationName, $databaseName)->get();
print_r($responseUsage);

// Example: Delete a specific database
$responseDeleteDatabase = $databases->delete($organizationName, $databaseName)->get();
print_r($responseDeleteDatabase);

// Example: List instances for a specific database
$responseListInstances = $databases->list_instances($organizationName, $databaseName)->get();
print_r($responseListInstances);

// Example: Get information about a specific database instance
$responseGetInstance = $databases->get_instance($organizationName, $databaseName)->get();
print_r($responseGetInstance);

// Example: Create an access token for a specific database
$responseCreateToken = $databases->create_token($organizationName, $databaseName)->get();
print_r($responseCreateToken);

// Example: Invalidate access tokens for a specific database
$responseInvalidateTokens = $databases->invalidate_tokens($organizationName, $databaseName)->get();
print_r($responseInvalidateTokens);

// Example: Upload a database dump
$dumpFilePath = '/path/to/your/database/dump.sql';
$responseUploadDump = $databases->upload_dump($organizationName, $dumpFilePath)->get();
print_r($responseUploadDump);

// Example: Get the API response as a JSON string or array
$jsonResponse = $databases->toJSON();
echo $jsonResponse;

?>
```

> Turso Databases: https://docs.turso.tech/api-reference/databases
