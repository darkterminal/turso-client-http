# Organizations

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
