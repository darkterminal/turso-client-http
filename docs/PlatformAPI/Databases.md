# Databases

Databases Platform API - PHP Wrapper

```php
<?php

namespace Darkterminal\TursoHttp\core\Platform;

final class Databases implements Response
{
    public function __construct(string $token, string $organizationName) {}
    public function list(string $group = '', string $schema = ''): Databases {}
    public function create(
        string $databaseName,
        bool $isSchema = false,
        string $schema = '',
        string $group = 'default',
        string $size_limit = '',
        array $seed = []
    ): Databases {}
    public function create_parent_schema(string $databaseName): Databases {}
    public function create_child_schema(string $databaseName, string $parentSchema): Databases {}
    public function craete_child_schema_with_limit(string $databaseName, string $parentSchema, string $size_limit): Databases {}
    public function create_parent_schema_in_group(string $databaseName, string $group): Databases {}
    public function create_child_schema_in_group(string $databaseName, string $parentSchema, string $group): Databases {}
    public function create_child_schema_in_group_with_limit(string $databaseName, string $parentSchema, string $group, string $size_limit): Databases {}
    public function create_in_group(string $databaseName, string $group): Databases {}
    public function create_in_group_with_limit(string $databaseName, string $group, string $size_limit): Databases {}
    public function create_with_limit(string $databaseName, string $size_limit): Databases {}
    public function create_from_seed(string $databaseName, array $seed): Databases {}
    public function create_from_seed_in_group(string $databaseName, array $seed, string $group): Databases {}
    public function get_database(string $databaseName): Databases {}
    public function get_database_configuration(string $databaseName): Databases {}
    public function update_database_configuration(string $databaseName, array $configuration): Databases {}
    public function usage(string $databaseName): Databases {}
    public function stats(string $databaseName): Databases {}
    public function delete(string $databaseName): Databases {}
    public function list_instances(string $databaseName): Databases {}
    public function get_instance(string $databaseName, Location $instanceName): Databases {}
    public function create_token(
        string $databaseName,
        string $expiration = 'never',
        Authorization $autorization = Authorization::FULL_ACCESS,
        array $attach_databases = []
    ): Databases {}
    public function invalidate_tokens(string $databaseName): Databases {}
    public function upload_dump(string $filePath): Databases {}
    public function get(): array {}
    public function toJSON(bool $pretty = false): string|array|null {}
}
```

## Usage

### Databases Platform API Instance

```php
<?php

// Assuming you have autoloading set up for your namespace
use Darkterminal\TursoHttp\core\Platform\Databases;

$apiToken = 'your_api_token';
$organizationName = 'your_organization_name';
$databaseName = 'your_database_name';

// Create an instance of Databases with the provided API token
$databases = new Databases($apiToken, $organizationName);
```

### List Databases

Get a list of databases belonging to the organization or user.

```php
// Option: 1 Filter database by organization or user
$lists = $databases->list();
// Option: 2 Filter databases by organization or user, group name
$lists = $databases->list($groupName);
// Option: 3 Filter databases by organization or user, group name, and
// schema database name that can be used to get databases that belong to that parent schema
$lists = $databases->list($groupName, $schemaName);

// Return as an array
print_r($lists->get());
// Return as an object/json, pass true to pretty print
echo $lists->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/databases/list

### Create Database

Creates a new database in a group for the organization or user.

```php
// Option: 1 Create database in default group
$create = $databases->create($databaseName);
// Option: 2 Create database in default group and mark as Schema Database (parent)
$create = $databases->create($databaseName, true);
// Option: 3 Create database in default group and mark as Child database that relate with Parent Schema
$create = $databases->create($databaseName, true, $parentDatabase);
// Option: 4 Create database in default group and mark as Child database that relate with Parent Schema
$create = $databases->create($databaseName, true, $parentDatabase);
// Option: 5 Create database in default group and mark as Child database that relate with Parent Schema with size limit
// The maximum size of the database in bytes. Values with units are also accepted, e.g. 1mb, 256mb, 1gb
$create = $databases->create($databaseName, true, $parentDatabase, $sizeLimit);
// Option: 6 Create database in default group and mark as Child database that relate with Parent Schema with size limit
// The maximum size of the database in bytes. Values with units are also accepted, e.g. 1mb, 256mb, 1gb
$create = $databases->create($databaseName, true, $parentDatabase, $sizeLimit);
// Option: 7 Create database from seed in default group and mark as Child database that relate with Parent Schema with size limit
// The maximum size of the database in bytes. Values with units are also accepted, e.g. 1mb, 256mb, 1gb
$create = $databases->create($databaseName, true, $parentDatabase, $sizeLimit, $seedMetadata);

# Another shortcut that not short

$create = $databases->createInGroup($databaseName, $group);
$create = $databases->createInGroupWithLimit($databaseName, $group, $sizeLimit);
$create = $databases->createWithLimit($databaseName, $sizeLimit);
$create = $databases->createFromSeed($databaseName, $seedMetadata);
$create = $databases->createFromSeedInGroup($databaseName, $seedMetadata, $group);

# Multi-DB Schema related things

$create = $databases->createParentSchema($databaseName);
$create = $databases->createChildSchema($databaseName, $parentSchema);
$create = $databases->craeteChildSchemaWithLimit($databaseName, $parentSchema, $sizeLimit);
$create = $databases->createParentSchemaInGroup($databaseName, $group);
$create = $databases->createChildSchemaInGroup($databaseName, $parentSchema, $group);
$create = $databases->createChildSchemaInGroupWithLimit($databaseName, $parentSchema, $group, $sizeLimit);

// Return as an array
print_r($create->get());
// Return as an object/json, pass true to pretty print
echo $create->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/databases/create

### Retrieve Database

Returns a database belonging to the organization or user.

```php
$info = $databases->getDatabase($databaseName);

// Return as an array
print_r($info->get());
// Return as an object/json, pass true to pretty print
echo $info->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/databases/retrieve

### Retrieve Database Configuration

Retrieve an individual database configuration belonging to the organization or user.

```php
$config = $databases->getDatabaseConfiguration($databaseName);

// Return as an array
print_r($config->get());
// Return as an object/json, pass true to pretty print
echo $config->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/databases/configuration

### Update Database Configuration

Update a database configuration belonging to the organization or user.

```php
$configuration = [
    'allow_attach' => false, # bool
    'block_reads' => false, # bool
    'block_writes' => false, # bool
    # string: The maximum size of the database in bytes. Values with units are also accepted, e.g. 1mb, 256mb, 1gb
    'size_limit' => '',
];

$config = $databases->updateDatabaseConfiguration($databaseName, $configuration);

// Return as an array
print_r($config->get());
// Return as an object/json, pass true to pretty print
echo $config->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/databases/update-configuration

### Retrieve Database Usage

Fetch activity usage for a database in a given time period.

```php
$usage = $databases->usage($databaseName);

// Return as an array
print_r($usage->get());
// Return as an object/json, pass true to pretty print
echo $usage->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/databases/usage

### Retrieve Database Stats

Fetch the top queries of a database, including the count of rows read and written.

```php
$stats = $databases->stats($databaseName);

// Return as an array
print_r($stats->get());
// Return as an object/json, pass true to pretty print
echo $stats->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/databases/stats

### Delete Database

Delete a database belonging to the organization or user.

```php
$delete = $databases->delete($databaseName);

// Return as an array
print_r($delete->get());
// Return as an object/json, pass true to pretty print
echo $delete->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/databases/delete

### List Database Instances

Returns a list of instances of a database. Instances are the individual primary or replica databases in each region defined by the group.

```php
$instances = $databases->listInstances($databaseName);

// Return as an array
print_r($instances->get());
// Return as an object/json, pass true to pretty print
echo $instances->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/databases/list-instances

### Retrieve Database Instance

Return the individual database instance by name.

```php
use Darkterminal\TursoHttp\core\Enums\Location;

$instance = $databases->getInstance($databaseName, Location::SIN);

// Return as an array
print_r($instance->get());
// Return as an object/json, pass true to pretty print
echo $instance->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/databases/retrieve-instance

### Generate Database Auth Token

Generates an authorization token for the specified database.

```php
use Darkterminal\TursoHttp\core\Enums\Authorization;

// Option: 1 Create database auth token that never expired and have full-access
$token = $databases->createToken($databaseName);
// Option: 2 Create database auth token that will expired in 2w1d30m (2 week 1 day 30 minute) and have full-access
$token = $databases->createToken($databaseName, '2w1d30m');
// Option: 3 Create database auth token that will expired in 2w1d30m (2 week 1 day 30 minute) and have read-only
$token = $databases->createToken($databaseName, '2w1d30m', Authorization::READ_ONLY);
// Option: 4 Create database auth token that will expired in 2w1d30m (2 week 1 day 30 minute) and have full-access and permission to Read ATTACH lists of databases
$token = $databases->createToken($databaseName, '2w1d30m', Authorization::FULL_ACCESS, ['db1', 'db2']);

// Return as an array
print_r($token->get());
// Return as an object/json, pass true to pretty print
echo $token->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/databases/create-token

### Invalidate All Database Auth Tokens

Invalidates all authorization tokens for the specified database.

```php
$invalidate = $databases->invalidateTokens($databaseName);

// Return as an array
print_r($invalidate->get());
// Return as an object/json, pass true to pretty print
echo $invalidate->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/databases/invalidate-tokens

### Upload SQLite Dump

Upload a SQL dump to be used when [creating a new database](https://docs.turso.tech/api-reference/databases/create) from seed.

```php
$dumpFilePath = '/path/to/your/database/dump.sql';
$upload = $databases->uploadDump($dumpFilePath);

// Return as an array
print_r($upload->get());
// Return as an object/json, pass true to pretty print
echo $upload->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/databases/upload-dump

> Turso Databases: https://docs.turso.tech/api-reference/databases
