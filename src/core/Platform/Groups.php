<?php
declare(strict_types=1);

namespace Darkterminal\TursoHttp\core\Platform;

use Darkterminal\TursoHttp\core\Enums\Authorization;
use Darkterminal\TursoHttp\core\Enums\Extension;
use Darkterminal\TursoHttp\core\Enums\Location;
use Darkterminal\TursoHttp\core\LibSQLError;
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
     * @var string The name of the organization.
     */
    protected string $organizationName;

    /**
     * @var mixed The response from the API request.
     */
    protected $response;

    /**
     * Groups constructor.
     * 
     * @param string $token The API token used for authentication.
     * @param string $organizationName The name of the organization.
     */
    public function __construct(string $token, string $organizationName)
    {
        $this->token = $token;
        $this->organizationName = $organizationName;
    }

    /**
     * List groups for a specific organization.
     * 
     * @return Groups Returns an instance of Groups for method chaining.
     */
    public function list(): Groups
    {
        $endpoint = Utils::useAPI('groups', 'list');
        $url = \str_replace('{organizationName}', $this->organizationName, $endpoint['url']);
        $groups = Utils::makeRequest($endpoint['method'], $url, $this->token);

        if (empty($groups['groups'])) {
            throw new LibSQLError('No groups found', 'GROUP_NOT_FOUND');
        }
        $this->response['list_groups'] = $groups['groups'];

        return $this;
    }

    /**
     * Create a new group.
     *
     * @param string $groupName The name of the new group.
     * @param Location $location Optional. The location of the group (default: 'default').
     * @param Extension|array<Extension> $extensions Optional. The extensions to enable for new databases created in this group. 
     *                                               Users looking to enable vector extensions should instead use the native [libSQL vector datatype](https://docs.turso.tech/features/ai-and-embeddings).
     *
     * @return Groups Returns an instance of Groups for method chaining.
     */
    public function create(
        string $groupName,
        Location $location = Location::DEFAULT ,
        Extension|array $extensions = Extension::ALL
    ): Groups {
        $endpoint = Utils::useAPI('groups', 'create');
        $url = \str_replace('{organizationName}', $this->organizationName, $endpoint['url']);

        if ($location->value === 'default') {
            $closestRegion = Utils::closestRegion($this->token);
            $location = $closestRegion['server'];
        }

        $body = [
            'name' => $groupName,
            'location' => $location,
            'extensions' => is_array($extensions) ? array_map(fn($extension) => $extension->value, $extensions) : $extensions->value
        ];

        $create = Utils::makeRequest($endpoint['method'], $url, $this->token, $body);

        if (isset($create['error'])) {
            throw new LibSQLError($create['error'], 'ERR_CREATING_GROUP');
        }

        $this->response['create_group'] = $create['group'];

        return $this;
    }

    /**
     * Get information about a specific group.
     *
     * @param string $groupName The name of the group.
     *
     * @return Groups Returns an instance of Groups for method chaining.
     */
    public function getGroup(string $groupName): Groups
    {
        $endpoint = Utils::useAPI('groups', 'get_group');
        $url = \str_replace('{organizationName}', $this->organizationName, $endpoint['url']);
        $url = \str_replace('{groupName}', $groupName, $url);
        $group = Utils::makeRequest($endpoint['method'], $url, $this->token);

        if (!isset($group['group'])) {
            throw new LibSQLError($group['error'], 'GROUP_NOT_FOUND');
        }
        $this->response['single_group'] = $group['group'];

        return $this;
    }

    /**
     * Delete a specific group.
     *
     * @param string $groupName The name of the group.
     *
     * @return Groups Returns an instance of Groups for method chaining.
     */
    public function delete(string $groupName): Groups
    {
        $endpoint = Utils::useAPI('groups', 'delete');
        $url = \str_replace('{organizationName}', $this->organizationName, $endpoint['url']);
        $url = \str_replace('{groupName}', $groupName, $url);
        $delete = Utils::makeRequest($endpoint['method'], $url, $this->token);

        if (!isset($delete['group'])) {
            throw new LibSQLError($delete['error'], 'GROUP_DELETION_FAILED');
        }
        $this->response['deleted_group'] = $delete['group'];

        return $this;
    }

    /**
     * Add a location to a specific group.
     *
     * @param string $groupName The name of the group.
     * @param Location $location The code of the location to be added.
     *
     * @return Groups Returns an instance of Groups for method chaining.
     */
    public function addLocation(string $groupName, Location $location): Groups
    {
        $endpoint = Utils::useAPI('groups', 'add_location');
        $url = \str_replace('{organizationName}', $this->organizationName, $endpoint['url']);
        $url = \str_replace('{groupName}', $groupName, $url);
        $url = \str_replace('{location}', $location->value, $url);
        $location = Utils::makeRequest($endpoint['method'], $url, $this->token);

        if (!isset($location['group'])) {
            throw new LibSQLError($location['error'], 'LOCATION_ADDITION_FAILED');
        }
        $this->response['added_location'] = $location['group'];

        return $this;
    }

    /**
     * Delete a location from a specific group.
     *
     * @param string $groupName The name of the group.
     * @param Location $location The code of the location to be deleted.
     *
     * @return Groups Returns an instance of Groups for method chaining.
     */
    public function deleteLocation(string $groupName, Location $location): Groups
    {
        $endpoint = Utils::useAPI('groups', 'delete_location');
        $url = \str_replace('{organizationName}', $this->organizationName, $endpoint['url']);
        $url = \str_replace('{groupName}', $groupName, $url);
        $url = \str_replace('{location}', $location->value, $url);
        $location = Utils::makeRequest($endpoint['method'], $url, $this->token);

        if (!isset($location['group'])) {
            throw new LibSQLError($location['error'], 'LOCATION_ADDITION_FAILED');
        }
        $this->response['delete_location'] = $location['group'];

        return $this;
    }

    /**
     * Transfer a specific group to another organization.
     *
     * @param string $oldGroupName The name of the group to be transferred.
     * @param string $organization The name of the organization to transfer the group to.
     *
     * @return Groups Returns an instance of Groups for method chaining.
     */
    public function transfer(string $oldGroupName, string $organization): Groups
    {
        $endpoint = Utils::useAPI('groups', 'transfer');
        $url = \str_replace('{organizationName}', $this->organizationName, $endpoint['url']);
        $url = \str_replace('{groupName}', $oldGroupName, $url);
        $transfer = Utils::makeRequest($endpoint['method'], $url, $this->token, ['organization' => $organization]);

        if (isset($transfer['error'])) {
            throw new LibSQLError($transfer['error'], 'GROUP_TRANSFER_FAILED');
        }
        $this->response['transfer_group'] = $transfer;

        return $this;
    }

    /**
     * Unarchive Group
     * 
     * Unarchive a group that has been archived due to inactivity.
     * 
     * Databases get archived after 10 days of inactivity for users on a free plan — [learn more](https://docs.turso.tech/features/scale-to-zero). You can unarchive inactive groups using the API.
     *
     * @param string $groupName The name of the group.
     *
     * @return Groups Returns an instance of Groups for method chaining.
     */
    public function unarchive(string $groupName): Groups
    {
        $endpoint = Utils::useAPI('groups', 'unarchive');
        $url = \str_replace('{organizationName}', $this->organizationName, $endpoint['url']);
        $url = \str_replace('{groupName}', $groupName, $url);
        $unarchive = Utils::makeRequest($endpoint['method'], $url, $this->token);

        if (!isset($unarchive['group'])) {
            throw new LibSQLError($unarchive['error'], 'GROUP_UNARCHIVE_FAILED');
        }
        $this->response['unarchive_group'] = $unarchive['group'];

        return $this;
    }

    /**
     * Update the version of a specific group.
     *
     * @param string $groupName The name of the group.
     *
     * @return Groups Returns an instance of Groups for method chaining.
     */
    public function updateVersion(string $groupName): Groups
    {
        $endpoint = Utils::useAPI('groups', 'update_version');
        $url = \str_replace('{organizationName}', $this->organizationName, $endpoint['url']);
        $url = \str_replace('{groupName}', $groupName, $url);
        $update = Utils::makeRequest($endpoint['method'], $url, $this->token);

        if (isset($update['error'])) {
            throw new LibSQLError($update['error'], 'GROUP_UPDATE_FAILED');
        }
        $this->response['update_group'] = ['update' => 'This operation causes some amount of downtime to occur during the update process. The version of libSQL server is taken from the latest built docker image.'];

        return $this;
    }

    /**
     * Create an access token for a specific group.
     *
     * @param string $groupName The name of the group.
     * @param string $expiration Optional. The expiration time for the access token (default: 'never').
     * @param Authorization $authorization Optional. The authorization level for the access token (default: 'full-access').
     *
     * @return Groups Returns an instance of Groups for method chaining.
     */
    public function createToken(
        string $groupName,
        string $expiration = 'never',
        Authorization $authorization = Authorization::FULL_ACCESS
    ): Groups {
        $endpoint = Utils::useAPI('groups', 'create_token');
        $url = \str_replace('{organizationName}', $this->organizationName, $endpoint['url']);
        $url = \str_replace('{groupName}', $groupName, $url);
        $url = $url . "?" . \http_build_query([
            'expiration' => $expiration,
            'authorization' => $authorization->value
        ]);

        $body = [];
        if (!empty($attach_databases)) {
            array_push($body, [
                'permissions' => [
                    'read_attach' => [
                        'databases' => $attach_databases
                    ]
                ]
            ]);
        }

        $token = Utils::makeRequest($endpoint['method'], $url, $this->token, $body);
        if (!isset($token['jwt'])) {
            throw new LibSQLError($token['error'], 'GROUP_TOKEN_CREATION_FAILED');
        }
        $this->response['created_token_group'] = $token['jwt'];

        return $this;
    }

    /**
     * Invalidate access tokens for a specific group.
     *
     * @param string $groupName The name of the group.
     *
     * @return Groups Returns an instance of Groups for method chaining.
     */
    public function invalidateTokens(string $groupName): Groups
    {
        $endpoint = Utils::useAPI('groups', 'invalidate_tokens');
        $url = \str_replace('{organizationName}', $this->organizationName, $endpoint['url']);
        $url = \str_replace('{groupName}', $groupName, $url);
        $invalidate = Utils::makeRequest($endpoint['method'], $url, $this->token);

        if (isset($invalidate['error'])) {
            throw new LibSQLError($invalidate['error'], 'GROUP_TOKEN_INVALIDATION_FAILED');
        }
        $this->response['invalidated_token_group'] = ['invalidate_token' => 'All access tokens for this group have been invalidated.'];

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
            isset($this->response['list_groups']) => $this->response['list_groups'],
            isset($this->response['create_group']) => $this->response['create_group'],
            isset($this->response['single_group']) => $this->response['single_group'],
            isset($this->response['deleted_group']) => $this->response['deleted_group'],
            isset($this->response['added_location']) => $this->response['added_location'],
            isset($this->response['delete_location']) => $this->response['delete_location'],
            isset($this->response['transfer_group']) => $this->response['transfer_group'],
            isset($this->response['unarchive_group']) => $this->response['unarchive_group'],
            isset($this->response['update_group']) => $this->response['update_group'],
            isset($this->response['created_token_group']) => $this->response['created_token_group'],
            isset($this->response['invalidated_token_group']) => $this->response['invalidated_token_group'],
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
