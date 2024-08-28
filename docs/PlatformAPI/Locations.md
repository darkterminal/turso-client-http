# Lcoations

Lcoations Platform API - PHP Wrapper

```php
final class Locations implements Response
{
    public function __construct(string $token) {}
    public function getLocations(): Locations {}
    public function closestRegion(): Locations {}
    public function get(): array {}
    public function toJSON(bool $pretty = false): string|array|null {}
}
```

## Usage

### Locations Platform API Instance

```php
<?php

// Assuming you have autoloading set up for your namespace
use Darkterminal\TursoHttp\core\Platform\Locations;

$apiToken = 'your_api_token';

// Create an instance of Databases with the provided API token
$locations = new Locations($apiToken);
```

### List Locations

Returns a list of locations where you can create or replicate databases.

```php
$locations = $locations->getLocations();

// Return as an array
print_r($locations->get());
// Return as an object/json, pass true to pretty print
echo $locations->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/locations/list

### Closest Region

Returns the closest region to the user’s location.

```php
$locations = $locations->closestRegion();

// Return as an array
print_r($locations->get());
// Return as an object/json, pass true to pretty print
echo $locations->toJSON(true) . PHP_EOL;
```

Ref: https://docs.turso.tech/api-reference/locations/closest-region

> Turso Locations: https://docs.turso.tech/api-reference/locations
