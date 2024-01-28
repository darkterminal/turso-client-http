<?php

namespace Darkterminal\TursoHttp\core\Platform;

use Darkterminal\TursoHttp\core\Response;
use Darkterminal\TursoHttp\core\Utils;

/**
 * Class APITokens
 *
 * Represents an API Tokens management class.
 */
final class APITokens implements Response
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
     * APITokens constructor.
     *
     * @param string $token The API token used for authentication.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * List API tokens.
     *
     * @return APITokens Returns an instance of APITokens for method chaining.
     */
    public function list(): APITokens
    {
        $endpoint = Utils::useAPI('tokens', 'list');
        $this->response = Utils::makeRequest($endpoint['method'], $endpoint['url'], $this->token);
        return $this;
    }

    /**
     * Create a new API token.
     *
     * @param string $tokenName The name of the new API token.
     *
     * @return APITokens Returns an instance of APITokens for method chaining.
     */
    public function create(string $tokenName): APITokens
    {
        $endpoint = Utils::useAPI('tokens', 'create');
        $this->response = Utils::makeRequest($endpoint['method'], \str_replace('{tokenName}', $tokenName, $endpoint['url']), $this->token);
        return $this;
    }

    /**
     * Validate the API token.
     *
     * @return APITokens Returns an instance of APITokens for method chaining.
     */
    public function validate(): APITokens
    {
        $endpoint = Utils::useAPI('tokens', 'validate');
        $this->response = Utils::makeRequest($endpoint['method'], $endpoint['url'], $this->token);
        return $this;
    }

    /**
     * Revoke an API token.
     *
     * @param string $tokenName The name of the API token to revoke.
     *
     * @return APITokens Returns an instance of APITokens for method chaining.
     */
    public function revoke(string $tokenName): APITokens
    {
        $endpoint = Utils::useAPI('tokens', 'revoke');
        $this->response = Utils::makeRequest($endpoint['method'], \str_replace('{tokenName}', $tokenName, $endpoint['url']), $this->token);
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
