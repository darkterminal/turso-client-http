<?php

namespace Darkterminal\TursoHttp\sadness;

use Darkterminal\TursoHttp\LibSQL;

/**
 * Class LibSQLSchemaBuilder
 *
 * A class to build and manage database schemas using the LibSQL database connection.
 *
 * @package Darkterminal\TursoHttp\sadness
 */
class LibSQLSchemaBuilder
{
    /**
     * @var LibSQL The LibSQL database connection instance.
     */
    protected LibSQL $db;

    /**
     * @var array The array of SQL queries to be executed.
     */
    protected array $queries = [];
    
    /**
     * @var array The array of table name.
     */
    protected array $tableName = [];

    /**
     * Creates a new LibSQLSchemaBuilder instance.
     *
     * @param LibSQL $db The LibSQL database connection instance.
     */
    public function __construct(LibSQL $db)
    {
        $this->db = $db;
    }

    /**
     * Creates a new table.
     *
     * @param string $table The name of the table.
     * @param callable $callback The callback function to define the table schema.
     * @return LibSQLSchemaBuilder The current schema builder instance.
     */
    public function create(string $table, callable $callback): LibSQLSchemaBuilder
    {
        $this->tableName[] = $table;
        $blueprint = new LibSQLBlueprint($table);
        $callback($blueprint);
        $query = $blueprint->toSql();
        $this->queries[] = $query;
        return $this;
    }

    /**
     * Modifies an existing table.
     *
     * @param string $table The name of the table.
     * @param callable $callback The callback function to alter the table schema.
     * @return LibSQLSchemaBuilder The current schema builder instance.
     */
    public function table(string $table, callable $callback): LibSQLSchemaBuilder
    {
        $blueprint = new LibSQLBlueprint($table);
        $callback($blueprint);
        $queries = $blueprint->alterToSql();
        $this->queries = array_merge($this->queries, $queries);
        return $this;
    }

    /**
     * Drops an existing table.
     *
     * @param string $table The name of the table.
     * @return LibSQLSchemaBuilder The current schema builder instance.
     */
    public function drop(string $table): LibSQLSchemaBuilder
    {
        $query = "DROP TABLE IF EXISTS $table";
        $this->queries[] = $query;
        return $this;
    }

    /**
     * Retrieves the SQL query for a specific table or all queries if no table is provided.
     *
     * @param string|null $table The name of the table to retrieve the query for. If null, all queries will be returned.
     * @return string The SQL query for the specified table or all queries.
     */
    public function toSql(string|null $table = null)
    {
        $index = array_search($table, $this->tableName);
        return is_null($table) ? $this->queries : $this->queries[$index];
    }

    /**
     * Executes all the accumulated queries within a transaction.
     *
     * @return void
     * @throws \Exception If an error occurs during execution.
     */
    public function execute(): void
    {
        $trx = $this->db->transaction();
        try {
            foreach ($this->queries as $query) {
                $trx->execute($query);
            }
            $trx->commit();
        } catch (\Exception $e) {
            $trx->rollback();
            throw $e;
        } finally {
            $this->queries = [];
        }
    }
}
