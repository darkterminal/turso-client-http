# Members

Members Platform API - PHP Wrapper

```php
final class Members implements Response
{
    public function __construct(string $token, string $organizationName) {}
    public function list(): Members {}
    public function addMember(string $username, RoleType $role = RoleType::MEMBER): Members {}
    public function removeMember(string $username): Members {}
    public function get(): array {}
    public function toJSON(bool $pretty = false): string|array|null {}
}
```

## Usage

### Members Platform API Instance

```php
<?php

// Assuming you have autoloading set up for your namespace
use Darkterminal\TursoHttp\core\Platform\Members;

$apiToken = 'your_api_token';

// Create an instance of Databases with the provided API token
$members = new Members($apiToken);
```

### List Members

Returns a list of members part of the organization.

```php
$members = $members->list();

// Return as an array
print_r($members->get());
// Return as an object/json, pass true to pretty print
echo $members->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/organizations/members/list

### Add Member

Add an existing Turso user to an organization.

```php
use Darkterminal\TursoHttp\core\Enums\RoleType;

$members = $members->addMember('notrab', RoleType::ADMIN);

// Return as an array
print_r($members->get());
// Return as an object/json, pass true to pretty print
echo $members->toJSON(true) . PHP_EOL;
```

### Remove Member

Remove a user from the organization by username.

```php
$members = $members->removeMember('notrab');

// Return as an array
print_r($members->get());
// Return as an object/json, pass true to pretty print
echo $members->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/organizations/members/remove

> Turso Members: https://docs.turso.tech/api-reference/members
