<?php

namespace Darkterminal\TursoHttp\sadness;

use Darkterminal\TursoHttp\LibSQL;

/**
 * Class LibSQLQueryBuilder
 *
 * A query builder for the LibSQL database.
 *
 * @package Darkterminal\TursoHttp\sadness
 */
class LibSQLQueryBuilder
{
    /**
     * @var LibSQL The database connection instance.
     */
    protected LibSQL $db;

    /**
     * @var string The table name.
     */
    protected string $table;

    /**
     * @var string The columns to select.
     */
    protected string $columns = '*';

    /**
     * @var array The conditions for the query.
     */
    protected array $conditions = [];

    /**
     * @var array The bindings for the query.
     */
    protected array $bindings = [];

    /**
     * @var int|null The limit for the query.
     */
    protected int|null $limit = null;

    /**
     * @var int|null The offset for the query.
     */
    protected int|null $offset = null;

    /**
     * @var string|null The order by clause for the query.
     */
    protected string|null $orderBy = null;

    /**
     * @var array The join clauses for the query.
     */
    protected array $joins = [];

    /**
     * @var array The results of the query.
     */
    protected array $results = [];

    /**
     * @var bool Whether to use timestamps.
     */
    protected bool $timestamps = true;

    /**
     * @var string|null The group by clause for the query.
     */
    protected ?string $groupBy = null;

    /**
     * @var string|null The HAVING clause for the query.
     */
    protected ?string $having = null;

    /**
     * @var array The queries for UNION or UNION ALL operations.
     */
    protected array $unionQueries = [];

    /**
     * @var bool Whether to use UNION ALL instead of UNION.
     */
    protected bool $unionAll = false;

    /**
     * @var array The queries for EXCEPT operations.
     */
    protected array $exceptQueries = [];

    /**
     * @var array The queries for INTERSECT operations.
     */
    protected array $intersectQueries = [];

    /**
     * @var array The subqueries used in the query.
     */
    protected array $subqueries = [];

    /**
     * @var array The EXISTS subqueries used in the query.
     */
    protected array $existsSubqueries = [];

    /**
     * @var array The CASE expressions used in the query.
     */
    protected array $caseExpressions = [];

    /**
     * LibSQLQueryBuilder constructor.
     *
     * @param LibSQL $db The database connection instance.
     */
    public function __construct(LibSQL $db)
    {
        $this->db = $db;
    }

    /**
     * Set the table for the query.
     *
     * @param string $table The table name.
     * @return $this
     */
    public function table(string $table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Set the columns to select.
     *
     * @param string|array $columns The columns to select.
     * @return $this
     */
    public function select($columns = '*')
    {
        $this->columns = is_array($columns) ? implode(', ', $columns) : $columns;
        return $this;
    }

    /**
     * Add a where condition to the query.
     *
     * @param string $column The column name.
     * @param string $operator The operator.
     * @param mixed $value The value.
     * @return $this
     */
    public function where(string $column, string $operator, $value)
    {
        $this->conditions[] = "$column $operator ?";
        $this->bindings[] = $value;
        return $this;
    }

    /**
     * Add an OR WHERE condition to the query.
     *
     * @param string $column The column name.
     * @param string $operator The operator.
     * @param mixed $value The value.
     * @return $this
     */
    public function orWhere(string $column, string $operator, $value)
    {
        if ($this->conditions) {
            $this->conditions[] = "OR $column $operator ?";
        } else {
            $this->conditions[] = "$column $operator ?";
        }
        $this->bindings[] = $value;
        return $this;
    }

    /**
     * Add an AND WHERE condition to the query.
     *
     * @param string $column The column name.
     * @param string $operator The operator.
     * @param mixed $value The value.
     * @return $this
     */
    public function andWhere(string $column, string $operator, $value)
    {
        if ($this->conditions) {
            $this->conditions[] = "AND $column $operator ?";
        } else {
            $this->conditions[] = "$column $operator ?";
        }
        $this->bindings[] = $value;
        return $this;
    }

    /**
     * Add a WHERE grouping to the query.
     *
     * @param callable $callback A callback function to add multiple WHERE conditions.
     * @param string $operator The operator to use between groups (AND or OR).
     * @return $this
     */
    public function whereGroup(callable $callback, string $operator = 'AND')
    {
        $this->conditions[] = '(';

        // Execute the callback to build the group conditions
        $callback($this);

        // Add the grouped conditions and close the parenthesis
        $this->conditions[] = ')';

        // Append the operator if there are conditions in the group
        if ($this->conditions) {
            $this->conditions[] = $operator;
        }

        return $this;
    }

    /**
     * Add a BETWEEN condition to the query.
     *
     * @param string $column The column name.
     * @param mixed $start The start value.
     * @param mixed $end The end value.
     * @return $this
     */
    public function between(string $column, $start, $end)
    {
        $this->conditions[] = "$column BETWEEN ? AND ?";
        $this->bindings[] = $start;
        $this->bindings[] = $end;
        return $this;
    }

    /**
     * Add a NOT BETWEEN condition to the query.
     *
     * @param string $column The column name.
     * @param mixed $start The start value.
     * @param mixed $end The end value.
     * @return $this
     */
    public function notBetween(string $column, $start, $end)
    {
        $this->conditions[] = "$column NOT BETWEEN ? AND ?";
        $this->bindings[] = $start;
        $this->bindings[] = $end;
        return $this;
    }

    /**
     * Add an IN condition to the query.
     *
     * @param string $column The column name.
     * @param array $values The values to check against.
     * @return $this
     */
    public function in(string $column, array $values)
    {
        $placeholders = implode(', ', array_fill(0, count($values), '?'));
        $this->conditions[] = "$column IN ($placeholders)";
        $this->bindings = array_merge($this->bindings, $values);
        return $this;
    }

    /**
     * Add a LIKE condition to the query.
     *
     * @param string $column The column name.
     * @param string $pattern The pattern to match.
     * @return $this
     */
    public function like(string $column, string $pattern)
    {
        $this->conditions[] = "$column LIKE ?";
        $this->bindings[] = $pattern;
        return $this;
    }

    /**
     * Add a GLOB condition to the query.
     *
     * @param string $column The column name.
     * @param string $pattern The pattern to match.
     * @return $this
     */
    public function glob(string $column, string $pattern)
    {
        $this->conditions[] = "$column GLOB ?";
        $this->bindings[] = $pattern;
        return $this;
    }

    /**
     * Set the limit for the query.
     *
     * @param int $limit The limit.
     * @return $this
     */
    public function limit(int $limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Set the offset for the query.
     *
     * @param int $offset The offset.
     * @return $this
     */
    public function offset(int $offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Set the order by clause for the query.
     *
     * @param string $column The column name.
     * @param string $direction The direction (ASC or DESC).
     * @return $this
     */
    public function orderBy(string $column, string $direction = 'ASC')
    {
        $this->orderBy = "$column $direction";
        return $this;
    }

    /**
     * Add a GROUP BY clause to the query.
     *
     * @param string|array $columns The column(s) to group by.
     * @return $this
     */
    public function groupBy($columns)
    {
        if (is_array($columns)) {
            $columns = implode(', ', $columns);
        }

        $this->groupBy = $columns;
        return $this;
    }


    /**
     * Add an inner join clause to the query.
     *
     * @param string $table The table to join.
     * @param string $first The first column.
     * @param string $operator The operator.
     * @param string $second The second column.
     * @return $this
     */
    public function join(string $table, string $first, string $operator, string $second)
    {
        $this->joins[] = "INNER JOIN $table ON $first $operator $second";
        return $this;
    }

    /**
     * Add a left join clause to the query.
     *
     * @param string $table The table to join.
     * @param string $first The first column.
     * @param string $operator The operator.
     * @param string $second The second column.
     * @return $this
     */
    public function leftJoin(string $table, string $first, string $operator, string $second)
    {
        $this->joins[] = "LEFT JOIN $table ON $first $operator $second";
        return $this;
    }

    /**
     * Add a right join clause to the query.
     *
     * @param string $table The table to join.
     * @param string $first The first column.
     * @param string $operator The operator.
     * @param string $second The second column.
     * @return $this
     */
    public function rightJoin(string $table, string $first, string $operator, string $second)
    {
        $this->joins[] = "RIGHT JOIN $table ON $first $operator $second";
        return $this;
    }

    /**
     * Add a CROSS JOIN clause to the query.
     *
     * @param string $table The table to join.
     * @return $this
     */
    public function crossJoin(string $table)
    {
        $this->joins[] = "CROSS JOIN $table";
        return $this;
    }

    /**
     * Add a self join clause to the query.
     *
     * @param string $table The table to join.
     * @param string $alias The alias for the table.
     * @param string $firstColumn The first column to join on.
     * @param string $operator The operator.
     * @param string $secondColumn The second column to join on.
     * @return $this
     */
    public function selfJoin(string $table, string $alias, string $firstColumn, string $operator, string $secondColumn)
    {
        $this->joins[] = "INNER JOIN $table AS $alias ON $firstColumn $operator $alias.$secondColumn";
        return $this;
    }

    /**
     * Add a FULL OUTER JOIN clause to the query using a common column.
     *
     * @param string $table The table to join.
     * @param string $column The column to join on.
     * @return $this
     */
    public function fullOuterJoinUsing(string $table, string $column)
    {
        $this->joins[] = "FULL OUTER JOIN $table USING($column)";
        return $this;
    }

    /**
     * Add a HAVING clause to the query.
     *
     * @param string $condition The HAVING condition.
     * @return $this
     */
    public function having(string $condition)
    {
        $this->having = $condition;
        return $this;
    }

    /**
     * Add a UNION clause to the query.
     *
     * @param string $query The query to union.
     * @param bool $all Whether to use UNION ALL instead of UNION.
     * @return $this
     */
    public function union(string $query, bool $all = false)
    {
        $this->unionQueries[] = $query;
        $this->unionAll = $all;
        return $this;
    }

    /**
     * Add an EXCEPT clause to the query.
     *
     * @param string $query The query to except.
     * @return $this
     */
    public function except(string $query)
    {
        $this->exceptQueries[] = $query;
        return $this;
    }

    /**
     * Add an INTERSECT clause to the query.
     *
     * @param string $query The query to intersect.
     * @return $this
     */
    public function intersect(string $query)
    {
        $this->intersectQueries[] = $query;
        return $this;
    }

    /**
     * Add a subquery to the query.
     *
     * @param string $alias The alias for the subquery.
     * @param \Closure $callback A callback that receives a new query builder instance to build the subquery.
     * @return $this
     */
    public function subquery(string $alias, \Closure $callback)
    {
        $subqueryBuilder = new self($this->db);
        $callback($subqueryBuilder);
        $this->subqueries[$alias] = $subqueryBuilder->getQuery();
        return $this;
    }

    /**
     * Add an EXISTS subquery to the query.
     *
     * @param \Closure $callback A callback that receives a new query builder instance to build the subquery.
     * @param bool $notExists Whether to use NOT EXISTS instead of EXISTS.
     * @return $this
     */
    public function exists(\Closure $callback, bool $notExists = false)
    {
        $subqueryBuilder = new self($this->db);
        $callback($subqueryBuilder);
        $subquery = $subqueryBuilder->getQuery();
        $this->existsSubqueries[] = [
            'type' => $notExists ? 'NOT EXISTS' : 'EXISTS',
            'query' => $subquery
        ];
        return $this;
    }

    /**
     * Add a CASE expression to the query.
     *
     * @param string $expression The CASE expression string.
     * @param string|null $alias The alias for the CASE expression result.
     * @return $this
     */
    public function addCaseExpression(string $expression, string $alias = null)
    {
        $this->caseExpressions[] = [
            'expression' => $expression,
            'alias' => $alias
        ];
        return $this;
    }

    /**
     * Build the CASE expression.
     *
     * @param string $caseExpression The initial expression for the CASE statement.
     * @param array $whenThens An associative array of WHEN conditions and their corresponding THEN results.
     * @param string|null $else The ELSE result.
     * @return string The constructed CASE expression.
     */
    public function buildCaseExpression(string $caseExpression, array $whenThens, string $else = null)
    {
        $caseSql = "CASE {$caseExpression} ";
        foreach ($whenThens as $when => $then) {
            $caseSql .= "WHEN {$when} THEN {$then} ";
        }
        if ($else !== null) {
            $caseSql .= "ELSE {$else} ";
        }
        $caseSql .= "END";
        return $caseSql;
    }

    /**
     * Get the constructed SQL query string without executing it.
     *
     * @param string|null $table The table name (optional).
     * @return string The constructed SQL query string.
     */
    public function getQuery(string $table = null): string
    {
        $table ??= $this->table;
        $sql = "SELECT {$this->columns}";

        if ($this->caseExpressions) {
            foreach ($this->caseExpressions as $case) {
                $sql .= ", {$case['expression']}";
                if ($case['alias']) {
                    $sql .= " AS {$case['alias']}";
                }
            }
        }

        $sql .= " FROM {$table}";

        if ($this->joins) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        if ($this->subqueries) {
            foreach ($this->subqueries as $alias => $subquery) {
                $sql .= ", ({$subquery}) AS {$alias}";
            }
        }

        if ($this->conditions) {
            $sql .= " WHERE " . implode(' ', array_filter($this->conditions));
        }

        if ($this->existsSubqueries) {
            foreach ($this->existsSubqueries as $subquery) {
                $sql .= " AND {$subquery['type']} ({$subquery['query']})";
            }
        }

        if ($this->groupBy) {
            $sql .= " GROUP BY {$this->groupBy}";
        }

        if ($this->having) {
            $sql .= " HAVING {$this->having}";
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

        return $sql;
    }

    /**
     * Execute the select query and return the results.
     *
     * @param string|null $table The table name (optional).
     * @return array The query results.
     */
    public function get(string $table = null)
    {
        $mainQuery = $this->getQuery($table);

        if ($this->unionQueries) {
            $unionType = $this->unionAll ? 'UNION ALL' : 'UNION';
            foreach ($this->unionQueries as $query) {
                $mainQuery .= " {$unionType} ({$query})";
            }
        }

        if ($this->exceptQueries) {
            foreach ($this->exceptQueries as $query) {
                $mainQuery .= " EXCEPT ({$query})";
            }
        }

        if ($this->intersectQueries) {
            foreach ($this->intersectQueries as $query) {
                $mainQuery .= " INTERSECT ({$query})";
            }
        }

        $this->results = $this->db->prepare($mainQuery)
            ->query($this->bindings)
            ->fetchArray(LibSQL::LIBSQL_ASSOC);

        return $this->results;
    }

    /**
     * Build and execute the REPLACE statement.
     *
     * @param string $table The table name.
     * @param array $data The data to insert.
     * @return bool True on success, false on failure.
     */
    public function replaceInto(string $table, array $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $bindings = array_values($data);
        $sql = "REPLACE INTO {$table} ({$columns}) VALUES ({$placeholders})";

        $statement = $this->db->prepare($sql);
        return $statement->execute($bindings) > 0;
    }

    /**
     * Insert data into the table.
     *
     * @param array $data The data to insert.
     * @return bool True on success, false on failure.
     */
    public function insert(array $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $bindings = array_values($data);

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $statement = $this->db->prepare($sql);

        return $statement->execute($bindings) > 0;
    }

    /**
     * Generate the SQL for inserting batches data.
     *
     * @param array $data The data to insert.
     * @return string The SQL statement.
     */
    public function values(array $data)
    {
        $columns = implode(', ', array_keys($data));
        $bindings = implode(', ', array_map(function ($value) {
            return "'" . addslashes($value) . "'";
        }, array_values($data)));

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($bindings)";
        return $sql;
    }

    /**
     * Update data in the table.
     *
     * @param array $data The data to update.
     * @return bool True on success, false on failure.
     */
    public function update(array $data)
    {
        $set = implode(', ', array_map(function ($column) {
            return "$column = ?";
        }, array_keys($data)));

        $bindings = array_values($data);
        $sql = "UPDATE {$this->table} SET $set";

        if ($this->conditions) {
            $sql .= " WHERE " . implode(' AND ', $this->conditions);
        }

        $statement = $this->db->prepare($sql);
        return $statement->execute(array_merge($bindings, $this->bindings));
    }

    /**
     * Perform an upsert operation.
     *
     * @param string $table The table name.
     * @param array $onInsert The columns and values to insert.
     * @param array $onUpdate The columns and expressions to update on conflict.
     * @return bool True on success, false on failure.
     */
    public function upsert(string $table, array $onInsert, array $onUpdate): string
    {
        $columns = implode(', ', array_keys($onInsert));
        $placeholders = implode(', ', array_fill(0, count($onInsert), '?'));
        $insertValues = array_values($onInsert);

        $insertSql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";

        if (!empty($onUpdate)) {
            $updateSql = [];
            foreach ($onUpdate as $column => $expression) {
                $updateSql[] = "{$column} = {$expression}";
            }
            $updateSql = implode(', ', $updateSql);
            $insertSql .= " ON CONFLICT ({$table}) DO UPDATE SET {$updateSql}";
        }

        $statement = $this->db->prepare($insertSql);
        return $statement->execute($insertValues) > 0;
    }

    /**
     * Delete data from the table.
     *
     * @return bool True on success, false on failure.
     */
    public function delete()
    {
        $sql = "DELETE FROM {$this->table}";
        if ($this->conditions) {
            $sql .= " WHERE " . implode(' AND ', $this->conditions);
        }

        $statement = $this->db->prepare($sql);
        return $statement->execute($this->bindings);
    }

    /**
     * Perform an INSERT operation with RETURNING clause.
     *
     * @param string $table The table name.
     * @param array $data The columns and values to insert.
     * @param array $returning The columns to return.
     * @return array The constructed SQL insert query string.
     */
    public function insertReturn(string $table, array $data, array $returning = []): array
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $bindings = array_values($data);

        $insertSql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";

        if (!empty($returning)) {
            $returningColumns = implode(', ', $returning);
            $insertSql .= " RETURNING {$returningColumns}";
        } else {
            $insertSql .= " RETURNING *";
        }

        $statement = $this->db->prepare($insertSql);
        $results = $statement->query($bindings)->fetchArray(LibSQL::LIBSQL_ASSOC);

        return $results;
    }

    /**
     * Perform an UPDATE operation with RETURNING clause.
     *
     * @param string $table The table name.
     * @param array $data The columns and values to update.
     * @param string $condition The condition to meet for the update.
     * @param array $returning The columns to return.
     * @return array The constructed SQL update query string.
     */
    public function updateReturn(string $table, array $data, string $condition, array $returning = []): array
    {
        $set = implode(', ', array_map(function ($column) {
            return "$column = ?";
        }, array_keys($data)));

        $bindings = array_values($data);

        $updateSql = "UPDATE {$table} SET {$set} WHERE {$condition}";

        if (!empty($returning)) {
            $returningColumns = implode(', ', $returning);
            $updateSql .= " RETURNING {$returningColumns}";
        }

        $statement = $this->db->prepare($updateSql);
        $results = $statement->query($bindings)->fetchArray(LibSQL::LIBSQL_ASSOC);

        return $results;
    }

    /**
     * Perform a DELETE operation with RETURNING clause.
     *
     * @param string $table The table name.
     * @param string $condition The condition to meet for the delete.
     * @param array $returning The columns to return.
     * @return array The constructed SQL delete query string.
     */
    public function deleteReturn(string $table, string $condition, array $returning = []): array
    {
        $deleteSql = "DELETE FROM {$table} WHERE {$condition}";

        if (!empty($returning)) {
            $returningColumns = implode(', ', $returning);
            $deleteSql .= " RETURNING {$returningColumns}";
        }

        $results = $this->db->query($deleteSql)->fetchArray(LibSQL::LIBSQL_ASSOC);
        return $results;
    }

    public function dropTable(string $name)
    {
        return $this->db->execute("DROP TABLE '$name'");
    }

    public function dropAllTables()
    {
        $sql = "select name from sqlite_master where type in ('table', 'index', 'trigger')";
        $tables = $this->db->query($sql)->fetchArray(LibSQL::LIBSQL_NUM);

        if (!empty($tables)) {
            $queries = [];
            foreach ($tables as $table) {
                $queries[] = "DROP TABLE '{$table}'";
            }
            return $this->db->executeBatch($queries);
        }
    }

    /**
     * Convert the results to JSON.
     *
     * @return string The JSON encoded results.
     */
    public function toJson()
    {
        $json = json_encode($this->results);
        $this->results = [];
        return $json;
    }

    /**
     * Convert the results to an object.
     *
     * @return object The results as an object.
     */
    public function toObject()
    {
        $object = (object) $this->results;
        $this->results = [];
        return $object;
    }
}
