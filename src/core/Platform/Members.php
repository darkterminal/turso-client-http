<?php

namespace Darkterminal\TursoHttp\core\Platform;

use Darkterminal\TursoHttp\core\Enums\RoleType;
use Darkterminal\TursoHttp\core\LibSQLError;
use Darkterminal\TursoHttp\core\Response;
use Darkterminal\TursoHttp\core\Utils;

/**
 * Class Locations
 *
 * Represents a class for managing locations.
 */
final class Members implements Response
{
    /**
     * @var string The API token used for authentication.
     */
    protected string $token;

    /**
     * @var string The name of the organization.
     */
    protected string $organizationName;

    /**
     * @var mixed The response from the API request.
     */
    protected $response;

    /**
     * Locations constructor.
     *
     * @param string $token The API token used for authentication.
     */
    public function __construct(string $token, string $organizationName)
    {
        $this->token = $token;
        $this->organizationName = $organizationName;
    }

    /**
     * Get a list of members in an organization.
     *
     *
     * @return Members Returns an instance of Members for method chaining.
     */
    public function list(): Members
    {
        $endpoint = Utils::useAPI('members', 'members');
        $url = \str_replace('{organizationName}', $this->organizationName, $endpoint['url']);
        $members = Utils::makeRequest($endpoint['method'], $url, $this->token);

        if (isset($members['error'])) {
            throw new LibSQLError('Failed to get list of members', 'GET_MEMBERS_FAILED');
        }
        $this->response['list_members'] = $members['members'];

        return $this;
    }

    /**
     * Add a member to the organization.
     *
     * @param string $username The username of the member.
     * @param RoleType $role The role of the member.
     *
     * @return Members Returns an instance of Members for method chaining or throws an Exception on failure.
     */
    public function addMember(string $username, RoleType $role = RoleType::MEMBER): Members
    {
        $endpoint = Utils::useAPI('members', 'add_member');
        $url = \str_replace('{organizationName}', $this->organizationName, $endpoint['url']);
        Utils::validateMemberRole($role->value);
        $addMember = Utils::makeRequest($endpoint['method'], $url, $this->token, [
            'username' => $username,
            'role' => $role->value,
        ]);

        if (isset($addMember['error'])) {
            throw new LibSQLError($addMember['error'], 'ADD_MEMBER_FAILED');
        }
        $this->response['add_member'] = $addMember;

        return $this;
    }

    /**
     * Remove a member from the organization.
     *
     * @param string $username The username of the member to be removed.
     *
     * @return Members Returns an instance of Members for method chaining.
     */
    public function removeMember(string $username): Members
    {
        $endpoint = Utils::useAPI('members', 'remove_member');
        $url = \str_replace('{organizationName}', $this->organizationName, $endpoint['url']);
        $url = \str_replace('{username}', $username, $endpoint['url']);
        $remove = Utils::makeRequest($endpoint['method'], $url, $this->token);

        if (isset($remove['error'])) {
            $error = str_replace('{organizationName}', $this->organizationName, $remove['error']);
            throw new LibSQLError($error, 'REMOVE_MEMBER_FAILED');
        }
        $this->response['remove_member'] = $remove;

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
            isset($this->response['list_members']) => $this->response['list_members'],
            isset($this->response['add_member']) => $this->response['add_member'],
            isset($this->response['remove_member']) => $this->response['remove_member'],
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
