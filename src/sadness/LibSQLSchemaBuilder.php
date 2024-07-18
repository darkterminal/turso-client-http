<?php

namespace Darkterminal\TursoHttp\sadness;

use Darkterminal\TursoHttp\LibSQL;

class LibSQLSchemaBuilder
{
    protected $db;
    protected $queries = [];

    public function __construct(LibSQL $db)
    {
        $this->db = $db;
    }

    public function create($table, $callback)
    {
        $blueprint = new LibSQLBlueprint($table);
        $callback($blueprint);
        $query = $blueprint->toSql();
        $this->queries[] = $query;
        return $this;
    }

    public function table($table, $callback)
    {
        $blueprint = new LibSQLBlueprint($table);
        $callback($blueprint);
        $queries = $blueprint->alterToSql();
        $this->queries = array_merge($this->queries, $queries);
        return $this;
    }

    public function drop($table)
    {
        $query = "DROP TABLE IF EXISTS $table";
        $this->queries[] = $query;
        return $this;
    }

    public function execute()
    {
        $trx = $this->db->transaction();
        try {
            foreach ($this->queries as $query) {
                echo $query . PHP_EOL;
                $trx->execute($query);
            }
            $trx->commit();
        } catch (\Exception $e) {
            $trx->rollBack();
            throw $e;
        } finally {
            $this->queries = [];
        }
    }
}
