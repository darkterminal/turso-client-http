<?php

namespace Darkterminal\TursoHttp;

use Darkterminal\TursoHttp\core\Http\LibSQLResult;
use Darkterminal\TursoHttp\core\Http\LibSQLStatement;
use Darkterminal\TursoHttp\core\Http\LibSQLTransaction;
use Darkterminal\TursoHttp\core\LibSQLError;
use Darkterminal\TursoHttp\core\Request;
use Darkterminal\TursoHttp\core\Utils;

class LibSQL
{
    /**
     * Return associative array.
     */
    public const int LIBSQL_ASSOC = 1;

    /**
     * Return numerical array
     */
    public const int LIBSQL_NUM = 2;

    /**
     * Return both associative and numerical array
     */
    public const int LIBSQL_BOTH = 3;

    /**
     * Return a result sets
     */
    public const int LIBSQL_ALL = 4;

    /**
     * Turso HTTP Database URL
     *
     * @var string
     */
    protected $baseURL;

    /**
     * Turso Database Auth Token
     *
     * @var string
     */
    protected $authToken;

    protected Request $http;

    protected int $affected_rows = 0;
    public bool $auto_commit = true;

    /**
     * LibSQL HTTP Instance
     *
     * @param string $dsn your dsn config
     */
    public function __construct(string $dsn)
    {
        $database = Utils::parseDsn($dsn);
        $this->baseURL = str_replace('libsql://', 'https://', $database['dbname']);
        $this->authToken = $database['authToken'];
        $this->http = new Request($this->baseURL, $this->authToken);
    }

    public function getBaseUrl(): string
    {
        return $this->baseURL;
    }

    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    public function getConnection(): Request
    {
        return $this->http;
    }

    public function version(): string
    {
        $response = Utils::makeRequest('GET', "{$this->baseURL}/version", $this->authToken);
        return $response;
    }

    public function query(string $sql, array $parameters = []): LibSQLResult
    {
        $results = $this->http->prepareRequest($sql, $parameters)->executeRequest()->get();
        $this->setChanges($results);
        return new LibSQLResult($results);
    }

    public function execute(string $sql, array $parameters = []): int
    {
        $res = $this->http->prepareRequest($sql, $parameters)->executeRequest()->get();
        $result = Utils::removeCloseResponses($res['results']);
        $this->setChanges($result['affected_row_count']);
        return $result['affected_row_count'];
    }

    public function prepare(string $sql): LibSQLStatement
    {
        return new LibSQLStatement($this, $sql);
    }

    public function transaction(string $mode = 'deffered'): LibSQLTransaction
    {
        $this->checkTransactionMode($mode);
        return new LibSQLTransaction($this, $mode);
    }

    public function changes(): int
    {
        return $this->affected_rows;
    }

    public function isAutoCommit(): bool
    {
        return $this->auto_commit;
    }

    public function close(): void
    {
        $this->http->addRequest('close')->executeRequest();
    }

    private function checkTransactionMode(string $mode): LibSQLError|bool
    {
        if(!in_array(strtolower($mode), ['write', 'read', 'deffered'])) {
            throw new LibSQLError("Error undefined transaction mode: $mode", "UNDEFINED_TRANSACTION_MODE");
        }
        return true;
    }

    private function setIsAutoCommit(bool $yes): void
    {
        $this->auto_commit = $yes;
    }

    private function setChanges(array|int $results): void
    {
        if (is_array($results)) {
            $result = Utils::removeCloseResponses($results['results']);
            $this->affected_rows = $result['affected_row_count'];
        } else {
            $this->affected_rows = $results;
        }
    }
}
