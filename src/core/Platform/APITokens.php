<?php

namespace Darkterminal\TursoHttp\core\Platform;

use Darkterminal\TursoHttp\core\LibSQLError;
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
        $tokens = Utils::makeRequest($endpoint['method'], $endpoint['url'], $this->token);

        if (isset($tokens['error'])) {
            throw new LibSQLError('Failed to get list of API tokens', 'GET_API_TOKENS_FAILED');
        }
        $this->response['list_tokens'] = $tokens['tokens'];

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
        $create = Utils::makeRequest($endpoint['method'], \str_replace('{tokenName}', $tokenName, $endpoint['url']), $this->token);

        if (isset($create['error'])) {
            throw new LibSQLError($create['error'], 'CREATE_API_TOKEN_FAILED');
        }
        $this->response['create_token'] = $create;

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
        $validate = Utils::makeRequest($endpoint['method'], $endpoint['url'], $this->token);

        if (isset($validate['error'])) {
            throw new LibSQLError($validate['error'], 'VALIDATE_API_TOKEN_FAILED');
        }
        $this->response['token_validate'] = $validate;

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
        $revoke = Utils::makeRequest($endpoint['method'], \str_replace('{tokenName}', $tokenName, $endpoint['url']), $this->token);

        if (isset($revoke['error'])) {
            throw new LibSQLError($revoke['error'], 'REVOKE_API_TOKEN_FAILED');
        }
        $this->response['revoke_token'] = $revoke['token'];

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
            isset($this->response['list_tokens']) => $this->response['list_tokens'],
            isset($this->response['create_token']) => $this->response['create_token'],
            isset($this->response['token_validate']) => $this->response['token_validate'],
            isset($this->response['revoke_token']) => $this->response['revoke_token'],
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
