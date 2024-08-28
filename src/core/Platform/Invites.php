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
final class Invites implements Response
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

    public function list(): Invites
    {
        $endpoint = Utils::useAPI('invites', 'invite_lists');
        $url = \str_replace('{organizationName}', $this->organizationName, $endpoint['url']);
        $invites = Utils::makeRequest($endpoint['method'], $url, $this->token);

        if (isset($invites['error'])) {
            throw new LibSQLError('Failed to get list of invites', 'GET_INVITES_FAILED');
        }
        $this->response['list_invites'] = $invites['invites'];

        return $this;
    }

    public function createInvite(string $email, RoleType $role = RoleType::MEMBER): Invites
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new LibSQLError('Invalid email address', 'CREATE_INVITE_FAILED');
        }

        $endpoint = Utils::useAPI('invites', 'create_invite');
        $url = \str_replace('{organizationName}', $this->organizationName, $endpoint['url']);
        Utils::validateMemberRole($role->value);
        $createInvite = Utils::makeRequest($endpoint['method'], $url, $this->token, [
            'email' => $email,
            'role' => $role->value,
        ]);

        if (isset($createInvite['error'])) {
            throw new LibSQLError($createInvite['error'], 'CREATE_INVITE_FAILED');
        }
        $this->response['create_invite'] = $createInvite['invited'];

        return $this;
    }

    public function deleteInvite(string $email): Invites
    {
        $endpoint = Utils::useAPI('members', 'delete_invite');
        $url = \str_replace('{organizationName}', $this->organizationName, $endpoint['url']);
        $url = \str_replace('{email}', $email, $endpoint['url']);
        $remove = Utils::makeRequest($endpoint['method'], $url, $this->token);

        if (isset($remove['error'])) {
            throw new LibSQLError($remove['error'], 'DELETE_INVITE_FAILED');
        }
        $this->response['delete_invite'] = ["delete_invite" => "Email $email has been deleted"];

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
            isset($this->response['list_invites']) => $this->response['list_invites'],
            isset($this->response['create_invite']) => $this->response['create_invite'],
            isset($this->response['delete_invite']) => $this->response['delete_invite'],
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
