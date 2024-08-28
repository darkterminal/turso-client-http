<?php

namespace Darkterminal\TursoHttp\core\Platform;

use Darkterminal\TursoHttp\core\LibSQLError;
use Darkterminal\TursoHttp\core\Response;
use Darkterminal\TursoHttp\core\Utils;

/**
 * Class Locations
 *
 * Represents a class for managing locations.
 */
final class Locations implements Response
{
    /**
     * @var string The API token used for authentication.
     */
    protected string $token;

    /**
     * @var mixed The response from the API request.
     */
    protected $response;

    /**
     * Locations constructor.
     *
     * @param string $token The API token used for authentication.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get a list of available locations.
     *
     * @return Locations Returns an instance of Locations for method chaining.
     */
    public function getLocations(): Locations
    {
        $endpoint = Utils::useAPI('locations', 'list');
        $locations = Utils::makeRequest($endpoint['method'], $endpoint['url'], $this->token);

        if (!isset($locations['locations'])) {
            throw new LibSQLError('Failed to get list of locations', 'GET_LOCATIONS_FAILED');
        }
        $this->response['list_locations'] = $locations['locations'];

        return $this;
    }

    /**
     * Get the closest region based on the user's location.
     *
     * @return Locations Returns an instance of Locations for method chaining.
     */
    public function closestRegion(): Locations
    {
        $endpoint = Utils::useAPI('locations', 'closest_region');
        $closest = Utils::makeRequest($endpoint['method'], $endpoint['url'], $this->token);

        if (isset($closest['error'])) {
            throw new LibSQLError('Failed to get closest region', 'GET_CLOSEST_REGION_FAILED');
        }
        $this->response['closest_region'] = $closest;

        return $this;
    }

    /**
     * Returns the result of the previous operation.
     *
     * @return array|string The result of the previous operation
     */
    private function results(): array|string
    {
        return match (true) {
            isset($this->response['list_locations']) => $this->response['list_locations'],
            isset($this->response['closest_region']) => $this->response['closest_region'],
            default => $this->response,
        };
    }

    /**
     * Get the API response as an array.
     *
     * @return array The API response as an array.
     */
    public function get(): array
    {
        return $this->results();
    }

    /**
     * Get the API response as a JSON string or array.
     *
     * @param bool $pretty Whether to use pretty formatting.
     * @return string|array|null The API response as a JSON string, array, or null if not applicable.
     */
    public function toJSON(bool $pretty = false): string|array|null
    {
        if (!is_array($this->results())) {
            return $this->results();
        }

        return $pretty ? json_encode($this->results(), JSON_PRETTY_PRINT) : json_encode($this->results());
    }
}
