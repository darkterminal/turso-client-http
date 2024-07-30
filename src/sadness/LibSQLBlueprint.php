<?php

namespace Darkterminal\TursoHttp\sadness;

use Darkterminal\TursoHttp\core\Enums\DataType;

/**
 * Class LibSQLBlueprint
 *
 * A class to define and manage table schemas using the LibSQL database connection.
 *
 * @package Darkterminal\TursoHttp\sadness
 */
class LibSQLBlueprint
{
    /**
     * @var string The name of the table.
     */
    protected string $table;

    /**
     * @var array The columns of the table.
     */
    protected array $columns = [];

    /**
     * @var array The foreign keys of the table.
     */
    protected array $foreignKeys = [];

    /**
     * @var array The alterations to be made to the table.
     */
    protected array $alterations = [];

    /**
     * Creates a new LibSQLBlueprint instance.
     *
     * @param string $table The name of the table.
     */
    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * Creates a new table schema.
     *
     * @param string $table The name of the table.
     * @param callable $callback The callback function to define the table schema.
     * @return string The SQL query to create the table.
     */
    public static function create(string $table, callable $callback): string
    {
        $blueprint = new self($table);
        $callback($blueprint);
        return $blueprint->toSql();
    }

    /**
     * Adds an auto-incrementing integer primary key column.
     *
     * @param string $name The name of the column.
     * @return LibSQLBlueprint The current blueprint instance.
     */
    public function increments(string $name): LibSQLBlueprint
    {
        $this->columns[] = "$name INTEGER PRIMARY KEY AUTOINCREMENT";
        return $this;
    }

    /**
     * Adds a string column.
     *
     * @param string $name The name of the column.
     * @param int $length The length of the column (optional, default is 255).
     * @return LibSQLBlueprint The current blueprint instance.
     */
    public function string(string $name, int $length = 255): LibSQLBlueprint
    {
        $this->columns[] = "$name TEXT";
        return $this;
    }

    /**
     * Adds an integer column.
     *
     * @param string $name The name of the column.
     * @return LibSQLBlueprint The current blueprint instance.
     */
    public function integer(string $name): LibSQLBlueprint
    {
        $this->columns[] = "$name INTEGER";
        return $this;
    }

    /**
     * Adds a real (floating point) column.
     *
     * @param string $name The name of the column.
     * @return LibSQLBlueprint The current blueprint instance.
     */
    public function real(string $name): LibSQLBlueprint
    {
        $this->columns[] = "$name REAL";
        return $this;
    }

    /**
     * Adds a boolean column.
     *
     * @param string $name The name of the column.
     * @return LibSQLBlueprint The current blueprint instance.
     */
    public function boolean(string $name): LibSQLBlueprint
    {
        $this->columns[] = "$name INTEGER";
        return $this;
    }

    /**
     * Adds a date column.
     *
     * @param string $name The name of the column.
     * @return LibSQLBlueprint The current blueprint instance.
     */
    public function date(string $name): LibSQLBlueprint
    {
        $this->columns[] = "$name TEXT";
        return $this;
    }

    /**
     * Adds a binary large object (BLOB) column.
     *
     * @param string $name The name of the column.
     * @return LibSQLBlueprint The current blueprint instance.
     */
    public function blob(string $name): LibSQLBlueprint
    {
        $this->columns[] = "$name BLOB";
        return $this;
    }

    /**
     * Adds timestamp columns for created and updated at.
     *
     * @return LibSQLBlueprint The current blueprint instance.
     */
    public function timestamps(): LibSQLBlueprint
    {
        $this->columns[] = "created_at DATETIME DEFAULT CURRENT_TIMESTAMP";
        $this->columns[] = "updated_at DATETIME DEFAULT CURRENT_TIMESTAMP";
        return $this;
    }

    /**
     * Marks the last added column as NOT NULL.
     *
     * @return LibSQLBlueprint The current blueprint instance.
     */
    public function notNull(): LibSQLBlueprint
    {
        $lastColumnIndex = count($this->columns) - 1;
        $this->columns[$lastColumnIndex] = rtrim($this->columns[$lastColumnIndex]) . " NOT NULL";
        return $this;
    }

    /**
     * Adds a unique column.
     *
     * @param string $name The name of the column.
     * @return LibSQLBlueprint The current blueprint instance.
     */
    public function unique(string $name): LibSQLBlueprint
    {
        $this->columns[] = "$name TEXT UNIQUE";
        return $this;
    }

    /**
     * Adds a column of a specified data type.
     *
     * @param DataType $type The data type of the column.
     * @param string $name The name of the column.
     * @param int|null $length The length of the column (optional).
     * @return LibSQLBlueprint The current blueprint instance.
     */
    public function addColumn(DataType $type, string $name, int $length = null): LibSQLBlueprint
    {
        $type = DataType::tryFrom($type->value) ? $type->value : 'TEXT';
        $definition = "$name $type";
        if ($length && $type !== 'BLOB') {
            $definition .= "($length)";
        }

        $this->alterations[] = "ADD $definition";
        return $this;
    }

    /**
     * Adds a foreign key constraint to the table.
     *
     * @param string $column The column that references another table.
     * @param string $references The column in the referenced table.
     * @param string $on The referenced table.
     * @param string $onUpdate The action to perform on update (optional, default is 'CASCADE').
     * @param string $onDelete The action to perform on delete (optional, default is 'CASCADE').
     * @return LibSQLBlueprint The current blueprint instance.
     */
    public function foreignKey(string $column, string $references, string $on, string $onUpdate = 'CASCADE', string $onDelete = 'CASCADE'): LibSQLBlueprint
    {
        $this->foreignKeys[] = "FOREIGN KEY ($column) REFERENCES $on($references) ON UPDATE $onUpdate ON DELETE $onDelete";
        return $this;
    }

    /**
     * Generates the SQL query to create the table.
     *
     * @return string The SQL query.
     */
    public function toSql(): string
    {
        $columnsSql = implode(', ', $this->columns);
        $foreignKeysSql = !empty($this->foreignKeys) ? ', ' . implode(', ', $this->foreignKeys) : '';
        return "CREATE TABLE IF NOT EXISTS {$this->table} ($columnsSql$foreignKeysSql)";
    }

    /**
     * Generates the SQL queries to alter the table.
     *
     * @return array The SQL queries.
     */
    public function alterToSql(): array
    {
        $queries = [];
        foreach ($this->alterations as $alteration) {
            $queries[] = "ALTER TABLE {$this->table} $alteration";
        }
        return $queries;
    }
}
