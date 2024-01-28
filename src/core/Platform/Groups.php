<?php

namespace Darkterminal\TursoHttp\core\Platform;

use Darkterminal\TursoHttp\core\Response;
use Darkterminal\TursoHttp\core\Utils;

/**
 * Class Groups
 *
 * Represents a class for managing groups.
 */
final class Groups implements Response
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
     * Groups constructor.
     *
     * @param string $token The API token used for authentication.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * List groups for a specific organization.
     *
     * @param string $organizationName The name of the organization.
     *
     * @return Groups Returns an instance of Groups for method chaining.
     */
    public function list(string $organizationName): Groups
    {
        $endpoint = Utils::useAPI('groups', 'list');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token);
        return $this;
    }

    /**
     * Create a new group.
     *
     * @param string $organizationName The name of the organization.
     * @param string $groupName The name of the new group.
     * @param string $location Optional. The location of the group (default: 'default').
     *
     * @return Groups Returns an instance of Groups for method chaining.
     */
    public function create(string $organizationName, string $groupName, string $location = 'default'): Groups
    {
        $endpoint = Utils::useAPI('groups', 'create');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);

        if ($location === 'default') {
            $closestRegion = Utils::closestRegion($this->token);
            $location = $closestRegion['server'];
        }

        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token, [
            'name'  => $groupName,
            'location' => $location
        ]);

        return $this;
    }

    /**
     * Get information about a specific group.
     *
     * @param string $organizationName The name of the organization.
     * @param string $groupName The name of the group.
     *
     * @return Groups Returns an instance of Groups for method chaining.
     */
    public function get_group(string $organizationName, string $groupName): Groups
    {
        $endpoint = Utils::useAPI('groups', 'get_group');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $url = \str_replace('{groupName}', $groupName, $url);
        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token);
        return $this;
    }

    /**
     * Delete a specific group.
     *
     * @param string $organizationName The name of the organization.
     * @param string $groupName The name of the group.
     *
     * @return Groups Returns an instance of Groups for method chaining.
     */
    public function delete(string $organizationName, string $groupName): Groups
    {
        $endpoint = Utils::useAPI('groups', 'delete');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $url = \str_replace('{groupName}', $groupName, $url);
        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token);
        return $this;
    }

    /**
     * Transfer a specific group to another organization.
     *
     * @param string $organizationName The name of the current organization.
     * @param string $oldGroupName The name of the group to be transferred.
     * @param string $newGroupName The name of the target organization.
     *
     * @return Groups Returns an instance of Groups for method chaining.
     */
    public function transfer(string $organizationName, string $oldGroupName, string $newGroupName): Groups
    {
        $endpoint = Utils::useAPI('groups', 'transfer');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $url = \str_replace('{groupName}', $oldGroupName, $url);
        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token, ['organization' => $newGroupName]);
        return $this;
    }

    /**
     * Add a location to a specific group.
     *
     * @param string $organizationName The name of the organization.
     * @param string $groupName The name of the group.
     * @param string $location_code The code of the location to be added.
     *
     * @return Groups Returns an instance of Groups for method chaining.
     */
    public function add_location(string $organizationName, string $groupName, string $location_code): Groups
    {
        $endpoint = Utils::useAPI('groups', 'add_location');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $url = \str_replace('{groupName}', $groupName, $url);
        $url = \str_replace('{location}', $location_code, $url);
        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token);
        return $this;
    }

    /**
     * Delete a location from a specific group.
     *
     * @param string $organizationName The name of the organization.
     * @param string $groupName The name of the group.
     * @param string $location_code The code of the location to be deleted.
     *
     * @return Groups Returns an instance of Groups for method chaining.
     */
    public function delete_location(string $organizationName, string $groupName, string $location_code): Groups
    {
        $endpoint = Utils::useAPI('groups', 'delete_location');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $url = \str_replace('{groupName}', $groupName, $url);
        $url = \str_replace('{location}', $location_code, $url);
        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token);
        return $this;
    }

    /**
     * Update the version of a specific group.
     *
     * @param string $organizationName The name of the organization.
     * @param string $groupName The name of the group.
     *
     * @return Groups Returns an instance of Groups for method chaining.
     */
    public function update_version(string $organizationName, string $groupName): Groups
    {
        $endpoint = Utils::useAPI('groups', 'update_version');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $url = \str_replace('{groupName}', $groupName, $url);
        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token);
        return $this;
    }

    /**
     * Create an access token for a specific group.
     *
     * @param string $organizationName The name of the organization.
     * @param string $groupName The name of the group.
     * @param string $expiration Optional. The expiration time for the access token (default: 'never').
     * @param string $authorization Optional. The authorization level for the access token (default: 'read-only').
     *
     * @return Groups Returns an instance of Groups for method chaining.
     */
    public function create_token(string $organizationName, string $groupName, string $expiration = 'never', string $authorization = 'read-only'): Groups
    {
        $endpoint = Utils::useAPI('groups', 'create_token');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $url = \str_replace('{groupName}', $groupName, $url);
        $url = $url . "?" . \http_build_query([
            'expiration' => $expiration,
            'authorization' => $authorization
        ]);
        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token);
        return $this;
    }

    /**
     * Invalidate access tokens for a specific group.
     *
     * @param string $organizationName The name of the organization.
     * @param string $groupName The name of the group.
     *
     * @return Groups Returns an instance of Groups for method chaining.
     */
    public function invalidate_tokens(string $organizationName, string $groupName): Groups
    {
        $endpoint = Utils::useAPI('groups', 'invalidate_tokens');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $url = \str_replace('{groupName}', $groupName, $url);
        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token);
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
