# Organizations

Organizations Platform API - PHP Wrapper

```php
final class Organizations implements Response
{
    public function __construct(string $token) {}
    public function list(): Organizations {}
    public function update(string $organizationName, bool $overages = true): Organizations {}
    public function plans(string $organizationName): Organizations {}
    public function subscription(string $organizationName): Organizations {}
    public function invoices(string $organizationName, InvoiceType $invoiceType = InvoiceType::ALL): Organizations {}
    public function currentUsage(string $organizationName): Organizations {}
    public function members(string $organizationName): Organizations {}
    public function addMember(string $organizationName, string $role, string $username): Organizations {}
    public function removeMember(string $organizationName, string $username): Organizations {}
    public function inviteLists(string $organizationName): Organizations {}
    public function createInvite(string $organizationName, string $role, string $username): Organizations {}
    public function get(): array {}
    public function toJSON(bool $pretty = false): string|array|null {}
}
```

## Usage

### Organization Platform API Instance

```php
<?php

// Assuming you have autoloading set up for your namespace
use Darkterminal\TursoHttp\core\Platform\Organization;

$apiToken = 'your_api_token';

// Create an instance of Databases with the provided API token
$organization = new Organization($apiToken);
```

### List Organizations

Returns a list of organizations the authenticated user owns or is a member of.

```php
$list = $organization->list();

// Return as an array
print_r($list->get());
// Return as an object/json, pass true to pretty print
echo $list->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/organizations/list

### Update Organization

Returns a list of organizations the authenticated user owns or is a member of.

```php
$update = $organization->update(getenv('ORG_NAME'));

// Return as an array
print_r($update->get());
// Return as an object/json, pass true to pretty print
echo $update->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/organizations/update

### List Plans

Returns a list of available plans and their quotas.

```php
$plans = $organization->plans(getenv('ORG_NAME'));

// Return as an array
print_r($plans->get());
// Return as an object/json, pass true to pretty print
echo $plans->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/organizations/plans

### Current Subscription

Returns the current subscription details for the organization.

```php
$subs = $organization->subscription(getenv('ORG_NAME'));

// Return as an array
print_r($subs->get());
// Return as an object/json, pass true to pretty print
echo $subs->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/organizations/subscription

### List Invoices

Returns a list of invoices for the organization.

```php
$invoices = $organization->invoices(getenv('ORG_NAME'));

// Return as an array
print_r($invoices->get());
// Return as an object/json, pass true to pretty print
echo $invoices->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/organizations/invoices

### Organization Usage

Fetch current billing cycle usage for an organization.

```php
$currentUsage = $organization->currentUsage(getenv('ORG_NAME'));

// Return as an array
print_r($currentUsage->get());
// Return as an object/json, pass true to pretty print
echo $currentUsage->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/organizations/usage

> Turso Organizations: https://docs.turso.tech/api-reference/organizations
