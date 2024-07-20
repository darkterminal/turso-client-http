<?php

namespace Darkterminal\TursoHttp;

use Exception;
use Darkterminal\TursoHttp\core\Utils;
use Darkterminal\TursoHttp\core\Request;
use Darkterminal\TursoHttp\core\LibSQLError;
use Darkterminal\TursoHttp\core\Http\LibSQLResult;
use Darkterminal\TursoHttp\core\Http\LibSQLStatement;
use Darkterminal\TursoHttp\core\Http\LibSQLTransaction;

/**
 * Class LibSQL
 *
 * A class to interact with the LibSQL database over HTTP.
 *
 * @package Darkterminal\TursoHttp
 */
class LibSQL
{
    /**
     * Return associative array.
     */
    public const int LIBSQL_ASSOC = 1;

    /**
     * Return numerical array.
     */
    public const int LIBSQL_NUM = 2;

    /**
     * Return both associative and numerical array.
     */
    public const int LIBSQL_BOTH = 3;

    /**
     * Return all result sets.
     */
    public const int LIBSQL_ALL = 4;

    /**
     * Turso HTTP Database URL
     *
     * @var string
     */
    protected string $baseURL;

    /**
     * Turso Database Auth Token
     *
     * @var string
     */
    protected string $authToken;

    /**
     * @var Request The HTTP request handler.
     */
    protected Request $http;

    /**
     * @var int The number of affected rows.
     */
    protected int $affected_rows = 0;

    /**
     * @var bool Whether auto commit is enabled.
     */
    public bool $auto_commit = true;

    /**
     * LibSQL HTTP Instance
     *
     * @param string $dsn Your DSN config.
     */
    public function __construct(string $dsn)
    {
        $database = Utils::parseDsn($dsn);
        $this->baseURL = str_replace('libsql://', 'https://', $database['dbname']);
        $this->authToken = $database['authToken'];
        $this->http = new Request($this->baseURL, $this->authToken);
    }

    /**
     * Get the base URL of the database.
     *
     * @return string The base URL.
     */
    public function getBaseUrl(): string
    {
        return $this->baseURL;
    }

    /**
     * Get the authentication token.
     *
     * @return string The authentication token.
     */
    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    /**
     * Get the HTTP request handler.
     *
     * @return Request The request handler.
     */
    public function getConnection(): Request
    {
        return $this->http;
    }

    /**
     * Get the database version.
     *
     * @return string The database version.
     */
    public function version(): string
    {
        $response = Utils::makeRequest('GET', "{$this->baseURL}/version", $this->authToken);
        return $response;
    }

    /**
     * Execute a query and return the results.
     *
     * @param string $sql The SQL query.
     * @param array $parameters The query parameters.
     * @return LibSQLResult The query results.
     */
    public function query(string $sql, array $parameters = []): LibSQLResult
    {
        $results = $this->http->prepareRequest($sql, $parameters)->executeRequest()->get();
        $this->setChanges($results);
        return new LibSQLResult($results);
    }

    /**
     * Execute a query and return the number of affected rows.
     *
     * @param string $sql The SQL query.
     * @param array $parameters The query parameters.
     * @return int The number of affected rows.
     */
    public function execute(string $sql, array $parameters = []): int
    {
        $res = $this->http->prepareRequest($sql, $parameters)->executeRequest()->get();
        $result = Utils::removeCloseResponses($res['results']);
        $this->setChanges($result['affected_row_count']);
        return $result['affected_row_count'];
    }

    /**
     * Execute a batch of queries.
     *
     * @param array $queries The queries to execute.
     * @throws LibSQLError If there is an error during execution.
     */
    public function executeBatch(array $queries)
    {
        $trx = $this->transaction();
        try {
            foreach ($queries as $query) {
                $trx->execute($query);
            }
            $trx->commit();
        } catch (Exception $e) {
            $trx->rollback();
            throw new LibSQLError($e->getMessage(), "EXECUTE_BATCH_ERROR");
        }
    }

    /**
     * Prepare a statement for execution.
     *
     * @param string $sql The SQL statement.
     * @return LibSQLStatement The prepared statement.
     */
    public function prepare(string $sql): LibSQLStatement
    {
        return new LibSQLStatement($this, $sql);
    }

    /**
     * Start a new transaction.
     *
     * @param string $mode The transaction mode.
     * @return LibSQLTransaction The transaction object.
     * @throws LibSQLError If the transaction mode is invalid.
     */
    public function transaction(string $mode = 'deferred'): LibSQLTransaction
    {
        $this->checkTransactionMode($mode);
        return new LibSQLTransaction($this, $mode);
    }

    /**
     * Get the number of affected rows.
     *
     * @return int The number of affected rows.
     */
    public function changes(): int
    {
        return $this->affected_rows;
    }

    /**
     * Check if auto commit is enabled.
     *
     * @return bool True if auto commit is enabled, false otherwise.
     */
    public function isAutoCommit(): bool
    {
        return $this->auto_commit;
    }

    /**
     * Close the database connection.
     *
     * @return void
     */
    public function close(): void
    {
        $this->http->addRequest('close')->executeRequest();
    }

    /**
     * Check if the transaction mode is valid.
     *
     * @param string $mode The transaction mode.
     * @return bool True if the mode is valid.
     * @throws LibSQLError If the mode is invalid.
     */
    private function checkTransactionMode(string $mode): bool
    {
        if (!in_array(strtolower($mode), ['write', 'read', 'deferred'])) {
            throw new LibSQLError("Error undefined transaction mode: $mode", "UNDEFINED_TRANSACTION_MODE");
        }
        return true;
    }

    /**
     * Set the number of affected rows.
     *
     * @param array|int $results The query results or the number of affected rows.
     * @return void
     */
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
