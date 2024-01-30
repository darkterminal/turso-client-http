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

## Turso Platform API

Manage databases, replicas, and teams with the Turso Platform API using PHP.

### API Token

| Method      | Parameters                | Types           | Description                                                        |
|-------------|---------------------------|-----------------|--------------------------------------------------------------------|
| `__construct`| `$token`          | `string`        | Constructor for the `APITokens` class, sets the API token.         |
| `list`      | -                         | -               | List API tokens.                                                  |
| `create`    | `$tokenName`      | `string`        | Create a new API token with the specified name.                   |
| `validate`  | -                         | -               | Validate the API token.                                           |
| `revoke`    | `$tokenName`      | `string`        | Revoke an API token with the specified name.                       |
| `get`       | -                         | `array`         | Get the API response as an array.                                  |
| `toJSON`    | -                         | `string` or `array` or `null` | Get the API response as a JSON string, array, or null if not applicable. |

**Example Usage**

```php
<?php

// Assuming you have autoloading set up for your namespace

use Darkterminal\TursoHttp\core\Platform\APITokens;

// Replace 'your_api_token' with the actual API token you have
$apiToken = 'your_api_token';

// Create an instance of APITokens with the provided API token
$apiTokens = new APITokens($apiToken);

// Example: List API tokens
$responseList = $apiTokens->list()->get();
print_r($responseList);

// Example: Create a new API token
$newTokenName = 'new_token_name';
$responseCreate = $apiTokens->create($newTokenName)->get();
print_r($responseCreate);

// Example: Validate the API token
$responseValidate = $apiTokens->validate()->get();
print_r($responseValidate);

// Example: Revoke an API token
$tokenToRevoke = 'token_to_revoke';
$responseRevoke = $apiTokens->revoke($tokenToRevoke)->get();
print_r($responseRevoke);

// Example: Get the API response as a JSON string or array
$jsonResponse = $apiTokens->toJSON();
echo $jsonResponse;

```

> Turso API Token: https://docs.turso.tech/api-reference/tokens

### Audit Logs

| Method              | Parameters                                    | Types                     | Description                                                     |
|---------------------|-----------------------------------------------|---------------------------|-----------------------------------------------------------------|
| `__construct`       | `$token`                              | `string`                  | Constructor for the `AuditLogs` class, sets the API token.      |
| `list_audit_logs`   | `$organizationName`, `$page_size`, `$page` | `string`, `int`, `int`    | List audit logs for a specific organization with pagination.    |
| `get`               | -                                             | `array`                   | Get the API response as an array.                               |
| `toJSON`            | -                                             | `string|array|null`       | Get the API response as a JSON string, array, or null if not applicable. |

**Example Usage**

```php
<?php

// Assuming you have autoloading set up for your namespace

use Darkterminal\TursoHttp\core\Platform\AuditLogs;

// Replace 'your_api_token' with the actual API token you have
$apiToken = 'your_api_token';

// Replace 'your_organization_name', 10, and 1 with actual values
$organizationName = 'your_organization_name';
$page_size = 10;
$page = 1;

// Create an instance of AuditLogs with the provided API token
$auditLogs = new AuditLogs($apiToken);

// Example: List audit logs for a specific organization with pagination
$responseListAuditLogs = $auditLogs->list_audit_logs($organizationName, $page_size, $page)->get();
print_r($responseListAuditLogs);

// Example: Get the API response as a JSON string or array
$jsonResponse = $auditLogs->toJSON();
echo $jsonResponse;

?>
```

> Turso Audit Logs: https://docs.turso.tech/api-reference/audit-logs

### Database

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

### Groups

| Method                  | Parameters                                                            | Types                       | Description                                                                       |
|-------------------------|-----------------------------------------------------------------------|-----------------------------|-----------------------------------------------------------------------------------|
| `__construct`           | `$token`                                                      | `string`                    | Constructor for the `Groups` class, sets the API token.                           |
| `list`                  | `$organizationName`                                            | `string`                    | List groups for a specific organization.                                          |
| `create`                | `$organizationName`, `$groupName`, `$location = 'default'` | `string`, `string`, `string` | Create a new group with optional location parameter.                               |
| `get_group`            | `$organizationName`, `$groupName`                  | `string`, `string`           | Get information about a specific group.                                            |
| `delete`                | `$organizationName`, `$groupName`                  | `string`, `string`           | Delete a specific group.                                                           |
| `transfer`             | `$organizationName`, `$oldGroupName`, `$newGroupName` | `string`, `string`, `string` | Transfer a specific group to another organization.                                 |
| `add_location`         | `$organizationName`, `$groupName`, `$location_code` | `string`, `string`, `string` | Add a location to a specific group.                                                |
| `delete_location`      | `$organizationName`, `$groupName`, `$location_code` | `string`, `string`, `string` | Delete a location from a specific group.                                           |
| `update_version`       | `$organizationName`, `$groupName`                  | `string`, `string`           | Update the version of a specific group.                                           |
| `create_token`         | `$organizationName`, `$groupName`, `$expiration = 'never'`, `$authorization = 'read-only'` | `string`, `string`, `string`, `string` | Create an access token for a specific group with optional parameters.              |
| `invalidate_tokens`    | `$organizationName`, `$groupName`                  | `string`, `string`           | Invalidate access tokens for a specific group.                                    |
| `get`                 | -                                                                   | `array`                     | Get the API response as an array.                                                  |
| `toJSON`              | -                                                                   | `string` or `array` or `null`         | Get the API response as a JSON string, array, or null if not applicable.          |

**Example usage**

```php
<?php

// Assuming you have autoloading set up for your namespace

use Darkterminal\TursoHttp\core\Platform\Groups;

// Replace 'your_api_token', 'your_organization_name', and 'your_group_name' with actual values
$apiToken = 'your_api_token';
$organizationName = 'your_organization_name';
$groupName = 'your_group_name';

// Create an instance of Groups with the provided API token
$groups = new Groups($apiToken);

// Example: List groups for a specific organization
$responseListGroups = $groups->list($organizationName)->get();
print_r($responseListGroups);

// Example: Create a new group
$responseCreateGroup = $groups->create($organizationName, $groupName)->get();
print_r($responseCreateGroup);

// Example: Get information about a specific group
$responseGetGroup = $groups->get_group($organizationName, $groupName)->get();
print_r($responseGetGroup);

// Example: Delete a specific group
$responseDeleteGroup = $groups->delete($organizationName, $groupName)->get();
print_r($responseDeleteGroup);

// Example: Transfer a specific group to another organization
$newOrganizationName = 'new_organization_name';
$responseTransferGroup = $groups->transfer($organizationName, $groupName, $newOrganizationName)->get();
print_r($responseTransferGroup);

// Example: Add a location to a specific group
$locationCode = 'location_code';
$responseAddLocation = $groups->add_location($organizationName, $groupName, $locationCode)->get();
print_r($responseAddLocation);

// Example: Delete a location from a specific group
$responseDeleteLocation = $groups->delete_location($organizationName, $groupName, $locationCode)->get();
print_r($responseDeleteLocation);

// Example: Update the version of a specific group
$responseUpdateVersion = $groups->update_version($organizationName, $groupName)->get();
print_r($responseUpdateVersion);

// Example: Create an access token for a specific group
$responseCreateToken = $groups->create_token($organizationName, $groupName)->get();
print_r($responseCreateToken);

// Example: Invalidate access tokens for a specific group
$responseInvalidateTokens = $groups->invalidate_tokens($organizationName, $groupName)->get();
print_r($responseInvalidateTokens);

// Example: Get the API response as a JSON string or array
$jsonResponse = $groups->toJSON();
echo $jsonResponse;

?>
```

> Turso Groups: https://docs.turso.tech/api-reference/groups

### Locations

| Method                  | Parameters                                                            | Types                       | Description                                                                       |
|-------------------------|-----------------------------------------------------------------------|-----------------------------|-----------------------------------------------------------------------------------|
| `__construct`           | `$token`                                                      | `string`                    | Constructor for the `Locations` class, sets the API token.                         |
| `get_locations`         | -                                                                     | -                           | Get a list of available locations.                                                |
| `closest_region`        | -                                                                     | -                           | Get the closest region based on the user's location.                              |
| `get`                   | -                                                                     | `array`                     | Get the API response as an array.                                                  |
| `toJSON`                | -                                                                     | `string|array|null`         | Get the API response as a JSON string, array, or null if not applicable.          |

**Example usage**

```php
<?php

// Assuming you have autoloading set up for your namespace

use Darkterminal\TursoHttp\core\Platform\Locations;

// Replace 'your_api_token' with the actual API token
$apiToken = 'your_api_token';

// Create an instance of Locations with the provided API token
$locations = new Locations($apiToken);

// Example: Get a list of available locations
$responseGetLocations = $locations->get_locations()->get();
print_r($responseGetLocations);

// Example: Get the closest region based on the user's location
$responseClosestRegion = $locations->closest_region()->get();
print_r($responseClosestRegion);

// Example: Get the API response as a JSON string or array
$jsonResponse = $locations->toJSON();
echo $jsonResponse;

?>
```

> Turso Locations: https://docs.turso.tech/api-reference/locations

### Organizations

| Method                  | Parameters                                                            | Types                       | Description                                                                       |
|-------------------------|-----------------------------------------------------------------------|-----------------------------|-----------------------------------------------------------------------------------|
| `__construct`           | `$token`                                                      | `string`                    | Constructor for the `Organizations` class, sets the API token.                      |
| `list`                  | -                                                                     | -                           | Get a list of organizations.                                                        |
| `update`                | `$organizationName`, `$overages: bool = true`                  | `string`, `bool`            | Update organization details.                                                       |
| `members`               | `$organizationName`                                            | `string`                    | Get a list of members in an organization.                                          |
| `add_member`            | `$organizationName`, `$role`, `$username`      | `string`, `string`, `string` | Add a member to the organization.                                                  |
| `remove_member`         | `$organizationName`, `$username`                       | `string`, `string`          | Remove a member from the organization.                                             |
| `invite_lists`          | `$organizationName`                                            | `string`                    | Get the list of invite lists in the organization.                                  |
| `create_invite`         | `$organizationName`, `$role`, `$username`      | `string`, `string`, `string` | Create an invite in the organization.                                              |
| `get`                   | -                                                                     | `array`                     | Get the API response as an array.                                                  |
| `toJSON`                | -                                                                     | `string|array|null`         | Get the API response as a JSON string, array, or null if not applicable.          |

**Example usage**

```php
<?php

// Assuming you have autoloading set up for your namespace

use Darkterminal\TursoHttp\core\Platform\Organizations;

// Replace 'your_api_token' with the actual API token
$apiToken = 'your_api_token';

// Create an instance of Organizations with the provided API token
$organizations = new Organizations($apiToken);

// Example: Get a list of organizations
$responseGetOrganizations = $organizations->list()->get();
print_r($responseGetOrganizations);

// Example: Update organization details
$responseUpdateOrganization = $organizations->update('organization_name')->get();
print_r($responseUpdateOrganization);

// Example: Get a list of members in an organization
$responseGetMembers = $organizations->members('organization_name')->get();
print_r($responseGetMembers);

// Example: Add a member to the organization
$responseAddMember = $organizations->add_member('organization_name', 'member_role', 'member_username')->get();
print_r($responseAddMember);

// Example: Remove a member from the organization
$responseRemoveMember = $organizations->remove_member('organization_name', 'member_username')->get();
print_r($responseRemoveMember);

// Example: Get the list of invite lists in the organization
$responseGetInviteLists = $organizations->invite_lists('organization_name')->get();
print_r($responseGetInviteLists);

// Example: Create an invite in the organization
$responseCreateInvite = $organizations->create_invite('organization_name', 'invite_role', 'invite_username')->get();
print_r($responseCreateInvite);

// Example: Get the API response as a JSON string or array
$jsonResponse = $organizations->toJSON();
echo $jsonResponse;

?>
```

> Turso Organizations: https://docs.turso.tech/api-reference/organizations

## License

This library is licensed under the MIT License - see the [LICENSE](https://github.com/darkterminal/turso-http/blob/main/LICENSE) file for details.
