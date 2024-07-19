<?php

namespace Darkterminal\TursoHttp\sadness;

use Darkterminal\TursoHttp\core\Enums\LibSQLType;

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
        $this->columns[] = "$name TEXT";
        return $this;
    }

    public function integer(string $name)
    {
        $this->columns[] = "$name INTEGER";
        return $this;
    }

    public function real(string $name)
    {
        $this->columns[] = "$name REAL";
        return $this;
    }

    public function boolean(string $name)
    {
        $this->columns[] = "$name INTEGER";
        return $this;
    }

    public function date(string $name)
    {
        $this->columns[] = "$name TEXT";
        return $this;
    }

    public function blob(string $name)
    {
        $this->columns[] = "$name BLOB";
        return $this;
    }

    public function timestamps()
    {
        $this->columns[] = "created_at DATETIME DEFAULT CURRENT_TIMESTAMP";
        $this->columns[] = "updated_at DATETIME DEFAULT CURRENT_TIMESTAMP";
        return $this;
    }

    public function unique(string $name)
    {
        $this->columns[] = "$name TEXT UNIQUE";
        return $this;
    }

    public function addColumn(LibSQLType $type, string $name, int $length = null)
    {
        $type = LibSQLType::tryFrom($type->value) ? $type->value : 'TEXT';
        $definition = "$name $type";
        if ($length && $type !== 'BLOB') {
            $definition .= "($length)";
        }

        $this->alterations[] = "ADD $definition";
        return $this;
    }

    public function toSql()
    {
        $columnsSql = implode(', ', $this->columns);
        return "CREATE TABLE IF NOT EXISTS {$this->table} ($columnsSql)";
    }

    public function alterToSql()
    {
        $queries = [];
        foreach ($this->alterations as $alteration) {
            $queries[] = "ALTER TABLE {$this->table} $alteration";
        }
        return $queries;
    }
}
