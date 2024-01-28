<?php

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
    public function get_locations(): Locations
    {
        $endpoint = Utils::useAPI('locations', 'list');
        $this->response = Utils::makeRequest($endpoint['method'], $endpoint['url'], $this->token);
        return $this;
    }

    /**
     * Get the closest region based on the user's location.
     *
     * @return Locations Returns an instance of Locations for method chaining.
     */
    public function closest_region(): Locations
    {
        $endpoint = Utils::useAPI('location', 'closest_region');
        $this->response = Utils::makeRequest($endpoint['method'], $endpoint['url'], $this->token);
        return $this;
    }

    /**
     * Get the API response as an array.
     *
     * @return array The API response as an array.
     */
    public function get(): array
    {
        return $this->response;
    }

    /**
     * Get the API response as a JSON string or array.
     *
     * @return string|array|null The API response as a JSON string, array, or null if not applicable.
     */
    public function toJSON(): string|array|null
    {
        return json_encode($this->response, true);
    }
}
