<?php

namespace Darkterminal\TursoHttp\sadness;

class LibSQLBlueprint
{
    protected $table;
    protected $columns = [];
    protected $alterations = [];

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function increments(string $name)
    {
        $this->columns[] = "$name INTEGER PRIMARY KEY AUTOINCREMENT";
        return $this;
    }

    public function string(string $name, int $length = 255)
    {
        $this->columns[] = "$name TEXT($length)";
        return $this;
    }

    public function integer(string $name)
    {
        $this->columns[] = "$name INTEGER";
        return $this;
    }

    public function timestamp($name)
    {
        $this->columns[] = "$name DATETIME DEFAULT CURRENT_TIMESTAMP";
        return $this;
    }

    public function unique($name)
    {
        $this->columns[] = "$name TEXT UNIQUE";
        return $this;
    }

    public function addColumn($type, $name, $length = null, string|null $options = null)
    {
        $definition = "$name $type";
        $definition .= $length ? "($length)" : "";
        $definition .= $options ? " $options" : "";
        $this->alterations[] = "ADD COLUMN $definition";
        return $this;
    }

    public function toSql()
    {
        $columnsSql = implode(', ', $this->columns);
        return "CREATE TABLE IF NOT EXISTS $this->table ($columnsSql)";
    }

    public function alterToSql()
    {
        $queries = [];
        foreach ($this->alterations as $alteration) {
            $queries[] = "ALTER TABLE $this->table $alteration";
        }
        return $queries;
    }
}
