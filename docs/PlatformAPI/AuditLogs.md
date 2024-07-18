# Audit Logs

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
