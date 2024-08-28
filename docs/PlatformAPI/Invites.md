# Invites

Invites Platform API - PHP Wrapper

```php
final class Invites implements Response
{
    public function __construct(string $token, string $organizationName) {}
    public function list(): Invites {}
    public function createInvite(string $email, RoleType $role = RoleType::MEMBER): Invites {}
    public function deleteInvite(string $email): Invites {}
    public function get(): array {}
    public function toJSON(bool $pretty = false): string|array|null {}
}
```

## Usage

### Invites Platform API Instance

```php
<?php

// Assuming you have autoloading set up for your namespace
use Darkterminal\TursoHttp\core\Platform\Invites;

$apiToken = 'your_api_token';

// Create an instance of Databases with the provided API token
$invites = new Invites($apiToken);
```

### List Invites

Returns a list of invites for the organization.

```php
$invites = $invites->list();

// Return as an array
print_r($invites->get());
// Return as an object/json, pass true to pretty print
echo $invites->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/organizations/invites/list

### Create Invite

Invite a user (who isn’t already a Turso user) to an organization.

```php
use Darkterminal\TursoHttp\core\Enums\RoleType;

$invites = $invites->createInvite('darkterminal@quack.com', RoleType::ADMIN);

// Return as an array
print_r($invites->get());
// Return as an object/json, pass true to pretty print
echo $invites->toJSON(true) . PHP_EOL;
```

### Delete Invite

Delete an invite for the organization by email.

```php
$invites = $invites->deleteInvite('darkterminal@quack.com');

// Return as an array
print_r($invites->get());
// Return as an object/json, pass true to pretty print
echo $invites->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/organizations/invites/delete

> Turso Invites: https://docs.turso.tech/api-reference/invites
