<?php

namespace Darkterminal\TursoHttp\core\Http;

use Darkterminal\TursoHttp\core\Utils;
use Darkterminal\TursoHttp\LibSQL;

/**
 * Represents a prepared SQL statement.
 */
class LibSQLStatement
{
    protected array $parameters = [];

    protected array $results = [];
    /**
     * Creates a new LibSQLStatement instance.
     *
     * @param LibSQL $db The connection ID.
     * @param string $sql The SQL statement.
     */
    public function __construct(protected LibSQL $db, protected string $sql)
    {
    }

    /**
     * Finalizes the prepared statement.
     *
     * @return void
     */
    public function finalize()
    {
        $this->sql = '';
    }

    /**
     * Executes the prepared statement with given parameters.
     *
     * @param array $parameters The parameters for the statement.
     * 
     * @return int The number of affected rows.
     */
    public function execute(array $parameters)
    {
        $this->parameters = $parameters;
        $res = $this->db->getConnection()->prepareRequest($this->sql, $parameters)->executeRequest()->get();
        $this->setResults($res);
        $result = Utils::removeCloseResponses($res['results']);
        return $result['affected_row_count'];
    }

    /**
     * Executes the prepared statement and retrieves the result set.
     *
     * @param array $parameters The parameters for the statement.
     * 
     * @return LibSQLResult The result set.
     */
    public function query(array $parameters = [])
    {
        $this->parameters = $parameters;
        $results = $this->db->getConnection()->prepareRequest($this->sql, $parameters)->executeRequest()->get();
        $this->setResults($results);
        return new LibSQLResult($results);
    }

    /**
     * Resets the prepared statement.
     *
     * @return void
     */
    public function reset()
    {
    }

    /**
     * Gets the number of parameters in the prepared statement.
     *
     * @return int The number of parameters.
     */
    public function parameterCount()
    {
        return count($this->parameters);
    }

    /**
     * Gets the name of a parameter by index.
     *
     * @param int $idx The index of the parameter.
     * 
     * @return string The name of the parameter.
     */
    public function parameterName(int $idx)
    {
        $params = Utils::isArrayAssoc($this->parameters) ? array_keys($this->parameters) : $this->parameters;
        return $params[$idx];
    }

    /**
     * Gets the column names of the result set.
     *
     * @return array The column names.
     */
    public function columns()
    {
        return $this->results['cols'];
    }

    private function setResults(array $results): void
    {
        $this->results = Utils::removeCloseResponses($results['results']);
    }
}
