# API Token

API Token Platform API - PHP Wrapper

```php
final class APITokens implements Response
{
    public function __construct(string $token, string $organizationName) {}
    public function list(): APITokens {}
    public function create(string $tokenName): APITokens {}
    public function validate(): APITokens {}
    public function revoke(string $tokenName): APITokens {}
    public function get(): array {}
    public function toJSON(bool $pretty = false): string|array|null {}
}
```

## Usage

### Invites Platform API Instance

```php
<?php

// Assuming you have autoloading set up for your namespace
use Darkterminal\TursoHttp\core\Platform\APITokens;

$apiToken = 'your_api_token';
$organizationName = 'your_organization';

// Create an instance of Databases with the provided API token
$api = new APITokens($apiToken, $organizationName);
```

### List API Tokens

Returns a list of API tokens belonging to a user.

```php
$api = $api->list();

// Return as an array
print_r($api->get());
// Return as an object/json, pass true to pretty print
echo $api->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/tokens/list

### Create API Token

Returns a new API token belonging to a user.

```php
$api = $api->create('test-token');

// Return as an array
print_r($api->get());
// Return as an object/json, pass true to pretty print
echo $api->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/tokens/create

### Validate API Token

Validates an API token belonging to a user.

```php
$api = $api->validate();

// Return as an array
print_r($api->get());
// Return as an object/json, pass true to pretty print
echo $api->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/tokens/validate

### Revoke API Token

Revokes the provided API token belonging to a user.

```php
$api = $api->revoke('test-token');

// Return as an array
print_r($api->get());
// Return as an object/json, pass true to pretty print
echo $api->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/tokens/revoke

> Turso API Token: https://docs.turso.tech/api-reference/tokens
