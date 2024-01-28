<?php

use Darkterminal\TursoHttp\core\Response;
use Darkterminal\TursoHttp\core\Utils;

/**
 * Class Organizations
 *
 * Represents a class for managing organizations.
 */
final class Organizations implements Response
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
     * Organizations constructor.
     *
     * @param string $token The API token used for authentication.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get a list of organizations.
     *
     * @return Organizations Returns an instance of Organizations for method chaining.
     */
    public function list(): Organizations
    {
        $endpoint = Utils::useAPI('organizations', 'list');
        $this->response = Utils::makeRequest($endpoint['method'], $endpoint['url'], $this->token);
        return $this;
    }

    /**
     * Update organization details.
     *
     * @param string $organizationName The name of the organization.
     * @param bool $overages Optional. Whether overages are allowed (default: true).
     *
     * @return Organizations Returns an instance of Organizations for method chaining.
     */
    public function update(string $organizationName, bool $overages = true): Organizations
    {
        $endpoint = Utils::useAPI('organizations', 'update');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token, ['overages' => $overages]);
        return $this;
    }

    /**
     * Get a list of members in an organization.
     *
     * @param string $organizationName The name of the organization.
     *
     * @return Organizations Returns an instance of Organizations for method chaining.
     */
    public function members(string $organizationName): Organizations
    {
        $endpoint = Utils::useAPI('organizations', 'members');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token);
        return $this;
    }

    /**
     * Add a member to the organization.
     *
     * @param string $organizationName The name of the organization.
     * @param string $role The role of the member.
     * @param string $username The username of the member.
     *
     * @return Organizations|Exception Returns an instance of Organizations for method chaining or throws an Exception on failure.
     */
    public function add_member(string $organizationName, string $role, string $username): Organizations|Exception
    {
        $endpoint = Utils::useAPI('organizations', 'add_member');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        Utils::validateMemberRole($role);
        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token, [
            'role' => $role,
            'username' => $username
        ]);
        return $this;
    }

    /**
     * Remove a member from the organization.
     *
     * @param string $organizationName The name of the organization.
     * @param string $username The username of the member to be removed.
     *
     * @return Organizations Returns an instance of Organizations for method chaining.
     */
    public function remove_member(string $organizationName, string $username): Organizations
    {
        $endpoint = Utils::useAPI('organizations', 'remove_member');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $url = \str_replace('{username}', $username, $endpoint['url']);
        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token);
        return $this;
    }

    /**
     * Get the list of invite lists in the organization.
     *
     * @param string $organizationName The name of the organization.
     *
     * @return Organizations Returns an instance of Organizations for method chaining.
     */
    public function invite_lists(string $organizationName): Organizations
    {
        $endpoint = Utils::useAPI('organizations', 'invite_lists');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token);
        return $this;
    }

    /**
     * Create an invite in the organization.
     *
     * @param string $organizationName The name of the organization.
     * @param string $role The role for the invited member.
     * @param string $username The username of the invited member.
     *
     * @return Organizations Returns an instance of Organizations for method chaining.
     */
    public function create_invite(string $organizationName, string $role, string $username): Organizations
    {
        $endpoint = Utils::useAPI('organizations', 'create_invite');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        Utils::validateUserRole($role);
        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token, [
            'role' => $role,
            'username' => $username
        ]);
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
