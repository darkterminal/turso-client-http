<?php

namespace Darkterminal;

enum DataType
{
    const INTEGER   = 'INTEGER';
    const TEXT      = 'TEXT';
    const REAL      = 'REAL';
    const BLOB      = 'BLOB';
}

final class SadQuery
{
    protected $query;
    private $columns = [];
    private $tableName;

    public function __construct()
    {
        $this->query = '';
    }

    /**
     * Validates and throws an exception if the provided data type is not valid.
     *
     * @param string $dataType The data type to validate.
     *
     * @throws \InvalidArgumentException If the data type is not valid.
     */
    private function dataTypeException(string $dataType)
    {
        if (!in_array($dataType, [DataType::INTEGER, DataType::TEXT, DataType::REAL, DataType::BLOB])) {
            throw new \InvalidArgumentException('Invalid data type.');
        }
    }

    /**
     * Start building a CREATE TABLE query for the specified table.
     *
     * @param string $tableName The name of the table.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function createTable(string $tableName): self
    {
        $this->tableName = $tableName;
        $this->query = "CREATE TABLE IF NOT EXISTS $tableName (";
        return $this;
    }

    /**
     * Add a column definition to the table.
     *
     * @param string $columnName   The name of the column.
     * @param string $dataType     The data type of the column.
     * @param array  $constraints  Optional. An array of constraints for the column.
     *                             Example: ['NOT NULL', 'UNIQUE']
     *
     * @return $this               Returns the current instance for method chaining.
     */
    public function addColumn(string $columnName, string $dataType, array $constraints = [])
    {
        $this->dataTypeException($dataType);

        $this->columns[] = [
            'name' => $columnName,
            'type' => $dataType,
            'constraints' => $constraints,
        ];

        return $this;
    }

    /**
     * Generate the final CREATE TABLE SQL query based on added columns.
     *
     * @return string The generated CREATE TABLE SQL query.
     */
    public function endCreateTable(): string
    {
        $query = "CREATE TABLE IF NOT EXISTS {$this->tableName} (";

        foreach ($this->columns as $column) {
            $query .= "{$column['name']} {$column['type']}";

            foreach ($column['constraints'] as $constraint) {
                $query .= " $constraint";
            }

            $query .= ', ';
        }

        $query = rtrim($query, ', ');

        $query .= ');';
        $this->tableName = '';
        return $query;
    }

    /**
     * Rename a column in the specified table.
     *
     * @param string $tableName The name of the table.
     * @param string $oldColumnName The current name of the column to be renamed.
     * @param string $newColumnName The new name for the column.
     *
     * @return self Returns the current instance for method chaining.
     *
     * @throws \InvalidArgumentException If the provided data type is not valid.
     */
    public function renameColumn(string $tableName, string $oldColumnName, string $newColumnName): self
    {
        $this->query = "ALTER TABLE $tableName RENAME COLUMN $oldColumnName TO $newColumnName;";
        return $this;
    }

    /**
     * Drop a table if it exists.
     *
     * @param string $tableName The name of the table to be dropped.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function dropTable(string $tableName): self
    {
        $this->query = "DROP TABLE IF EXISTS $tableName;";
        return $this;
    }

    /**
     * Start building a SELECT query with specified columns.
     *
     * @param array $columns The columns to be selected.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function select(array $columns): self
    {
        $this->query = "SELECT " . implode(', ', $columns);
        return $this;
    }

    /**
     * Specify the table to SELECT FROM.
     *
     * @param string $table The name of the table to select from.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function from(string $table): self
    {
        $this->query .= " FROM $table";
        return $this;
    }

    /**
     * Add a WHERE clause to the SELECT query.
     *
     * @param string $condition The condition for the WHERE clause.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function where(string $condition): self
    {
        $this->query .= " WHERE $condition";
        return $this;
    }

    /**
     * Add an ORDER BY clause to the SELECT query.
     *
     * @param array $columns The columns to be used for ordering.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function orderBy(array $columns): self
    {
        $this->query .= " ORDER BY " . implode(', ', $columns);
        return $this;
    }

    /**
     * Add a LIMIT clause to the SELECT query.
     *
     * @param int $limit The maximum number of rows to return.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function limit(int $limit): self
    {
        $this->query .= " LIMIT $limit";
        return $this;
    }

    /**
     * Add a WHERE clause to check if a column value is between two values.
     *
     * @param string $column The column to check.
     * @param mixed $value1 The lower bound value.
     * @param mixed $value2 The upper bound value.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function between(string $column, $value1, $value2): self
    {
        $this->query .= " WHERE $column BETWEEN $value1 AND $value2";
        return $this;
    }

    /**
     * Add a WHERE clause to check if a column value is in a list of values.
     *
     * @param string $column The column to check.
     * @param array $values An array of values to check against.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function in(string $column, array $values): self
    {
        $valuesString = implode(', ', array_map(function ($value) {
            return "'$value'";
        }, $values));
        $this->query .= " WHERE $column IN ($valuesString)";
        return $this;
    }

    /**
     * Add a WHERE clause to check if a column value matches a pattern using LIKE.
     *
     * @param string $column The column to check.
     * @param string $pattern The pattern to match.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function like(string $column, string $pattern): self
    {
        $this->query .= " WHERE $column LIKE '$pattern'";
        return $this;
    }

    /**
     * Add a WHERE clause to check if a column value is NULL.
     *
     * @param string $column The column to check.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function isNull(string $column): self
    {
        $this->query .= " WHERE $column IS NULL";
        return $this;
    }

    /**
     * Add a WHERE clause to check if a column value matches a pattern using GLOB.
     *
     * @param string $column The column to check.
     * @param string $pattern The GLOB pattern to match.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function glob(string $column, string $pattern): self
    {
        $this->query .= " WHERE $column GLOB '$pattern'";
        return $this;
    }

    /**
     * Add a JOIN clause to the SELECT query.
     *
     * @param string $table The name of the table to join.
     * @param string $condition The join condition.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function join(string $table, string $condition): self
    {
        $this->query .= " JOIN $table ON $condition";
        return $this;
    }

    /**
     * Add an INNER JOIN clause to the SELECT query.
     *
     * @param string $table The name of the table to INNER JOIN.
     * @param string $condition The join condition.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function innerJoin(string $table, string $condition): self
    {
        $this->query .= " INNER JOIN $table ON $condition";
        return $this;
    }

    /**
     * Add a LEFT JOIN clause to the SELECT query.
     *
     * @param string $table The name of the table to LEFT JOIN.
     * @param string $condition The join condition.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function leftJoin(string $table, string $condition): self
    {
        $this->query .= " LEFT JOIN $table ON $condition";
        return $this;
    }

    /**
     * Add a CROSS JOIN clause to the SELECT query.
     *
     * @param string $table The name of the table to CROSS JOIN.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function crossJoin(string $table): self
    {
        $this->query .= " CROSS JOIN $table";
        return $this;
    }

    /**
     * Add a self JOIN clause to the SELECT query.
     *
     * @param string $table The name of the table to self JOIN.
     * @param string $condition The join condition.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function selfJoin(string $table, string $condition): self
    {
        $this->query .= " JOIN $table ON $condition";
        return $this;
    }

    /**
     * Add a FULL OUTER JOIN clause to the SELECT query.
     *
     * @param string $table The name of the table to FULL OUTER JOIN.
     * @param string $condition The join condition.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function fullOuterJoin(string $table, string $condition): self
    {
        $this->query .= " FULL OUTER JOIN $table ON $condition";
        return $this;
    }

    /**
     * Add a GROUP BY clause to the SELECT query.
     *
     * @param array $columns An array of columns to GROUP BY.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function groupBy(array $columns): self
    {
        $this->query .= " GROUP BY " . implode(', ', $columns);
        return $this;
    }

    /**
     * Add a HAVING clause to the SELECT query.
     *
     * @param string $condition The HAVING condition.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function having(string $condition): self
    {
        $this->query .= " HAVING $condition";
        return $this;
    }

    /**
     * Add a UNION clause to the SELECT query.
     *
     * @param string $query The SELECT query to UNION.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function union(string $query): self
    {
        $this->query .= " UNION $query";
        return $this;
    }

    /**
     * Add an EXCEPT clause to the SELECT query.
     *
     * @param string $query The SELECT query to EXCEPT.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function except(string $query): self
    {
        $this->query .= " EXCEPT $query";
        return $this;
    }

    /**
     * Add an INTERSECT clause to the SELECT query.
     *
     * @param string $query The SELECT query to INTERSECT.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function intersect(string $query): self
    {
        $this->query .= " INTERSECT $query";
        return $this;
    }

    /**
     * Add a subquery to the SELECT query.
     *
     * @param string $subquery The subquery to include.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function subquery(string $subquery): self
    {
        $this->query .= " ($subquery)";
        return $this;
    }

    /**
     * Add an EXISTS clause with a subquery to the SELECT query.
     *
     * @param string $subquery The subquery for the EXISTS clause.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function exists(string $subquery): self
    {
        $this->query .= " EXISTS ($subquery)";
        return $this;
    }

    /**
     * Add a CASE statement to the SELECT query.
     *
     * @param array $conditions An associative array of conditions and their corresponding results.
     * @param string $else The result when none of the conditions are met.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function case(array $conditions, string $else): self
    {
        $caseString = "CASE ";
        foreach ($conditions as $condition => $result) {
            $caseString .= "WHEN $condition THEN $result ";
        }
        $this->query .= "$caseString ELSE $else END";
        return $this;
    }

    /**
     * Add an INSERT INTO statement to the query.
     *
     * @param string $table The name of the table to insert into.
     * @param array $values An associative array of column-value pairs for insertion.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function insert(string $table, array $values): self
    {
        $columns = implode(', ', array_keys($values));
        $data = implode(', ', array_map(function ($value) {
            return "'$value'";
        }, $values));
        $this->query = "INSERT INTO $table ($columns) VALUES ($data)";
        return $this;
    }

    /**
     * Add an UPDATE statement to the query.
     *
     * @param string $table The name of the table to update.
     * @param array $values An associative array of column-value pairs for updating.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function update(string $table, array $values): self
    {
        $setClause = implode(', ', array_map(function ($column, $value) {
            return "$column = '$value'";
        }, array_keys($values), $values));
        $this->query = "UPDATE $table SET $setClause";
        return $this;
    }

    /**
     * Add a DELETE FROM statement to the query.
     *
     * @param string $table The name of the table to delete from.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function delete(string $table): self
    {
        $this->query = "DELETE FROM $table";
        return $this;
    }

    /**
     * Add a REPLACE INTO statement to the query.
     *
     * @param string $table The name of the table to replace into.
     * @param array $values An associative array of column-value pairs for replacement.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function replace(string $table, array $values): self
    {
        $columns = implode(', ', array_keys($values));
        $data = implode(', ', array_map(function ($value) {
            return "'$value'";
        }, $values));
        $this->query = "REPLACE INTO $table ($columns) VALUES ($data)";
        return $this;
    }

    /**
     * Begin a transaction in the query.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function beginTransaction(): self
    {
        $this->query = "BEGIN TRANSACTION";
        return $this;
    }

    /**
     * Commit the current transaction in the query.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function commit(): self
    {
        $this->query = "COMMIT";
        return $this;
    }

    /**
     * Rollback the current transaction in the query.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function rollback(): self
    {
        $this->query = "ROLLBACK";
        return $this;
    }

    /**
     * Get the current query string.
     *
     * @return string Returns the current query string.
     */
    public function getQuery(): string
    {
        return $this->query;
    }
}
