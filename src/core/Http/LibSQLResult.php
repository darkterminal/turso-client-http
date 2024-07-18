<?php

namespace Darkterminal\TursoHttp\core\Http;

use Darkterminal\TursoHttp\core\Utils;
use Darkterminal\TursoHttp\LibSQL;

/**
 * Represents the result of a LibSQL query.
 */
class LibSQLResult
{
    protected array $results;
    protected array $cols;
    protected array $rows;

    public string|null $baton;

    public string|null $base_url;

    public function __construct(array $results)
    {
        $this->baton = $results['baton'];
        $this->base_url = $results['base_url'];
        $this->results = Utils::removeCloseResponses($results['results']);
        $this->cols = $this->results['cols'];
        $this->rows = $this->results['rows'];
    }

    /**
     * Fetches the result set as an array.
     *
     * @param int $mode The fetching mode (optional, default is 3).
     *
     * @return array The fetched result set.
     */
    public function fetchArray(int $mode = 3)
    {
        if ($mode !== LibSQL::LIBSQL_ALL) {
            $results = [];
            for ($i=0; $i < count($this->rows); $i++) { 
                if ($mode === LibSQL::LIBSQL_ASSOC) {
                    $columns = array_map(function($col) {
                        return $col['name'];
                    }, $this->cols);
                    foreach ($this->rows as $row) {
                        $values = [];
                        foreach ($row as $data) {
                            $values[] = $data['value'];
                        }
                    }
                    $assoc = array_combine($columns, $values);
                    array_push($results, $assoc);
                } else if ($mode === LibSQL::LIBSQL_NUM) {
                    foreach ($this->rows as $row) {
                        $values = [];
                        foreach ($row as $data) {
                            $values[] = $data['value'];
                        }
                    }
                    array_push($results, $values);
                } else {
                    $columns = array_map(function($col) {
                        return $col['name'];
                    }, $this->cols);
                    foreach ($this->rows as $row) {
                        $values = [];
                        foreach ($row as $data) {
                            $values[] = $data['value'];
                        }
                    }
                    $assoc = array_combine($columns, $values);
                    array_push($results, array_merge($assoc, $values));
                }
            }
            return $results;
        }

        return $this->results;
    }

    /**
     * Finalizes the result set and frees the associated resources.
     *
     * @return void
     */
    public function finalize()
    {
        // 
    }

    /**
     * Resets the result set for re-execution.
     *
     * @return void
     */
    public function reset()
    {
        // 
    }

    // /**
    //  * Retrieves the name of a column by its index.
    //  *
    //  * @param int $column The index of the column.
    //  *
    //  * @return string The name of the column.
    //  */
    public function columnName(int $column)
    {
        return array_map(function ($col) {
            return $col['name'];
        }, $this->cols)[$column];
    }

    /**
     * Retrieves the type of a column by its index.
     *
     * @param int $column The index of the column.
     *
     * @return string The type of the column.
     */
    public function columnType(int $column)
    {
        return array_map(function ($col) {
            return $col['decltype'];
        }, $this->cols)[$column];
    }

    /**
     * Retrieves the number of columns in the result set.
     *
     * @return int The number of columns.
     */
    public function numColumns()
    {
        return count($this->cols);
    }
}
