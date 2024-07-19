<?php

namespace Darkterminal\TursoHttp\sadness;

use Darkterminal\TursoHttp\LibSQL;

class LibSQLQueryBuilder
{
    protected LibSQL $db;
    protected string $table;
    protected string $columns = '*';
    protected array $conditions = [];
    protected array $bindings = [];
    protected int|null $limit = null;
    protected int|null $offset = null;
    protected string|null $orderBy = null;
    protected array $results = [];
    protected $timestamps = true;
    protected $softDelete = false;
    protected $deletedAtColumn = 'deleted_at';

    public function __construct(LibSQL $db)
    {
        $this->db = $db;
    }

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function select($columns = '*')
    {
        $this->columns = is_array($columns) ? implode(', ', $columns) : $columns;
        return $this;
    }

    public function where($column, $operator, $value)
    {
        $this->conditions[] = "$column $operator ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function orderBy($column, $direction = 'ASC')
    {
        $this->orderBy = "$column $direction";
        return $this;
    }

    public function get(string $table = null)
    {
        $table ??= $this->table;
        $sql = "SELECT {$this->columns} FROM {$table}";

        if ($this->conditions) {
            $sql .= " WHERE " . implode(' AND ', $this->conditions);
        }

        if ($this->orderBy) {
            $sql .= " ORDER BY {$this->orderBy}";
        }

        if ($this->limit) {
            $sql .= " LIMIT {$this->limit}";
        }

        if ($this->offset) {
            $sql .= " OFFSET {$this->offset}";
        }

        $this->results = $this->db->prepare($sql)
            ->query($this->bindings)
            ->fetchArray(LibSQL::LIBSQL_ASSOC);
        
        return $this->results;
    }

    public function insert($data)
    {        
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $bindings = array_values($data);

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $statement = $this->db->prepare($sql);

        return $statement->execute($bindings);
    }

    public function toJson()
    {
        $json = json_encode($this->results);
        $this->results = [];
        return $json;
    }

    public function toObject()
    {
        $object = (object) $this->results;
        $this->results = [];
        return $object;
    }
}
