<?php

namespace Darkterminal\TursoHttp\core\Http;

use Darkterminal\TursoHttp\LibSQL;

/**
 * Represents a database transaction in LibSQL.
 *
 * @package Darkterminal\TursoHttp\core\Http
 */
class LibSQLTransaction
{
    /**
     * @var int The number of affected rows.
     */
    protected int $affected_rows = 0;

    /**
     * @var bool Whether auto commit is enabled.
     */
    protected bool $auto_commit = true;

    /**
     * Creates a new LibSQLTransaction instance.
     *
     * @param LibSQL $db The LibSQL database connection instance.
     * @param string $trx_mode The transaction mode ('DEFERRED', 'READ', 'WRITE').
     */
    public function __construct(protected LibSQL $db, protected string $trx_mode)
    {
        $trx = $this->db->getConnection()->prepareRequest("BEGIN", [], true)->executeRequest()->get();
        $this->db->getConnection()->setBaton($trx['baton']);
        $this->trx_mode = strtoupper($trx_mode);
        $this->setIsAutoCommit(false);
    }

    /**
     * Retrieves the number of rows changed by the last SQL statement.
     *
     * @return int The number of rows changed.
     */
    public function changes(): int
    {
        return $this->affected_rows;
    }

    /**
     * Checks if the transaction is set to autocommit.
     *
     * @return bool True if autocommit is enabled, otherwise false.
     */
    public function isAutocommit(): bool
    {
        return $this->auto_commit;
    }

    /**
     * Executes an SQL statement within the transaction.
     *
     * @param string $sql The SQL statement to execute.
     * @param array $parameters The parameters for the statement (optional).
     *
     * @return LibSQLTransaction The current transaction instance.
     */
    public function execute(string $sql, array $parameters = []): LibSQLTransaction
    {
        $this->db->getConnection()->prepareRequest($sql, $parameters, true);
        return $this;
    }

    /**
     * Executes a query within the transaction and returns the result set.
     *
     * @param string $sql The SQL statement to execute.
     * @param array $parameters The parameters for the statement (optional).
     *
     * @return LibSQLResult The result of the query.
     */
    public function query(string $sql, array $parameters = []): LibSQLResult
    {
        $results = $this->db->getConnection()->prepareRequest($sql, $parameters, true)->executeRequest()->get();
        return new LibSQLResult($results);
    }

    /**
     * Commits the transaction.
     *
     * @return void
     */
    public function commit(): void
    {
        $this->db->getConnection()
            ->addRequest('execute', 'COMMIT')
            ->addRequest('close')
            ->executeRequest();
    }

    /**
     * Rolls back the transaction.
     *
     * @return void
     */
    public function rollback(): void
    {
        $this->db->getConnection()
            ->addRequest('execute', 'ROLLBACK')
            ->addRequest('close')
            ->executeRequest();
    }

    /**
     * Set whether auto commit is enabled.
     *
     * @param bool $yes True to enable auto commit, false to disable.
     * @return void
     */
    private function setIsAutoCommit(bool $yes): void
    {
        $this->auto_commit = $yes;
    }
}
