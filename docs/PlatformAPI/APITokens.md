# API Token

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
