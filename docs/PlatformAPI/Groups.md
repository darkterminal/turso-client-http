# Groups

Groups Platform API - PHP Wrapper

```php
final class Groups implements Response
{
    public function __construct(string $token, string $organizationName) {}
    public function list(): Groups {}
    public function create(
        string $groupName,
        Location $location = Location::DEFAULT ,
        Extension|array $extensions = Extension::ALL
    ): Groups {}
    public function get_group(string $groupName): Groups {}
    public function delete(string $groupName): Groups {}
    public function add_location(string $groupName, Location $location): Groups {}
    public function delete_location(string $groupName, Location $location): Groups {}
    public function transfer(string $oldGroupName, string $organization): Groups {}
    public function unarchive(string $groupName): Groups {}
    public function update_version(string $groupName): Groups {}
    public function create_token(
        string $groupName,
        string $expiration = 'never',
        Authorization $authorization = Authorization::FULL_ACCESS
    ): Groups {}
    public function invalidate_tokens(string $groupName): Groups {}
    public function get(): array {}
    public function toJSON(bool $pretty = false): string|array|null {}
}
```

## Usage

### Groups Platform API Instance

```php
<?php

// Assuming you have autoloading set up for your namespace
use Darkterminal\TursoHttp\core\Platform\Groups;

$apiToken = 'your_api_token';
$organizationName = 'your_organization_name';

// Create an instance of Databases with the provided API token
$groups = new Groups($organizationName, $apiToken);
```

### List Groups

Returns a list of groups belonging to the organization or user.

```php
$lists = $groups->list();

// Return as an array
print_r($lists->get());
// Return as an object/json, pass true to pretty print
echo $lists->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/groups/list

### Create Group

Creates a new group for the organization or user.

```php
use Darkterminal\TursoHttp\core\Enums\Location;
use Darkterminal\TursoHttp\core\Enums\Extension;

// Create group with default closest region and all extension enables
$create = $groups->create('punk');
// Create group with selected region and all extension enables
$create = $groups->create('punk', Location::AMS);
// Create group with selected region and selected extensions enables
$create = $groups->create('punk', Location::AMS, [
    Extension::MATH,
    Extension::TEXT
]);

// Return as an array
print_r($create->get());
// Return as an object/json, pass true to pretty print
echo $create->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/groups/create

### Retrieve Group

Returns a group belonging to the organization or user.

```php
$group = $groups->get_group('punk');

// Return as an array
print_r($group->get());
// Return as an object/json, pass true to pretty print
echo $group->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/groups/retrieve

### Delete Group

Delete a group belonging to the organization or user.

```php
$delete = $groups->delete('punk');

// Return as an array
print_r($delete->get());
// Return as an object/json, pass true to pretty print
echo $delete->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/groups/delete

### Add Location to Group

Adds a location to the specified group.

```php
use Darkterminal\TursoHttp\core\Enums\Location;

$location = $groups->addLocation('punk', Location::AMS);

// Return as an array
print_r($location->get());
// Return as an object/json, pass true to pretty print
echo $location->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/groups/add-location

### Remove Location from Group

Removes a location from the specified group.

```php
use Darkterminal\TursoHttp\core\Enums\Location;

$location = $groups->deleteLocation('punk', Location::AMS);

// Return as an array
print_r($location->get());
// Return as an object/json, pass true to pretty print
echo $location->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/groups/remove-location

### Transfer Group

Transfer a group to another organization that you own or a member of.

```php
$transfer = $groups->transfer('punk', 'universe');

// Return as an array
print_r($transfer->get());
// Return as an object/json, pass true to pretty print
echo $transfer->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/groups/transfer

### Unarchive Group

Unarchive a group that has been archived due to inactivity.

```php
$unarchive = $groups->unarchive('punk');

// Return as an array
print_r($unarchive->get());
// Return as an object/json, pass true to pretty print
echo $unarchive->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/groups/unarchive

### Update Databases in a Group

Updates all databases in the group to the latest libSQL version.

```php
$update = $groups->updateVersion('punk');

// Return as an array
print_r($update->get());
// Return as an object/json, pass true to pretty print
echo $update->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/groups/update-database-versions

### Create Group Auth Token

Generates an authorization token for the specified group.

```php
use Darkterminal\TursoHttp\core\Enums\Authorization;

// Create group token that never expired with full-access permission
$update = $groups->createToken('punk');
// Create group token that will be expired in 2 week 1 day 30 minutes with full-access permission
$update = $groups->createToken('punk', '2w1d30m');
// Create group token that will be expired in 2 week 1 day 30 minutes with read-only permission
$update = $groups->createToken('punk', '2w1d30m', Authorization::READ_ONLY);

// Return as an array
print_r($update->get());
// Return as an object/json, pass true to pretty print
echo $update->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/groups/create-token

### Invalidate All Group Auth Tokens

Invalidates all authorization tokens for the specified group.

```php
$update = $groups->invalidateTokens('punk');

// Return as an array
print_r($update->get());
// Return as an object/json, pass true to pretty print
echo $update->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/groups/invalidate-tokens

> Turso Groups: https://docs.turso.tech/api-reference/groups
