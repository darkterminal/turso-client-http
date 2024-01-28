<?php

namespace Darkterminal\TursoHttp\core\Platform;

use Darkterminal\TursoHttp\core\Response;
use Darkterminal\TursoHttp\core\Utils;

/**
 * Class Databases
 *
 * Represents a class for managing databases.
 */
final class Databases implements Response
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
     * Databases constructor.
     *
     * @param string $token The API token used for authentication.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * List databases for a specific organization.
     *
     * @param string $organizationName The name of the organization.
     *
     * @return Databases Returns an instance of Databases for method chaining.
     */
    public function list(string $organizationName): Databases
    {
        $endpoint = Utils::useAPI('databases', 'list');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token);
        return $this;
    }

    /**
     * Create a new database.
     *
     * @param string $organizationName The name of the organization.
     * @param string $databaseName The name of the new database.
     * @param string $group Optional. The group to which the database belongs (default: 'default').
     *
     * @return Databases Returns an instance of Databases for method chaining.
     */
    public function create(string $organizationName, string $databaseName, string $group = 'default'): Databases
    {
        $endpoint = Utils::useAPI('databases', 'create');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);

        $groups = new Groups($this->token);
        $list = $groups->list($organizationName)->get();

        if (empty($list['groups'])) {
            $created = $groups->create($organizationName, $group);
            $group = $created['group']['name'];
        }

        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token, [
            'name'  => $databaseName,
            'group' => $group
        ]);
        return $this;
    }

    /**
     * Get information about a specific database.
     *
     * @param string $organizationName The name of the organization.
     * @param string $databaseName The name of the database.
     *
     * @return Databases Returns an instance of Databases for method chaining.
     */
    public function get_database(string $organizationName, string $databaseName): Databases
    {
        $endpoint = Utils::useAPI('databases', 'retrive');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $url = \str_replace('{databaseName}', $databaseName, $url);
        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token);
        return $this;
    }

    /**
     * Get usage information for a specific database.
     *
     * @param string $organizationName The name of the organization.
     * @param string $databaseName The name of the database.
     *
     * @return Databases Returns an instance of Databases for method chaining.
     */
    public function usage(string $organizationName, string $databaseName): Databases
    {
        $endpoint = Utils::useAPI('databases', 'usage');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $url = \str_replace('{databaseName}', $databaseName, $url);
        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token);
        return $this;
    }

    /**
     * Delete a specific database.
     *
     * @param string $organizationName The name of the organization.
     * @param string $databaseName The name of the database.
     *
     * @return Databases Returns an instance of Databases for method chaining.
     */
    public function delete(string $organizationName, string $databaseName): Databases
    {
        $endpoint = Utils::useAPI('databases', 'delete');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $url = \str_replace('{databaseName}', $databaseName, $url);
        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token);
        return $this;
    }

    /**
     * List instances for a specific database.
     *
     * @param string $organizationName The name of the organization.
     * @param string $databaseName The name of the database.
     *
     * @return Databases Returns an instance of Databases for method chaining.
     */
    public function list_instances(string $organizationName, string $databaseName): Databases
    {
        $endpoint = Utils::useAPI('databases', 'list_instances');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $url = \str_replace('{databaseName}', $databaseName, $url);
        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token);
        return $this;
    }

    /**
     * Get information about a specific database instance.
     *
     * @param string $organizationName The name of the organization.
     * @param string $databaseName The name of the database.
     *
     * @return Databases Returns an instance of Databases for method chaining.
     */
    public function get_instance(string $organizationName, string $databaseName): Databases
    {
        $endpoint = Utils::useAPI('databases', 'get_instance');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $url = \str_replace('{databaseName}', $databaseName, $url);
        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token);
        return $this;
    }

    /**
     * Create an access token for a specific database.
     *
     * @param string $organizationName The name of the organization.
     * @param string $databaseName The name of the database.
     * @param string $expiration Optional. The expiration time of the access token (default: 'never').
     * @param string $autorization Optional. The authorization level of the access token (default: 'read-only').
     *
     * @return Databases Returns an instance of Databases for method chaining.
     */
    public function create_token(string $organizationName, string $databaseName, string $expiration = 'never', string $autorization = 'read-only'): Databases
    {
        $endpoint = Utils::useAPI('databases', 'create_token');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $url = \str_replace('{databaseName}', $databaseName, $url);
        $url = $url . "?" . \http_build_query([
            'expiration' => $expiration,
            'authorization' => $autorization
        ]);
        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token);
        return $this;
    }

    /**
     * Invalidate access tokens for a specific database.
     *
     * @param string $organizationName The name of the organization.
     * @param string $databaseName The name of the database.
     *
     * @return Databases Returns an instance of Databases for method chaining.
     */
    public function invalidate_tokens(string $organizationName, string $databaseName): Databases
    {
        $endpoint = Utils::useAPI('databases', 'invalidate_tokens');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $url = \str_replace('{databaseName}', $databaseName, $url);
        $this->response = Utils::makeRequest($endpoint['method'], $url, $this->token);
        return $this;
    }

    /**
     * Upload a database dump.
     *
     * @param string $organizationName The name of the organization.
     * @param string $filePath The path to the database dump file.
     *
     * @return Databases Returns an instance of Databases for method chaining.
     */
    public function upload_dump(string $organizationName, string $filePath): Databases
    {
        $endpoint = Utils::useAPI('databases', 'upload_dump');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $this->response = Utils::uploadDump($url, $this->token, $filePath);
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
