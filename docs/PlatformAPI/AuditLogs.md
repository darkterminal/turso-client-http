# API Token

API Token Platform API - PHP Wrapper

```php
final class AuditLogs implements Response
{
    public function __construct(string $token, string $organizationName) {}
    public function list(): AuditLogs {}
    public function get(): array {}
    public function toJSON(bool $pretty = false): string|array|null {}
}
```

## Usage

### Invites Platform API Instance

```php
<?php

// Assuming you have autoloading set up for your namespace
use Darkterminal\TursoHttp\core\Platform\AuditLogs;

$apiToken = 'your_api_token';
$organizationName = 'your_organization';

// Create an instance of Databases with the provided API token
$logs = new AuditLogs($apiToken, $organizationName);
```

### List Audit Logs

Return the audit logs for the given organization, ordered by the `created_at` field in descending order.

```php
$logs = $logs->list();
// List 20 logs per page, page 1
$logs = $logs->list(20, 1);

// Return as an array
print_r($logs->get());
// Return as an object/json, pass true to pretty print
echo $logs->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/audit-logs/list

> Turso Audit Logs: https://docs.turso.tech/api-reference/audit-logs
