<?php

namespace Darkterminal\TursoHttp\core\Platform;

use Darkterminal\TursoHttp\core\Response;
use Darkterminal\TursoHttp\core\Utils;

/**
 * Class AuditLogs
 *
 * Represents a class for retrieving audit logs.
 */
final class AuditLogs implements Response
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
     * AuditLogs constructor.
     *
     * @param string $token The API token used for authentication.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * List audit logs for a specific organization.
     *
     * @param string $organizationName The name of the organization.
     * @param int $page_size The number of items per page.
     * @param int $page The page number.
     *
     * @return AuditLogs Returns an instance of AuditLogs for method chaining.
     */
    public function list_audit_logs(string $organizationName, int $page_size, int $page): AuditLogs
    {
        $endpoint = Utils::useAPI('organizations', 'members');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $url = $url . "?" . \http_build_query([
            'page_size' => $page_size,
            'page' => $page
        ]);
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
