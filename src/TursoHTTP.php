<?php

namespace Darkterminal;

use Darkterminal\TursoHttp\core\Response;
use Darkterminal\TursoHttp\core\Utils;

class TursoHTTP implements Response
{
    /**
     * Turso HTTP Database URL
     *
     * @var string
     */
    private $baseURL;

    /**
     * Turso Database Auth Token
     *
     * @var string
     */
    private $authToken;

    /**
     * Turso HTTP Request Payload
     *
     * @var array
     */
    private $requestData;

    /**
     * Turso HTTP Response
     *
     * @var array|object
     */
    private $response;

    /**
     * Turso HTTP Instance
     *
     * @param string $databaseName your turso database name
     * @param string $organizationName your turso organization name
     * @param string $authToken your database Auth Token: turso db tokens create <database-name>
     */
    public function __construct(string $databaseName, string $organizationName, string $authToken)
    {
        $this->baseURL = "https://$databaseName-$organizationName.turso.io";
        $this->authToken = $authToken;
        $this->requestData = [
            'requests' => [],
        ];
        $this->response = [];
    }

    /**
     * Build request query statement
     *
     * @param string $type request type is only valid with "execute" (execute a statement on the connection.) or "close" (close the connection.)
     * @param string $stmt raw sql query
     * @param string $baton The baton is used to identify a connection with the server so that it can be reused
     *
     * @return TursoHTTP
     */
    public function addRequest(string $type, string $stmt = '', string $baton = '')
    {
        if (!in_array($type, ['execute', 'close'])) {
            throw new \Exception("Invalid request type. The valid types is only: execute and close", 1);
            exit;
        }

        $request = $type === 'close' ? ['type' => $type] : ['type' => $type, 'stmt' => ['sql' => $stmt]];
        if (!empty($baton)) {
            $this->requestData['baton'] = $baton;
        }
        $this->requestData['requests'][] = $request;
        return $this;
    }

    /**
     * Run query for Turso Database
     *
     * @return TursoHTTP
     */
    public function queryDatabase()
    {
        $url = $this->baseURL . '/v2/pipeline';
        $this->response = Utils::makeRequest('POST', $url, $this->authToken, $this->requestData);
        $this->resetRequestData();
        return $this;
    }

    /**
     * Return the full result database query in associative array
     *
     * @return array
     */
    public function get(): array
    {
        return $this->response;
    }

    /**
     * Return the full result database query in JSON
     *
     * @return void
     */
    public function toJSON(): string|array|null
    {
        return json_encode($this->response, true);
    }


    /**
     * Return only the baton (connection identifier)
     *
     * @return string|null
     */
    public function getBaton(): string|null
    {
        return isset($this->response['baton']) ? $this->response['baton'] : null;
    }

    /**
     * Return only the base url
     *
     * @return void
     */
    public function getBaseUrl(): string|null
    {
        return isset($this->response['base_url']) ? $this->response['base_url'] : null;
    }

    /**
     * The results for each of the requests made in the pipeline.
     *
     * @return array
     */
    public function getResults(): array
    {
        return isset($this->response['results']) ? $this->response['results'] : null;
    }

    /**
     * The list of columns for the returned rows.
     *
     * @return array
     */
    public function getCols(): array
    {
        $results = $this->getResults();
        return isset($results['cols']) ? $results['cols'] : null;
    }

    /**
     * The rows returned for the query.
     *
     * @return array
     */
    public function getRows(): array
    {
        $results = $this->getResults();
        return isset($results['rows']) ? $results['rows'] : null;
    }

    /**
     * The number of rows affected by the query.
     *
     * @return integer|null
     */
    public function getAffectedRowCount(): int|null
    {
        $results = $this->getResults();
        return isset($results['affected_row_count']) ? $results['affected_row_count'] : null;
    }

    /**
     * The ID of the last inserted row.
     *
     * @return integer|null
     */
    public function getLastInsertRowId(): int|null
    {
        $results = $this->getResults();
        return isset($results['last_insert_rowid']) ? $results['last_insert_rowid'] : null;
    }

    /**
     * The replication timestamp at which this query was executed.
     *
     * @return string|null
     */
    public function getReplicationIndex(): string|null
    {
        $results = $this->getResults();
        return isset($results['replication_index']) ? $results['replication_index'] : null;
    }

    /**
     * Reset the Request Data Value
     *
     * @return void
     */
    private function resetRequestData(): void
    {
        $this->requestData = ['requests' => []];
    }
}
