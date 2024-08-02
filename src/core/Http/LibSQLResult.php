<?php

namespace Darkterminal\TursoHttp\core\Http;

use Darkterminal\TursoHttp\core\Enums\DataType;
use Darkterminal\TursoHttp\core\Enums\Timezone;
use Darkterminal\TursoHttp\core\Utils;
use Darkterminal\TursoHttp\LibSQL;
use DateTime;

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
            $i = 0;
            while ($i < count($this->rows)) {
                if ($mode === LibSQL::LIBSQL_ASSOC) {
                    $results = $this->getAssoc($this->cols, $this->rows);
                } else if ($mode === LibSQL::LIBSQL_NUM) {
                    $results = $this->getNum($this->rows);
                } else {
                    array_push(
                        $results,
                        array_merge(
                            $this->getAssoc($this->cols, $this->rows),
                            $this->getNum($this->rows)
                        )
                    );
                }
                $i++;
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

    private function getAssoc($tableColumns, $tableRows)
    {
        $results = [];
        $columns = array_map(function ($col) {
            return $col['name'];
        }, $tableColumns);

        $values = array_map(function ($vals) {
            $arr_vals = [];
            $i = 0;
            foreach ($vals as $val) {
                if ($val['type'] === "null") $val['value'] = null;
                $arr_vals[] = $this->cast($val['type'], $val['value']);
                $i++;
            }
            return $arr_vals;
        }, $tableRows);

        foreach ($values as $value) {
            $results[] = array_combine($columns, $value);
        }

        return $results;
    }

    public function getNum($tableRows)
    {
        $values = [];
        foreach ($tableRows as $row) {
            $i = 0;
            foreach ($row as $data) {
                $values[] = $this->cast($this->columnType($i), $data['value']);
                $i++;
            }
        }
        return $values;
    }

    private function cast(string $type, mixed $value)
    {
        if ($type == DataType::BLOB) {
            return base64_encode(base64_encode($value));
        }

        $type = $this->isValidDateTime($value) ? 'datetime' : $type;

        $timezoneString = getenv('DB_TIMEZONE');
        $timezone = empty($timezoneString) ? Timezone::fromString('UTC') : Timezone::fromString($timezoneString);

        $result = match (strtolower($type)) {
            'null' => null,
            'boolean', 'integer' => (int) $value,
            'double', 'float' => (float) $value,
            'string', 'text' => (string) $value,
            'datetime' => $timezone->convertFromUtc($value),
            default => null,
        };

        return $result;
    }

    private function isValidDateTime($dateString, $format = 'Y-m-d H:i:s')
    {
        $dateTime = DateTime::createFromFormat($format, $dateString ?? date($format));
        return $dateTime && $dateTime->format($format) === $dateString;
    }
}
