# Groups

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
