# Locations

| Method                  | Parameters                                                            | Types                       | Description                                                                       |
|-------------------------|-----------------------------------------------------------------------|-----------------------------|-----------------------------------------------------------------------------------|
| `__construct`           | `$token`                                                      | `string`                    | Constructor for the `Locations` class, sets the API token.                         |
| `get_locations`         | -                                                                     | -                           | Get a list of available locations.                                                |
| `closest_region`        | -                                                                     | -                           | Get the closest region based on the user's location.                              |
| `get`                   | -                                                                     | `array`                     | Get the API response as an array.                                                  |
| `toJSON`                | -                                                                     | `string|array|null`         | Get the API response as a JSON string, array, or null if not applicable.          |

**Example usage**

```php
<?php

// Assuming you have autoloading set up for your namespace

use Darkterminal\TursoHttp\core\Platform\Locations;

// Replace 'your_api_token' with the actual API token
$apiToken = 'your_api_token';

// Create an instance of Locations with the provided API token
$locations = new Locations($apiToken);

// Example: Get a list of available locations
$responseGetLocations = $locations->get_locations()->get();
print_r($responseGetLocations);

// Example: Get the closest region based on the user's location
$responseClosestRegion = $locations->closest_region()->get();
print_r($responseClosestRegion);

// Example: Get the API response as a JSON string or array
$jsonResponse = $locations->toJSON();
echo $jsonResponse;

?>
```

> Turso Locations: https://docs.turso.tech/api-reference/locations
