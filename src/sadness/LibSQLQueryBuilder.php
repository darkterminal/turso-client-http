<?php

namespace Darkterminal\TursoHttp\sadness;

use Darkterminal\TursoHttp\core\LibSQLError;
use Darkterminal\TursoHttp\LibSQL;
use InvalidArgumentException;
use SQLite3;

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
    protected string $table = '';

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
     * @var string The name of the view for the query, if any.
     */
    protected string $viewName = '';

    /** 
     * @var array The custom columns of the view.
     */
    protected array $viewColumns = [];

    /** 
     * @var string The custom columns of the view.
     */
    protected string $viewQuery = '';

    /** 
     * @var string The name of trigger name
     */
    protected string $triggerName = '';

    /** 
     * @var string The time when trigger is fired
     */
    protected string $triggerTime = '';

    /** 
     * @var string The event should trigger to listen
     */
    protected string $triggerEvent = '';

    /** 
     * @var string The statements of trigger
     */
    protected string $triggerCondtion = '';

    /** 
     * @var array The statements of trigger
     */
    protected array $triggerStatements = [];

    /**
     * @var bool Auto commit query builder
     */
    protected $isAutoCommitBuilder = true;

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
     * @param mixed $operator_or_value The operator.
     * @param mixed|null $value The value.
     * @return $this
     */
    public function where(string $column, mixed $operator_or_value, mixed $value = null)
    {
        $str_operator = is_null($value) ? (!is_has_sqlite_operators($operator_or_value) ? '=' : $operator_or_value) : $operator_or_value;
        $this->conditions[] = "$column $str_operator ?";
        if (!is_null($value)) {
            $this->bindings[] = $value;
        }

        if (is_null($value) && !empty($str_operator)) {
            $this->bindings[] = $operator_or_value;
        }
        return $this;
    }

    /**
     * Add an OR WHERE condition to the query.
     *
     * @param string $column The column name.
     * @param mixed $operator_or_value The operator.
     * @param mixed $value The value.
     * @return $this
     */
    public function orWhere(string $column, mixed $operator_or_value, mixed $value = null)
    {
        $str_operator = !is_has_sqlite_operators($operator_or_value) ? '=' : $operator_or_value;

        $this->conditions[] = !empty($this->conditions) ? "OR $column $str_operator ?" : "$column $str_operator ?";
        if (!is_null($value)) {
            $this->bindings[] = $value;
        }

        if (is_null($value) && !empty($str_operator)) {
            $this->bindings[] = $operator_or_value;
        }
        return $this;
    }

    /**
     * Add an AND WHERE condition to the query.
     *
     * @param string $column The column name.
     * @param mixed $operator_or_value The operator.
     * @param mixed $value The value.
     * @return $this
     */
    public function andWhere(string $column, mixed $operator_or_value, mixed $value = null)
    {
        $str_operator = !is_has_sqlite_operators($operator_or_value) ? 'AND' : $operator_or_value;

        $this->conditions[] = !empty($this->conditions) ? "AND $column $str_operator ?" : "$column $str_operator ?";
        if (!is_null($value)) {
            $this->bindings[] = $value;
        }

        if (is_null($value) && !empty($str_operator)) {
            $this->bindings[] = $operator_or_value;
        }
        return $this;
    }

    /**
     * Add a WHERE grouping to the query.
     *
     * @param callable $callback A callback function to add multiple WHERE conditions.
     * @param string|null $operator The operator to use between groups (AND or OR).
     * @return $this
     */
    public function whereGroup(callable $callback, string|null $operator = null)
    {
        $this->conditions[] = '(';

        // Execute the callback to build the group conditions
        $callback($this);

        // Add the grouped conditions and close the parenthesis
        $this->conditions[] = ')';

        // Append the operator if there are conditions in the group
        if ($this->conditions && $operator !== null) {
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
     * Add an NOT IN condition to the query.
     *
     * @param string $column The column name.
     * @param array $values The values to check against.
     * @return $this
     */
    public function notIn(string $column, array $values)
    {
        $placeholders = implode(', ', array_fill(0, count($values), '?'));
        $this->conditions[] = "$column NOT IN ($placeholders)";
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
     * Add a NOT LIKE condition to the query.
     *
     * @param string $column The column name.
     * @param string $pattern The pattern to match.
     * @return $this
     */
    public function notLike(string $column, string $pattern)
    {
        $this->conditions[] = "$column NOT LIKE ?";
        $this->bindings[] = $pattern;
        return $this;
    }

    /**
     * Add a IS NULL condition to the query.
     *
     * @param string $column The column name.
     * @param string $pattern The pattern to match.
     * @return $this
     */
    public function isNull(string $column)
    {
        $this->conditions[] = "$column IS NULL";
        return $this;
    }

    /**
     * Add a IS NOT NULL condition to the query.
     *
     * @param string $column The column name.
     * @param string $pattern The pattern to match.
     * @return $this
     */
    public function isNotNull(string $column)
    {
        $this->conditions[] = "$column IS NOT NULL";
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
     * @param string|array $column The column name.
     * @param string $direction The direction (ASC or DESC).
     * @return $this
     */
    public function orderBy(string|array $column, string $direction = 'ASC')
    {
        $column = is_array($column) ? implode(', ', $column) : $column;
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
     * Adds an INNER JOIN clause using the specified column.
     *
     * This method creates an SQL INNER JOIN clause that uses the specified column to join the current table
     * with the given table. The column must have the same name in both tables.
     *
     * @param string $table The name of the table to join with.
     * @param string $column The column to use for the join.
     * @return $this The current query builder instance for method chaining.
     */
    public function joinUsing(string $table, string $column): self
    {
        $this->joins[] = "INNER JOIN $table USING($column)";
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
     * @param string $firstColumn The first column to join on.
     * @param string $operator The operator.
     * @param string $secondColumn The second column to join on.
     * @return $this
     */
    public function selfJoin(string $table, string $firstColumn, string $operator, string $secondColumn)
    {
        $this->joins[] = "INNER JOIN $table ON $firstColumn $operator $secondColumn";
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
     * Add a FULL OUTER JOIN clause to the query using a common column.
     *
     * @param string $table The table to join.
     * @param string $column1 The column 1 to join on.
     * @param string $column2 The column 2 to join on.
     * @return $this
     */
    public function fullOuterJoin(string $table, string $column1, string $column2)
    {
        $this->joins[] = "FULL OUTER JOIN $table ON $column1 = $column2";
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

    public function whereSubQuery(string $column, string $operator, \Closure $callback)
    {
        $subqueryBuilder = new self($this->db);
        $this->conditions[] = "$column $operator ({$callback($subqueryBuilder)})";
        return $this;
    }

    /**
     * Add an EXISTS subquery to the query.
     *
     * @param \Closure $callback A callback that receives a new query builder instance to build the subquery.
     * @return $this
     */
    public function exists(\Closure $callback)
    {
        $subqueryBuilder = new self($this->db);
        $callback($subqueryBuilder);
        $subquery = $subqueryBuilder->getQueryString();
        $this->existsSubqueries[] = [
            'type' => 'EXISTS',
            'query' => $subquery
        ];
        return $this;
    }

    /**
     * Add an NOT EXISTS subquery to the query.
     *
     * @param \Closure $callback A callback that receives a new query builder instance to build the subquery.
     * @return $this
     */
    public function notExists(\Closure $callback)
    {
        $subqueryBuilder = new self($this->db);
        $callback($subqueryBuilder);
        $subquery = $subqueryBuilder->getQueryString();
        $this->existsSubqueries[] = [
            'type' => 'NOT EXISTS',
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
                if ($this->conditions) {
                    $sql .= " AND {$subquery['type']} ({$subquery['query']})";
                } else {
                    $sql .= " WHERE {$subquery['type']} ({$subquery['query']})";
                }
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

        if (has_potential_injection($sql)) {
            throw new LibSQLError("Process rejected, your input is not safe", "ERR_BINDINGS");
        }

        return $sql;
    }

    public function autoCommitBuilder(bool $commit = true)
    {
        $this->isAutoCommitBuilder = $commit;
    }

    public function getQueryString(): string
    {
        $mainQuery = $this->getQuery();

        if ($this->unionQueries) {
            $unionType = $this->unionAll ? 'UNION ALL' : 'UNION';
            array_pop($this->unionQueries);
            foreach ($this->unionQueries as $query) {
                $mainQuery .= " {$unionType} {$query}";
            }
        }

        if ($this->exceptQueries) {
            $mainQuery = '';
            foreach ($this->exceptQueries as $query) {
                $mainQuery .= " EXCEPT {$query}";
            }
            $mainQuery = str_remove_word_begin_with('EXCEPT', $mainQuery);
        }

        if ($this->intersectQueries) {
            $mainQuery = '';
            foreach ($this->intersectQueries as $query) {
                $mainQuery .= " INTERSECT {$query}";
            }
            $mainQuery = str_remove_word_begin_with('INTERSECT', $mainQuery);
        }

        if ($this->viewName) {
            $mainQuery = '';

        }

        if (has_potential_injection($mainQuery) && has_potential_injection($this->bindings)) {
            throw new LibSQLError("Process rejected, your input is not safe", "ERR_BINDINGS");
        }

        $queryString = $this->replacePlaceholders($mainQuery, $this->bindings);

        if ($this->isAutoCommitBuilder) {
            $this->reset();
        }

        return $queryString;
    }

    public function explain(string $query)
    {
        $sql = <<<SQL
        EXPLAIN QUERY PLAN
        {$query}
        SQL;

        return $this->db->query($sql)->fetchArray(LibSQL::LIBSQL_ASSOC);
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
            array_pop($this->unionQueries);
            foreach ($this->unionQueries as $query) {
                $mainQuery .= " {$unionType} {$query}";
            }
        }

        if ($this->exceptQueries) {
            $mainQuery = '';
            foreach ($this->exceptQueries as $query) {
                $mainQuery .= " EXCEPT {$query}";
            }
            $mainQuery = str_remove_word_begin_with('EXCEPT', $mainQuery);
        }

        if ($this->intersectQueries) {
            $mainQuery = '';
            foreach ($this->intersectQueries as $query) {
                $mainQuery .= " INTERSECT {$query}";
            }
            $mainQuery = str_remove_word_begin_with('INTERSECT', $mainQuery);
        }

        if (has_potential_injection($mainQuery) && has_potential_injection($this->bindings)) {
            throw new LibSQLError("Process rejected, your input is not safe", "ERR_BINDINGS");
        }

        $this->results = $this->db->prepare($mainQuery)
            ->query($this->bindings)
            ->fetchArray(LibSQL::LIBSQL_ASSOC);

        $this->reset();

        return $this->results;
    }

    /**
     * Build and execute the REPLACE statement.
     *
     * @param string $table The table name.
     * @param array $data The data to insert.
     * @return int total row affected.
     */
    public function replaceInto(string $table, array $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $bindings = array_values($data);
        $sql = "REPLACE INTO {$table} ({$columns}) VALUES ({$placeholders})";

        if (has_potential_injection($sql) && has_potential_injection($bindings)) {
            throw new LibSQLError("Process rejected, your input is not safe", "ERR_BINDINGS");
        }

        $statement = $this->db->prepare($sql);
        return $statement->execute($bindings);
    }

    /**
     * Insert data into the table.
     *
     * @param array $data The data to insert.
     * @param bool $getQueryString The sql generate query string
     * @return int|string total row affected or generated query.
     */
    public function insert(array $data, bool $getQueryString = false)
    {
        if (is_nested_array($data)) {
            throw new LibSQLError("You can't insert nested arrays. Use insertBatch method if you want to used multiple rows", "ERR_BINDINGS");
        }

        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $bindings = array_values($data);

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";

        if (has_potential_injection($sql) && has_potential_injection($bindings)) {
            throw new LibSQLError("Process rejected, your input is not safe", "ERR_BINDINGS");
        }

        $this->reset();

        if ($getQueryString) {
            $sql = $this->replacePlaceholders($sql, $bindings);
            return $sql;
        }

        $statement = $this->db->prepare($sql);
        return $statement->execute($bindings);
    }

    /**
     * Insert multiple rows into the table in a single query.
     *
     * @param array $data An array of associative arrays, each representing a row to insert.
     * @param bool $getQueryString The sql generate query string
     * @return int|string total row affected or generated query string.
     */
    public function insertBatch(array $data, bool $getQueryString = false)
    {
        if (empty($data)) {
            throw new InvalidArgumentException('Data array cannot be empty.');
        }

        $columns = array_keys(reset($data));
        $columnsList = implode(', ', array_map(function ($column) {
            return "`{$column}`";
        }, $columns));

        $rowPlaceholders = [];
        $bindings = [];
        foreach ($data as $row) {
            $rowPlaceholders[] = '(' . implode(', ', array_fill(0, count($row), '?')) . ')';
            foreach ($row as $value) {
                $bindings[] = $value;
            }
        }
        $allPlaceholders = implode(', ', $rowPlaceholders);

        $sql = "INSERT INTO `{$this->table}` ({$columnsList}) VALUES {$allPlaceholders}";

        if (has_potential_injection($sql) && has_potential_injection($bindings)) {
            throw new LibSQLError("Process rejected, your input is not safe", "ERR_BINDINGS");
        }

        if ($getQueryString) {
            $sql = $this->replacePlaceholders($sql, $bindings);
            return $sql;
        }

        $this->reset();

        $statement = $this->db->prepare($sql);
        return $statement->execute($bindings);
    }

    /**
     * Copies a table from one database to another.
     *
     * @param string $from The name of the source table.
     * @param string $to The name of the destination table.
     * @param array|string $columns The columns to copy. Default is '*' (all columns).
     * @param bool $getQueryString The sql generate query string
     * @return int|string total row affected or generated query string.
     */
    public function copyTable(string $from, string $to, array|string $columns = '*', bool $getQueryString = false)
    {
        $columns = is_string($columns) ? $columns : implode(', ', $columns);

        if (is_has_sqlite_functions($columns)) {
            $columns = remove_quotes($columns);
        }

        $sql = "CREATE TABLE IF NOT EXISTS {$to} AS SELECT {$columns} FROM {$from}";

        if (has_potential_injection($sql)) {
            throw new LibSQLError("Process rejected, your input is not safe", "ERR_BINDINGS");
        }

        if ($getQueryString) {
            return $sql;
        }

        return $this->db->execute($sql);
    }

    /**
     * Update data in the table.
     *
     * @param array $data The data to update.
     * @param bool $getQueryString return the query string
     * @return int|string total row affected or query string.
     */
    public function update(array $data, bool $getQueryString = false)
    {
        $set = implode(', ', array_map(function ($column) {
            return "$column = ?";
        }, array_keys($data)));

        $bindings = array_values($data);
        $sql = "UPDATE {$this->table} SET $set";

        $merge_bindings = array_merge($bindings, $this->bindings);

        if ($this->conditions) {
            $sql .= " WHERE " . implode(' AND ', $this->conditions);
        }

        if (has_potential_injection($sql) && has_potential_injection($merge_bindings)) {
            throw new LibSQLError("Process rejected, your input is not safe", "ERR_BINDINGS");
        }

        $this->reset();

        if ($getQueryString) {
            $sql = $this->replacePlaceholders($sql, $merge_bindings);
            return $sql;
        }

        if (is_raw_value($bindings)) {
            $sql = $this->replacePlaceholders($sql, $merge_bindings);
            return $this->db->execute($sql);
        }

        $statement = $this->db->prepare($sql);
        return $statement->execute($merge_bindings);
    }

    /**
     * Perform an upsert operation.
     *
     * @param string $table The table name.
     * @param array|\Closure $onInsert The columns and values to insert or a closure to build the insert query.
     * @param array|\Closure $onUpdate The columns and expressions to update on conflict or a closure to build the update query.
     * @param string $onConflict The name of the conflict column.
     * @param bool $getQueryString return the query string
     * @return int|string total row affected or query string.
     */
    public function upsert(string $table, array|\Closure $onInsert, array|\Closure $onUpdate, string $onConflict, bool $getQueryString = false): int|string
    {
        if ($onInsert instanceof \Closure) {
            $insertBuilder = new self($this->db);
            $onInsert($insertBuilder);
            $insertSql = $insertBuilder->getQueryString();
        } else if (is_array($onInsert)) {
            $columns = implode(', ', array_keys($onInsert));
            $placeholders = implode(', ', array_fill(0, count($onInsert), '?'));
            $insertValues = array_values($onInsert);
            $insertSql = $this->replacePlaceholders("INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})", $insertValues);
        } else {
            throw new InvalidArgumentException('onInsert must be an array or an instance of \Closure');
        }

        if ($onUpdate instanceof \Closure) {
            $updateBuilder = new self($this->db);
            $onUpdate($updateBuilder);
            $updateSql = $updateBuilder->getQueryString();
        } else if (is_array($onUpdate)) {
            $updateParts = [];
            foreach ($onUpdate as $column => $expression) {
                $updateParts[] = "{$column} = {$expression}";
            }
            $updateSql = remove_quotes(implode(', ', $updateParts));
            if ($this->conditions) {
                $updateSql .= " WHERE " . remove_quotes(implode(' AND ', $this->conditions));
            }
            $updateSql = $this->replacePlaceholders($updateSql, $this->bindings);
        } else {
            throw new InvalidArgumentException('onUpdate must be an array or an instance of \Closure');
        }

        if (!empty($updateSql)) {
            $insertSql .= " ON CONFLICT ({$onConflict}) DO UPDATE SET {$updateSql}";
        } else {
            $insertSql .= " ON CONFLICT ({$onConflict}) DO NOTHING";
        }

        if (has_potential_injection($insertSql)) {
            throw new LibSQLError("Process rejected, your input is not safe", "ERR_BINDINGS");
        }

        $this->reset();

        if ($getQueryString) {
            return $insertSql;
        }

        return $this->db->execute($insertSql);
    }

    /**
     * Delete data from the table.
     * 
     * @param bool $getQueryString return the query string
     * @return int|string total row affected or query string.
     */
    public function delete(bool $getQueryString = true)
    {
        $sql = "DELETE FROM {$this->table}";
        if ($this->conditions) {
            $sql .= " WHERE " . implode(' AND ', $this->conditions);
        }

        if (has_potential_injection($this->bindings)) {
            throw new LibSQLError("Process rejected, your input is not safe", "ERR_BINDINGS");
        }

        $bindings = $this->bindings;
        $this->reset();

        if ($getQueryString) {
            $sql = $this->replacePlaceholders($sql, $bindings);
            return $sql;
        }

        if (is_raw_value($sql)) {
            $sql = $this->replacePlaceholders($sql, $bindings);
            return $this->db->execute($sql, $bindings);
        }

        $statement = $this->db->prepare($sql);
        return $statement->execute($bindings);
    }

    /**
     * Perform an INSERT operation with RETURNING clause.
     *
     * @param string $table The table name.
     * @param array $data The columns and values to insert.
     * @param array|string $returning The columns to return.
     * @param bool $getQueryString return the query string
     * @return array|string The constructed SQL insert query string or generated query string.
     */
    public function insertReturn(string $table, array $data, array|string $returning = [], bool $getQueryString = false): array|string
    {
        if (is_nested_array($data)) {
            $columns = [];
            $placeholders = [];
            $bindings = [];
            foreach ($data as $d) {
                $columns[] = implode(', ', array_keys($d));
                $placeholders[] = implode(', ', array_fill(0, count($d), '?'));
                $bindings[] = array_values($d);
            }

            $columns = reset($columns);
            $insertSql = "INSERT INTO {$table} ({$columns}) VALUES ";
            foreach ($placeholders as $placeholder) {
                $insertSql .= "({$placeholder}), ";
            }
            $insertSql = rtrim($insertSql, ", ");
        } else {
            $columns = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_fill(0, count($data), '?'));
            $bindings = array_values($data);
            $insertSql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        }

        if (!empty($returning)) {
            $returningColumns = is_array($returning) ? implode(', ', $returning) : $returning;
            $insertSql .= " RETURNING {$returningColumns}";
        } else {
            $insertSql .= " RETURNING *";
        }

        if (has_potential_injection($insertSql) && has_potential_injection($bindings)) {
            throw new LibSQLError("Process rejected, your input is not safe", "ERR_BINDINGS");
        }

        if ($getQueryString) {
            $sql = $this->replacePlaceholders($insertSql, $bindings);
            return $sql;
        }

        if (is_raw_value($insertSql)) {
            $sql = $this->replacePlaceholders($insertSql, $bindings);
            return $this->db->query($sql, $bindings)->fetchArray(LibSQL::LIBSQL_ASSOC);
        }

        if (is_nested_array($bindings)) {
            $sql = $this->replacePlaceholders($insertSql, $bindings);
            return $this->db->query($sql)->fetchArray(LibSQL::LIBSQL_ASSOC);
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
     * @param bool $getQueryString return the query string
     * @return array|string The constructed SQL update query string or generated query string.
     */
    public function updateReturn(string $table, array $data, string $condition, array $returning = [], bool $getQueryString = false): array|string
    {
        $set = implode(', ', array_map(function ($column) {
            return "$column = ?";
        }, array_keys($data)));

        $bindings = array_values($data);

        $updateSql = "UPDATE {$table} SET {$set} WHERE {$condition}";

        if (!empty($returning)) {
            $returningColumns = implode(', ', $returning);
            $updateSql .= " RETURNING {$returningColumns}";
        } else {
            $updateSql .= " RETURNING *";
        }

        if (has_potential_injection($updateSql) && has_potential_injection($bindings)) {
            throw new LibSQLError("Process rejected, your input is not safe", "ERR_BINDINGS");
        }

        if ($getQueryString) {
            $sql = $this->replacePlaceholders($updateSql, $bindings);
            return $sql;
        }

        if (is_raw_value($updateSql)) {
            $sql = $this->replacePlaceholders($updateSql, $bindings);
            return $this->db->query($sql, $bindings)->fetchArray(LibSQL::LIBSQL_ASSOC);
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
     * @param bool $getQueryString return the query string
     * @return array|string The constructed SQL delete query string.
     */
    public function deleteReturn(string $table, string $condition, array $returning = [], bool $getQueryString = false): array|string
    {
        $deleteSql = "DELETE FROM {$table} WHERE {$condition}";

        if (!empty($returning)) {
            $returningColumns = implode(', ', $returning);
            $deleteSql .= " RETURNING {$returningColumns}";
        } else {
            $deleteSql .= " RETURNING *";
        }

        if (has_potential_injection($deleteSql)) {
            throw new LibSQLError("Process rejected, your input is not safe", "ERR_BINDINGS");
        }

        if ($getQueryString) {
            return $deleteSql;
        }

        $results = $this->db->query($deleteSql)->fetchArray(LibSQL::LIBSQL_ASSOC);
        return $results;
    }

    /**
     * Executes a series of SQL queries within a transaction.
     *
     * @param array $queries An array of SQL queries to execute.
     * @param bool $getQueryString Whether to return the transaction string instead of executing it.
     * @return bool|string Returns true if the transaction was successful, or a string containing the transaction string if $getQueryString is true.
     * @throws \Throwable If an error occurs during the transaction.
     */
    public function transactions(array $queries, bool $getQueryString = false)
    {
        if ($getQueryString) {
            $queries = array_map(function ($query) {
                return $this->replacePlaceholders($query, []);
            }, $queries);
            $queries = "BEGIN TRANSACTION;" . PHP_EOL . implode(";" . PHP_EOL, $queries) . ";" . PHP_EOL . "COMMIT;";
            return $queries;
        }

        $trx = $this->db->transaction();
        try {
            foreach ($queries as $query) {
                $trx->execute($query);
            }
            $trx->commit();
            return true;
        } catch (\Throwable $th) {
            $trx->rollback();
            error_log($th->getMessage());
            return false;
        }
    }

    /**
     * Executes a raw SQL query and returns the result as an associative array.
     *
     * @param string $query The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return array The result of the query as an associative array.
     */
    public function queryRaw(string $query, array $params)
    {
        $results = $this->db->query($query, $params)->fetchArray(LibSQL::LIBSQL_ASSOC);
        return $results;
    }

    /**
     * Executes a raw SQL query with the given parameters and returns the result.
     *
     * @param string $query The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return mixed The result of the query execution.
     */
    public function executeRaw(string $query, array $params)
    {
        $results = $this->db->execute($query, $params);
        return $results;
    }

    /**
     * Retrieves all tables from the SQLite database.
     *
     * @return array An array of tables, each represented as an associative array with 'type', 'name', 'tbl_name', 'rootpage', 'sql' keys.
     */
    public function getAllTables()
    {
        $tables = sqlite_master_type('table');
        return $tables;
    }

    /**
     * Drops a table from the database.
     *
     * @param string $name The name of the table to drop.
     * @return bool Returns true if the table was successfully dropped, false otherwise.
     */
    public function dropTable(string $name)
    {
        return $this->db->execute("DROP TABLE '$name'");
    }

    /**
     * Drops all tables, indexes, and triggers from the database.
     *
     * @return bool Returns true if all tables, indexes, and triggers were successfully dropped, false otherwise.
     * @throws \Throwable If an error occurs during the dropping process.
     */
    public function dropAllTables()
    {
        try {
            $tables = sqlite_master_type(['table', 'index', 'trigger']);

            if (!empty($tables)) {
                $queries = [];
                foreach ($tables as $table) {
                    $this->truncateTable($table['name']);

                    if ($table['type'] === 'index') {
                        $queries[] = "DROP INDEX '{$table['name']}'";
                    }

                    if ($table['type'] === 'trigger') {
                        $queries[] = "DROP TRIGGER '{$table['name']}'";
                    }

                    if ($table['type'] === 'table') {
                        $queries[] = "DROP TABLE '{$table['name']}'";
                    }
                }
                array_unshift($queries, 'PRAGMA foreign_keys=OFF');
                array_push($queries, 'PRAGMA foreign_keys=ON');
                $this->db->executeBatch($queries);

                if (!empty($this->getAllTables())) {
                    $tables = $this->getAllTables();
                    foreach ($tables as $table) {
                        if ($table['name'] === 'sqlite_sequence') {
                            $this->truncateTable($table['name']);
                        } else {
                            $this->dropTable($table['name']);
                        }
                    }
                }

                return true;
            }

            return false;
        } catch (\Throwable $th) {
            error_log($th->getMessage());
            return false;
        }
    }

    /**
     * Truncate a table in SQLite.
     *
     * @param string $name The name of the table to truncate.
     * @return bool
     */
    public function truncateTable(string $name)
    {
        try {
            $this->db->executeBatch([
                "DELETE FROM {$name}",
                "DELETE FROM sqlite_sequence WHERE name='{$name}'"
            ]);
            return true;
        } catch (\Throwable $th) {
            error_log($th->getMessage());
            return false;
        }
    }

    /**
     * Sets the name of the view and returns the current instance of the class.
     *
     * @param string $viewName The name of the view to be created.
     * @return $this The current instance of the class.
     */
    public function createView(string $viewName)
    {
        $this->viewName = $viewName;
        return $this;
    }

    /**
     * Set the view columns.
     *
     * @param array $columns The array of column names.
     * @return $this
     */
    public function viewColumns(array $columns)
    {
        $this->viewColumns = $columns;
        return $this;
    }

    /**
     * Sets the view query and returns the current instance of the class.
     *
     * @param string $viewQuery The view query to be set.
     * @return $this The current instance of the class.
     */
    public function viewQuery(string $viewQuery)
    {
        $this->viewQuery = $viewQuery;
        return $this;
    }

    /**
     * Generates a SQL query to create a view.
     *
     * @param bool $getQueryString Whether to return the generated SQL query string.
     * @return mixed Returns the result of the database execution if $getQueryString is false,
     *              otherwise returns the generated SQL query string.
     */
    public function generateView(bool $getQueryString = false)
    {
        $sql = "CREATE VIEW {$this->viewName} ";
        if (!empty($this->viewColumns)) {
            $columns = implode(', ', $this->viewColumns);
            $sql .= "({$columns})";
        }
        $sql .= " AS {$this->viewQuery}";
        $sql = strim($sql);

        $this->reset();

        if ($getQueryString) {
            return $sql;
        }

        return $this->db->execute($sql);
    }

    /**
     * Retrieves all indexes for a given table name.
     *
     * @param string|null $tableName The name of the table to retrieve indexes for. If null, retrieves all indexes.
     * @return array An array of indexes for the specified table name, or all indexes if $tableName is null.
     */
    public function getAllIndexes(string|null $tableName = null)
    {
        $indexes = [];
        $allIndexes = sqlite_master_type('index');

        if (!is_null($tableName)) {
            foreach ($allIndexes as $index) {
                if ($index['tbl_name'] === $tableName) {
                    $indexes[] = $index;
                }
            }

            return $indexes;
        }

        return $allIndexes;
    }

    /**
     * Creates an index on a specified table.
     *
     * @param string $name The name of the index.
     * @param string $table The name of the table to create the index on.
     * @param string|array $columns The column(s) to include in the index. Can be a string or an array of strings.
     * @param bool $getQueryString If true, returns the SQL query string instead of executing it.
     * @return mixed The result of the executed SQL query or the SQL query string.
     */
    public function createIndex(string $name, string $table, string|array $columns, bool $getQueryString = false)
    {
        $columns = is_array($columns) ? implode(', ', $columns) : $columns;
        $sql = "CREATE INDEX {$name} ON {$table} ({$columns})";

        if ($getQueryString) {
            return $sql;
        }

        return $this->db->execute($sql);
    }

    /**
     * Creates a unique index on a specified table.
     *
     * @param string $name The name of the index.
     * @param string $table The name of the table to create the index on.
     * @param string|array $columns The column(s) to include in the index. Can be a string or an array of strings.
     * @param bool $getQueryString If true, returns the SQL query string instead of executing it.
     * @return mixed The result of the executed SQL query or the SQL query string.
     */
    public function createUniqueIndex(string $name, string $table, string|array $columns, bool $getQueryString = false)
    {
        $columns = is_array($columns) ? implode(', ', $columns) : $columns;
        $sql = "CREATE UNIQUE INDEX {$name} ON {$table} ({$columns})";

        if ($getQueryString) {
            return $sql;
        }

        return $this->db->execute($sql);
    }

    /**
     * Drops an index from the database.
     *
     * @param string $name The name of the index to drop.
     * @return int total row affected.
     */
    public function dropIndex(string $name)
    {
        return $this->db->execute("DROP INDEX IF EXISTS '$name'");
    }

    /**
     * Drops all indexes from the database for a given table name.
     *
     * @param string|null $tableName The name of the table to drop indexes for. If null, drops all indexes.
     * @return bool Returns true if all indexes were successfully dropped, false otherwise.
     * @throws \Throwable If an error occurs during the dropping process.
     */
    public function dropAllIndexes(string|null $tableName = null)
    {
        $queries = [];
        $index_tables = $this->getAllIndexes($tableName);

        foreach ($index_tables as $index) {
            $queries[] = "DROP INDEX IF EXISTS {$index['name']}";
        }

        try {
            $this->db->executeBatch($queries);
            return true;
        } catch (\Throwable $th) {
            error_log($th->getMessage());
            return false;
        }
    }

    /**
     * Set Trigger Name
     * @param string $name The name of your trigger
     * @return $this
     */
    public function setTrigerName(string $name)
    {
        $this->triggerName = $name;
        return $this;
    }

    /**
     * Set Trigger Time
     * @param string $time When the trigger should fire
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setTriggerTime(string $time)
    {
        if (!in_array($time, ['BEFORE', 'AFTER', 'INSTEAD OF'])) {
            throw new InvalidArgumentException("Invalid trigger time: $time");
        }
        $this->triggerTime = $time;
        return $this;
    }

    /**
     * Set the trigger event.
     *
     * @param string $event The trigger event. Must be one of 'INSERT', 'UPDATE', or 'DELETE'.
     * @throws \InvalidArgumentException If the trigger event is invalid.
     * @return $this
     */
    public function setTriggerEvent(string $event)
    {
        if (!in_array($event, ['INSERT', 'UPDATE', 'DELETE'])) {
            throw new InvalidArgumentException("Invalid trigger event: $event");
        }
        $this->triggerEvent = $event;
        return $this;
    }

    /**
     * Set the table name for the trigger.
     *
     * @param string $tableName The name of the table.
     * @return $this The current instance of the class.
     */
    public function setTriggerTable(string $tableName)
    {
        $this->table = $tableName;
        return $this;
    }

    /**
     * Sets the trigger condition for the query builder.
     *
     * @param string $condition The condition to set for the trigger.
     * @return $this The current instance of the query builder.
     */
    public function setTriggerCondition(string $condition)
    {
        $this->triggerCondtion = $condition;
        return $this;
    }

    /**
     * Adds a trigger statement to the list of trigger statements.
     *
     * @param string $statement The trigger statement to add.
     * @return $this The current instance of the class.
     */
    public function addTriggerStatement(string $statement)
    {
        $this->triggerStatements[] = $statement;
        return $this;
    }

    /**
     * Adds a RAISE(ABORT) statement with the given message to the trigger statements list.
     *
     * @param string $message The message to be included in the RAISE(ABORT) statement.
     * @return $this The current instance of the class.
     */
    public function addRaiseAbort(string $message)
    {
        $this->triggerStatements[] = "SELECT RAISE(ABORT, '{$message}')";
        return $this;
    }

    /**
     * Adds a RAISE(ROLLBACK) statement with the given message to the trigger statements list.
     *
     * @param string $message The message to be included in the RAISE(ROLLBACK) statement.
     * @return $this The current instance of the class.
     */
    public function addRaiseRollback(string $message)
    {
        $this->triggerStatements[] = "SELECT RAISE(ROLLBACK, '{$message}')";
        return $this;
    }

    /**
     * Adds a RAISE FAIL statement with the given message to the triggerStatements array.
     *
     * @param string $message The message to be included in the RAISE FAIL statement.
     * @return $this Returns the current object for method chaining.
     */
    public function addRaiseFail(string $message)
    {
        $this->triggerStatements[] = "SELECT RAISE(FAIL, '{$message}')";
        return $this;
    }

    /**
     * Adds a RAISE(IGNORE) statement to the trigger statements list.
     *
     * @return $this The current instance of the class.
     */
    public function addRaiseIgnore()
    {
        $this->triggerStatements[] = "SELECT RAISE(IGNORE)";
        return $this;
    }

    /**
     * Generates the SQL statement for creating a trigger.
     *
     * @throws InvalidArgumentException if any of the required trigger components are missing.
     * @return string The SQL statement for creating the trigger.
     */
    public function getTriggerSQL()
    {
        if (!$this->triggerName || !$this->triggerTime || !$this->triggerEvent || !$this->table || empty($this->triggerStatements)) {
            throw new InvalidArgumentException("Missing required trigger components");
        }

        $sql = "CREATE TRIGGER {$this->triggerName} {$this->triggerTime} {$this->triggerEvent} ON {$this->table}";

        if ($this->triggerCondtion) {
            $sql .= " WHEN {$this->triggerCondtion}";
        }

        $sql .= " BEGIN\n";
        foreach ($this->triggerStatements as $statement) {
            $sql .= "  {$statement};\n";
        }
        $sql .= "END;";

        $this->reset();

        return $sql;
    }

    /**
     * Creates a trigger in the database using the provided trigger SQL statement.
     *
     * @throws \Throwable if there is an error executing the trigger SQL statement.
     * @return bool Returns true if the trigger is successfully created, false otherwise.
     */
    public function createTrigger()
    {
        try {
            $trigger = $this->getTriggerSQL();
            $this->db->execute($trigger);
            return true;
        } catch (\Throwable $th) {
            error_log($th->getMessage());
            return false;
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

    /**
     * Flag the value as a raw SQL value
     *
     * @param string $string The input string to be flagged.
     * @return string The input string with the flag.
     */
    public function rawValue(string $string)
    {
        return "|>rawValue$string|>rawValue";
    }

    /**
     * Escapes a string for SQLite.
     *
     * @param string $value The string to escape.
     * @return string The escaped string.
     */
    private function escapeString($value)
    {
        if (is_numeric($value)) {
            return $value;
        } elseif (is_string($value)) {
            return "'" . SQLite3::escapeString($value) . "'";
        }
        return $value;
    }

    /**
     * Replaces all occurrences of '?' in a SQL query with specified replacement values.
     *
     * @param string $query The SQL query string containing '?' placeholders.
     * @param mixed $replacement The value or array of values to replace '?' with.
     * @return string The modified SQL query string with placeholders replaced.
     */
    private function replacePlaceholders($query, $replacement)
    {
        if (is_array($replacement)) {
            if (is_nested_array($replacement)) {
                foreach ($replacement as $replace) {
                    foreach ($replace as $value) {
                        $escapedValue = $this->escapeString($value);
                        $query = preg_replace('/\?/', $escapedValue, $query, 1);
                    }
                }
            } else {
                if (substr_count($query, '?') != count($replacement)) {
                    throw new InvalidArgumentException('Number of replacements does not match the number of placeholders.');
                }

                foreach ($replacement as $value) {
                    $escapedValue = $this->escapeString($value);
                    $query = preg_replace('/\?/', $escapedValue, $query, 1);
                }
            }
        } elseif (is_string($replacement)) {
            $escapedReplacement = $this->escapeString($replacement);
            $query = str_replace('?', $escapedReplacement, $query);
        } else {
            throw new InvalidArgumentException('Replacement must be a string or an array of strings.');
        }

        if (is_raw_value($query)) {
            return remove_quotes($query);
        }

        return $query;
    }

    /**
     * Resets the internal state of the object to its initial state.
     *
     * This function clears all the properties of the object, setting them to their default values.
     * It is typically called when the object is reused or when a new query is being constructed.
     *
     * @return void
     */
    private function reset()
    {
        $this->table = '';
        $this->columns = '*';
        $this->conditions = [];
        $this->bindings = [];
        $this->limit = null;
        $this->offset = null;
        $this->orderBy = null;
        $this->joins = [];
        $this->timestamps = true;
        $this->groupBy = null;
        $this->having = null;
        $this->unionQueries = [];
        $this->unionAll = false;
        $this->exceptQueries = [];
        $this->intersectQueries = [];
        $this->subqueries = [];
        $this->existsSubqueries = [];
        $this->caseExpressions = [];
        $this->viewName = '';
        $this->viewColumns = [];
        $this->viewQuery = '';
        $this->triggerName = '';
        $this->triggerTime = '';
        $this->triggerEvent = '';
        $this->triggerCondtion = '';
        $this->triggerStatements = [];
    }
}
