<?php

namespace Darkterminal\TursoHttp\core\Platform;

use Darkterminal\TursoHttp\core\Enums\InvoiceType;
use Darkterminal\TursoHttp\core\LibSQLError;
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
        $organizations = Utils::makeRequest($endpoint['method'], $endpoint['url'], $this->token);

        if (isset($organizations['error'])) {
            throw new LibSQLError('Failed to get list of organizations', 'GET_ORGANIZATIONS_FAILED');
        }
        $this->response['list_organizations'] = $organizations;
        
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
        $update = Utils::makeRequest($endpoint['method'], $url, $this->token, ['overages' => $overages]);

        if (isset($update['error'])) {
            throw new LibSQLError($update['error'], 'UPDATE_ORGANIZATION_FAILED');
        }
        $this->response['update_organization'] = $update;

        return $this;
    }

    /**
     * Returns a list of available plans and their quotas.
     *
     * @param string $organizationName The name of the organization.
     *
     * @return Organizations Returns an instance of Organizations for method chaining.
     */
    public function plans(string $organizationName): Organizations
    {
        $endpoint = Utils::useAPI('organizations', 'plans');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $plans = Utils::makeRequest($endpoint['method'], $url, $this->token);

        if (isset($plans['error'])) {
            throw new LibSQLError($plans['error'], 'PLANS_ORGANIZATION_FAILED');
        }
        $this->response['plans_organization'] = $plans;

        return $this;
    }

    /**
     * Get the subscription details for an organization.
     *
     * @param string $organizationName The name of the organization.
     *
     * @return Organizations Returns an instance of Organizations for method chaining.
     */
    public function subscription(string $organizationName): Organizations
    {
        $endpoint = Utils::useAPI('organizations', 'subscription');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $subscription = Utils::makeRequest($endpoint['method'], $url, $this->token);

        if (isset($subscription['error'])) {
            throw new LibSQLError($subscription['error'], 'SUBSCRIPTION_ORGANIZATION_FAILED');
        }
        $this->response['subscription_organization'] = $subscription['subscription'];

        return $this;
    }

    /**
     * Get a list of invoices for an organization.
     *
     * @param string $organizationName The name of the organization.
     * @param InvoiceType $invoiceType The type of invoices to retrieve. Defaults to InvoiceType::ALL.
     *
     * @return Organizations Returns an instance of Organizations for method chaining.
     */
    public function invoices(string $organizationName, InvoiceType $invoiceType = InvoiceType::ALL): Organizations
    {
        $endpoint = Utils::useAPI('organizations', 'invoices');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $url = "?type={$invoiceType->value}";
        $invoices = Utils::makeRequest($endpoint['method'], $url, $this->token);

        if (isset($invoices['error'])) {
            throw new LibSQLError($invoices['error'], 'INVOICES_ORGANIZATION_FAILED');
        }
        $this->response['invoices_organization'] = empty($invoices) ? "There is no invoices for {$organizationName}" : $invoices;

        return $this;
    }

    /**
     * Get the current usage for an organization.
     *
     * @param string $organizationName The name of the organization.
     *
     * @return Organizations Returns an instance of Organizations for method chaining.
     */
    public function currentUsage(string $organizationName): Organizations
    {
        $endpoint = Utils::useAPI('organizations', 'current_usage');
        $url = \str_replace('{organizationName}', $organizationName, $endpoint['url']);
        $currentUsage = Utils::makeRequest($endpoint['method'], $url, $this->token);

        if (isset($currentUsage['error'])) {
            throw new LibSQLError($currentUsage['error'], 'CURRENTUSAGE_ORGANIZATION_FAILED');
        }
        $this->response['current_usage_organization'] = $currentUsage['organization'];

        return $this;
    }

    /**
     * Get the list of invite lists in the organization.
     *
     * @param string $organizationName The name of the organization.
     *
     * @return Organizations Returns an instance of Organizations for method chaining.
     */
    public function inviteLists(string $organizationName): Organizations
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
    public function createInvite(string $organizationName, string $role, string $username): Organizations
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
     * Returns the result of the previous operation.
     *
     * @return array|string The result of the previous operation
     */
    private function results(): array|string
    {
        return match (true) {
            isset($this->response['list_organizations']) => $this->response['list_organizations'],
            isset($this->response['update_organization']) => $this->response['update_organization'],
            isset($this->response['subscription_organization']) => $this->response['subscription_organization'],
            isset($this->response['invoices_organization']) => $this->response['invoices_organization'],
            isset($this->response['current_usage_organization']) => $this->response['current_usage_organization'],
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
