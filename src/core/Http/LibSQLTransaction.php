<?php

namespace Darkterminal\TursoHttp\core\Http;

use Darkterminal\TursoHttp\core\Utils;
use Darkterminal\TursoHttp\LibSQL;

/**
 * Represents a database transaction in LibSQL.
 */
class LibSQLTransaction
{
    protected int $affected_rows = 0;
    protected bool $auto_commit = true;
    
    /**
     * Creates a new LibSQLTransaction instance.
     *
     * @param LibSQL $db The connection ID.
     * @param string $trx_mode The transaction mode.
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
    public function changes()
    {
        return $this->affected_rows;
    }

    /**
     * Checks if the transaction is set to autocommit.
     *
     * @return bool True if autocommit is enabled, otherwise false.
     */
    public function isAutocommit()
    {
        return $this->auto_commit;
    }

    /**
     * Executes an SQL statement within the transaction.
     *
     * @param string $stmt The SQL statement to execute.
     * @param array $parameters The parameters for the statement (optional).
     *
     * @return LibSQLTransaction
     */
    public function execute(string $sql, array $parameters = []): LibSQLTransaction
    {
        $this->db->getConnection()->prepareRequest($sql, $parameters, true);
        return $this;
    }

    /**
     * Executes a query within the transaction and returns the result set.
     *
     * @param string $stmt The SQL statement to execute.
     * @param array $parameters The parameters for the statement (optional).
     *
     * @return LibSQLResult
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
    public function commit()
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
    public function rollback()
    {
        $this->db->getConnection()
            ->addRequest('execute', 'ROLLBACK')
            ->addRequest('close')
            ->executeRequest();
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
