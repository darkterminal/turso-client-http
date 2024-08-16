<?php

use Darkterminal\TursoHttp\core\Utils;
use Darkterminal\TursoHttp\LibSQL;
use Darkterminal\TursoHttp\sadness\LibSQLQueryBuilder;

if (!function_exists('sqlite_functions')) {
    /**
     * Returns an array of SQLite functions.
     *
     * @return array The array of SQLite functions.
     */
    function sqlite_functions()
    {
        $sqliteFunctions = [
            'avg',
            'count',
            'max',
            'min',
            'sum',
            'group_concat',
            'substr',
            'trim',
            'ltrim',
            'rtrim',
            'length',
            'replace',
            'upper',
            'lower',
            'instr',
            'coalesce',
            'ifnull',
            'iif',
            'nullif',
            'date',
            'time',
            'datetime',
            'julianday',
            'strftime',
            'abs',
            'random',
            'round',
            'json_type',
            'ntile',
            'sqlite_compileoption_get',
            'json_valid',
            'json_quote',
            'json_patch',
            '->',
            'json_array',
            'current_timestamp',
            'sqlite_compileoption_used',
            'json_remove',
            'json_object',
            'json_insert',
            '->>',
            'quote',
            'printf',
            'likelihood',
            'json_replace',
            'json_extract',
            'last_value',
            'rank',
            'sign',
            'nth_value',
            'total',
            'unhex',
            'subtype',
            'typeof',
            'load_extension',
            'soundex',
            'json_group_array',
            'octet_length',
            'json_group_object',
            'json_array_length',
            'substring',
            'randomblob',
            'unicode',
            'vector',
            'timediff',
            'percent_rank',
            'row_number',
            'string_agg',
            'last_insert_rowid',
            'sqlite_log',
            'unlikely',
            'libsql_vector_idx',
            'json_error_position',
            'char',
            'vector64',
            'vector32',
            'unixepoch',
            'sqlite_crypt',
            'concat',
            'total_changes',
            'changes',
            'sqlite_version',
            'glob',
            'zeroblob',
            'hex',
            'sqlite_source_id',
            'concat_ws',
            'format',
            'cume_dist',
            'vector_extract',
            'json',
            'dense_rank',
            'current_date',
            'current_time',
            'lag',
            'like',
            'lead',
            'first_value',
            'likely',
            'vector_distance_cos',
            'json_set',
            'regexpi',
            'lsmode',
            'readfile',
            'sha3',
            'uuid_blob',
            'mode',
            'strfilter',
            'padl',
            'rightstr',
            'leftstr',
            'charindex',
            'uuid_str',
            'lower_quartile',
            'last_rows_affected',
            'replicate',
            'pi',
            'ceil',
            'log10',
            'floor',
            'log',
            'coth',
            'cosh',
            'cot',
            'cos',
            'upper_quartile',
            'sinh',
            'radians',
            'fts5',
            'sqrt',
            'fts3_tokenizer',
            'geopoly_svg',
            'stdev',
            'bm25',
            'atan2',
            'highlight',
            'padc',
            'proper',
            'rtreenode',
            'rtreecheck',
            'geopoly_regular',
            'variance',
            'matchinfo',
            'geopoly_debug',
            'geopoly_bbox',
            'tan',
            'snippet',
            'padr',
            'geopoly_overlap',
            'writefile',
            'asin',
            'uuid',
            'rtreedepth',
            'degrees',
            'geopoly_area',
            'power',
            'exp',
            'match',
            'geopoly_blob',
            'libsql_server_database_name',
            'square',
            'optimize',
            'geopoly_within',
            'sqlite3mc_config_table',
            'geopoly_json',
            'offsets',
            'geopoly_xform',
            'ceiling',
            'tanh',
            'geopoly_ccw',
            'asinh',
            'regexp',
            'difference',
            'atn2',
            'geopoly_group_bbox',
            'ln',
            'sin',
            'sqlite3mc_config',
            'sqlite3mc_version',
            'geopoly_contains_point',
            'acos',
            'median',
            'fts5_source_id',
            'acosh',
            'sqlite3mc_codec_data',
            'sha3_query',
            'atan',
            'reverse',
            'atanh',
        ];
        return $sqliteFunctions;
    }
}

if (!function_exists('sqlite_avg')) {
    /**
     * Returns the SQL expression for calculating the average of a column.
     *
     * @param string $column The name of the column to calculate the average of.
     * @return string The SQL expression for calculating the average of the column.
     */
    function sqlite_avg(string $column)
    {
        return "AVG($column)";
    }
}

if (!function_exists('sqlite_count')) {
    /**
     * Returns the SQL expression for counting the number of rows in a table or the number of occurrences of a specific column value.
     *
     * @param string $column The name of the column to count. Defaults to '*' to count all rows.
     * @return string The SQL expression for counting the rows or occurrences of the column.
     */
    function sqlite_count(string $column = '*', string $alias = '')
    {
        $alias = $alias ?: "{$column}_count";
        return "COUNT($column) AS $alias";
    }
}

if (!function_exists('sqlite_max')) {
    /**
     * Returns the SQL expression for calculating the maximum value of a column.
     *
     * @param string $column The name of the column to calculate the maximum value of.
     * @return string The SQL expression for calculating the maximum value of the column.
     */
    function sqlite_max($column)
    {
        return "MAX($column)";
    }
}

if (!function_exists('sqlite_min')) {
    /**
     * Returns the SQL expression for calculating the minimum value of a column.
     *
     * @param string $column The name of the column to calculate the minimum value of.
     * @return string The SQL expression for calculating the minimum value of the column.
     */
    function sqlite_min($column)
    {
        return "MIN($column)";
    }
}

if (!function_exists('sqlite_sum')) {
    /**
     * Returns the SQL expression for calculating the sum of a column.
     *
     * @param string $column The name of the column to calculate the sum of.
     * @return string The SQL expression for calculating the sum of the column.
     */
    function sqlite_sum($column)
    {
        return "SUM($column)";
    }
}

if (!function_exists('sqlite_group_concat')) {
    /**
     * Returns the SQL expression for concatenating values of a column in SQLite.
     *
     * @param string $column The name of the column to concatenate values from.
     * @param string $separator The separator to use between concatenated values. Defaults to ','.
     * @return string The SQL expression for concatenating values of the column.
     */
    function sqlite_group_concat($column, $separator = ',')
    {
        return "GROUP_CONCAT($column, '$separator')";
    }
}

if (!function_exists('sqlite_substr')) {
    /**
     * Returns the SQL expression for extracting a substring from a column in SQLite.
     *
     * @param string $column The name of the column to extract the substring from.
     * @param int $start The starting position of the substring.
     * @param int|null $length The length of the substring. If not provided, the entire substring starting from $start will be returned.
     * @return string The SQL expression for extracting the substring.
     */
    function sqlite_substr($column, $start, $length = null)
    {
        if ($length) {
            return "SUBSTR($column, $start, $length)";
        }
        return "SUBSTR($column, $start)";
    }
}

if (!function_exists('sqlite_trim')) {
    /**
     * Returns the SQL expression for trimming leading and trailing whitespace from a column in SQLite.
     *
     * @param string $column The name of the column to trim.
     * @return string The SQL expression for trimming the column.
     */
    function sqlite_trim($column)
    {
        return "TRIM($column)";
    }
}

if (!function_exists('sqlite_ltrim')) {
    /**
     * Returns the SQL expression for removing leading whitespace from a column in SQLite.
     *
     * @param string $column The name of the column to remove leading whitespace from.
     * @return string The SQL expression for removing leading whitespace from the column.
     */
    function sqlite_ltrim($column)
    {
        return "LTRIM($column)";
    }
}

if (!function_exists('sqlite_rtrim')) {
    /**
     * Trims whitespace from the right side of a SQLite column value.
     *
     * @param string $column The name of the column to trim.
     * @return string The trimmed column value.
     */
    function sqlite_rtrim($column)
    {
        return "RTRIM($column)";
    }
}

if (!function_exists('sqlite_length')) {
    /**
     * Returns the SQL expression for getting the length of a column in SQLite.
     *
     * @param string $column The name of the column to get the length of.
     * @return string The SQL expression for getting the length of the column.
     */
    function sqlite_length($column)
    {
        return "LENGTH($column)";
    }
}

if (!function_exists('sqlite_replace')) {
    /**
     * Returns the SQL expression for replacing a substring in a SQLite column.
     *
     * @param string $column The name of the column to replace the substring in.
     * @param string $find The substring to be replaced.
     * @param string $replace The replacement substring.
     * @return string The SQL expression for replacing the substring in the column.
     */
    function sqlite_replace($column, $find, $replace)
    {
        return "REPLACE($column, '$find', '$replace')";
    }
}

if (!function_exists('sqlite_upper')) {
    /**
     * Returns the SQL expression for converting a SQLite column value to uppercase.
     *
     * @param string $column The name of the column to convert to uppercase.
     * @return string The SQL expression for converting the column value to uppercase.
     */
    function sqlite_upper($column)
    {
        return "UPPER($column)";
    }
}

if (!function_exists('sqlite_lower')) {
    /**
     * Returns the SQL expression for converting a SQLite column value to lowercase.
     *
     * @param string $column The name of the column to convert to lowercase.
     * @return string The SQL expression for converting the column value to lowercase.
     */
    function sqlite_lower($column)
    {
        return "LOWER($column)";
    }
}

if (!function_exists('sqlite_instr')) {
    /**
     * Returns the SQL expression for finding the position of a substring in a SQLite column.
     *
     * @param string $column The name of the column to search in.
     * @param string $substring The substring to search for.
     * @return string The SQL expression for finding the position of the substring.
     */
    function sqlite_instr($column, $substring)
    {
        return "INSTR($column, '$substring')";
    }
}

if (!function_exists('sqlite_coalesce')) {
    /**
     * Returns the SQL expression for the COALESCE function in SQLite, which returns the first non-NULL value from a list of expressions.
     *
     * @param mixed ...$columns The list of expressions to be evaluated.
     * @return string The SQL expression for the COALESCE function.
     */
    function sqlite_coalesce(...$columns)
    {
        return "COALESCE(" . implode(', ', $columns) . ")";
    }
}

if (!function_exists('sqlite_ifnull')) {
    /**
     * Returns the SQL expression for the IFNULL function in SQLite, which returns the value of the first argument if it is not NULL, otherwise it returns the second argument.
     *
     * @param string $column The name of the column to be checked for NULL.
     * @param string $default The value to be returned if the column is NULL.
     * @return string The SQL expression for the IFNULL function.
     */
    function sqlite_ifnull($column, $default)
    {
        return "IFNULL($column, '$default')";
    }
}

if (!function_exists('sqlite_iif')) {
    /**
     * Returns a string that represents an SQLite IIF function call.
     *
     * @param mixed $condition The condition to evaluate.
     * @param mixed $trueResult The value to return if the condition is true.
     * @param mixed $falseResult The value to return if the condition is false.
     * @return string The SQLite IIF function call string.
     */
    function sqlite_iif($condition, $trueResult, $falseResult)
    {
        return "IIF($condition, '$trueResult', '$falseResult')";
    }
}

if (!function_exists('sqlite_nullif')) {
    /**
     * Returns a string that represents an SQLite NULLIF function call.
     *
     * @param mixed $column1 The first column to be compared.
     * @param mixed $column2 The second column to be compared.
     * @return string The SQLite NULLIF function call string.
     */
    function sqlite_nullif($column1, $column2)
    {
        return "NULLIF($column1, $column2)";
    }
}

if (!function_exists('sqlite_date')) {
    /**
     * Returns a string that represents the SQLite DATE function call.
     *
     * @param string $timestring The time string to be formatted as a date.
     * @return string The SQLite DATE function call string.
     */
    function sqlite_date($timestring)
    {
        return "DATE('$timestring')";
    }
}

if (!function_exists('sqlite_time')) {
    /**
     * Returns a string that represents the SQLite TIME function call.
     *
     * @param string $timestring The time string to be formatted as a time.
     * @return string The SQLite TIME function call string.
     */
    function sqlite_time($timestring)
    {
        return "TIME('$timestring')";
    }
}

if (!function_exists('sqlite_datetime')) {
    /**
     * Returns a string that represents the SQLite DATETIME function call.
     *
     * @param string $timestring The time string to be formatted as a datetime.
     * @return string The SQLite DATETIME function call string.
     */
    function sqlite_datetime($timestring)
    {
        return "DATETIME('$timestring')";
    }
}

if (!function_exists('sqlite_julianday')) {
    /**
     * Returns the Julian day number for a given timestamp.
     *
     * @param string $timestring The timestamp to convert to a Julian day number.
     * @return string The Julian day number as a string.
     */
    function sqlite_julianday($timestring)
    {
        return "JULIANDAY('$timestring')";
    }
}

if (!function_exists('sqlite_strftime')) {
    /**
     * Returns a string that represents the SQLite STRFTIME function call.
     *
     * @param string $format The format string for the strftime function.
     * @param string $timestring The time string to be formatted.
     * @return string The SQLite STRFTIME function call string.
     */
    function sqlite_strftime($format, $timestring)
    {
        return "STRFTIME('$format', '$timestring')";
    }
}

if (!function_exists('sqlite_abs')) {
    /**
     * Returns the absolute value of a number.
     *
     * @param mixed $number The number to get the absolute value of.
     * @return string The absolute value of the number as a string.
     */
    function sqlite_abs($number)
    {
        return "ABS($number)";
    }
}

if (!function_exists('sqlite_random')) {
    /**
     * Returns a string representing a SQLite random function.
     *
     * @return string The SQLite random function as a string.
     */
    function sqlite_random()
    {
        return "RANDOM()";
    }
}

if (!function_exists('sqlite_round')) {
    /**
     * Returns a string representing a SQLite ROUND function call.
     *
     * @param mixed $number The number to round.
     * @param int $precision The number of digits to round to (default: 0).
     * @return string The SQLite ROUND function call string.
     */
    function sqlite_round($number, $precision = 0)
    {
        return "ROUND($number, $precision)";
    }
}

if (!function_exists('sqlite_and_equals')) {
    /**
     * Generates an SQL AND condition string based on an associative array of comparisons.
     *
     * @param array $comparasion An associative array of key-value pairs representing the comparisons.
     * @throws Exception If the comparasion format is invalid.
     * @return string The SQL AND condition string.
     */
    function sqlite_and_equals(array $comparasion)
    {
        if (!is_array($comparasion) && !Utils::isArrayAssoc($comparasion)) {
            throw new Exception("Invalid comparasion format.");
        }

        $comparasions = [];
        foreach ($comparasion as $key => $value) {
            if (is_string($value) && !is_raw_value($value)) {
                $value = "'$value'";
            }
            $comparasions[] = remove_quotes("$key = $value");
        }

        return implode(' AND ', $comparasions);
    }
}

if (!function_exists('sqlite_or_equals')) {
    /**
     * Generates an SQL OR condition string based on an associative array of comparisons.
     *
     * @param array $comparasion An associative array of key-value pairs representing the comparisons.
     * @throws Exception If the comparasion format is invalid.
     * @return string The SQL OR condition string.
     */
    function sqlite_or_equals(array $comparasion)
    {
        if (!is_array($comparasion) && !Utils::isArrayAssoc($comparasion)) {
            throw new Exception("Invalid comparasion format.");
        }

        $comparasions = [];
        foreach ($comparasion as $key => $value) {
            if (is_string($value) && !is_raw_value($value)) {
                $value = "'$value'";
            }
            $comparasions[] = remove_quotes("$key = $value");
        }

        return implode(' OR ', $comparasions);
    }
}

if (!function_exists('sqlite_equal')) {
    /**
     * Generates an SQL equality comparison string.
     *
     * @param string $comparison The column or expression to compare.
     * @param mixed $value The value to compare against. If a string, it will be quoted unless it is a raw value.
     * @return string The SQL equality comparison string.
     */
    function sqlite_equal(string $comparison, mixed $value = null): string
    {
        if (is_string($value) && !is_raw_value($value)) {
            return "$comparison = '$value'";
        }
        return remove_quotes("$comparison = $value");
    }
}

if (!function_exists('sqlite_less_than')) {
    /**
     * Generates an SQL less than comparison string.
     *
     * @param string $comparison The column or expression to compare.
     * @param mixed $value The value to compare against. If a string, it will be quoted unless it is a raw value.
     * @return string The SQL less than comparison string.
     */
    function sqlite_less_than(string $comparison, mixed $value): string
    {
        if (is_string($value) && !is_raw_value($value)) {
            return "$comparison < '$value'";
        }
        return remove_quotes("$comparison < $value");
    }
}

if (!function_exists('sqlite_greater_than')) {
    /**
     * Generates an SQL greater than comparison string.
     *
     * @param string $comparison The column or expression to compare.
     * @param mixed $value The value to compare against. If a string, it will be quoted unless it is a raw value.
     * @return string The SQL greater than comparison string.
     */
    function sqlite_greater_than(string $comparison, mixed $value): string
    {
        if (is_string($value) && !is_raw_value($value)) {
            return "$comparison > '$value'";
        }
        return remove_quotes("$comparison > $value");
    }
}

if (!function_exists('sqlite_less_than_or_equal')) {
    /**
     * Generates an SQL less than or equal comparison string.
     *
     * @param string $comparison The column or expression to compare.
     * @param mixed $value The value to compare against. If a string, it will be quoted unless it is a raw value.
     * @return string The SQL less than or equal comparison string.
     */
    function sqlite_less_than_or_equal(string $comparison, mixed $value): string
    {
        if (is_string($value) && !is_raw_value($value)) {
            return "$comparison <= '$value'";
        }
        return remove_quotes("$comparison <= $value");
    }
}

if (!function_exists('sqlite_greater_than_or_equal')) {
    /**
     * Generates an SQL greater than or equal comparison string.
     *
     * @param string $comparison The column or expression to compare.
     * @param mixed $value The value to compare against. If a string, it will be quoted unless it is a raw value.
     * @return string The SQL greater than or equal comparison string.
     */
    function sqlite_greater_than_or_equal(string $comparison, mixed $value): string
    {
        if (is_string($value) && !is_raw_value($value)) {
            return "$comparison >= '$value'";
        }
        return remove_quotes("$comparison >= $value");
    }
}

if (!function_exists('sqlite_not_equal')) {
    /**
     * Generates an SQL not equal comparison string.
     *
     * @param string $comparison The column or expression to compare.
     * @param mixed $value The value to compare against. If a string, it will be quoted unless it is a raw value.
     * @return string The SQL not equal comparison string.
     */
    function sqlite_not_equal(string $comparison, mixed $value): string
    {
        if (is_string($value) && !is_raw_value($value)) {
            return "$comparison <> '$value'";
        }
        return remove_quotes("$comparison <> $value");
    }
}

if (!function_exists('sqlite_is')) {
    /**
     * Generates an SQLite IS comparison string based on the given comparison and value.
     *
     * @param string $comparison The comparison operator to use.
     * @param mixed $value The value to compare against.
     * @return string The generated SQLite IS comparison string.
     */
    function sqlite_is(string $comparison, mixed $value): string
    {
        if (is_string($value) && !is_raw_value($value)) {
            return "$comparison IS '$value'";
        }
        return remove_quotes("$comparison IS $value");
    }
}

if (!function_exists('sqlite_is_not')) {
    /**
     * Generates an SQLite IS NOT comparison string based on the given comparison and value.
     *
     * @param string $comparison The column or expression to compare.
     * @param mixed $value The value to compare against.
     * @return string The generated SQLite IS NOT comparison string.
     */
    function sqlite_is_not(string $comparison, mixed $value): string
    {
        if (is_string($value) && !is_raw_value($value)) {
            return "$comparison IS NOT '$value'";
        }
        return remove_quotes("$comparison IS NOT $value");
    }
}

if (!function_exists('sqlite_in')) {
    /**
     * Generates an SQLite IN comparison string based on the given comparison and values.
     *
     * @param string $comparison The column or expression to compare.
     * @param array $values An array of values to compare against.
     * @return string The generated SQLite IN comparison string.
     */
    function sqlite_in(string $comparison, array $values): string
    {
        $values = array_map(function ($value) {
            return is_string($value) ? "'$value'" : $value;
        }, $values);
        return "$comparison IN (" . implode(', ', $values) . ")";
    }
}

if (!function_exists('sqlite_not_in')) {
    /**
     * Generates a SQLite "NOT IN" condition string.
     *
     * @param string $comparison The column or expression to compare against.
     * @param array $values The values to exclude from the comparison.
     * @return string The generated SQLite "NOT IN" condition string.
     */
    function sqlite_not_in(string $comparison, array $values): string
    {
        $values = array_map(function ($value) {
            return is_string($value) ? "'$value'" : $value;
        }, $values);
        return "$comparison NOT IN (" . implode(', ', $values) . ")";
    }
}

if (!function_exists('sqlite_like')) {
    /**
     * Generates an SQLite LIKE comparison string based on the given comparison and pattern.
     *
     * @param string $comparison The column or expression to compare.
     * @param string $pattern The pattern to match against.
     * @return string The generated SQLite LIKE comparison string.
     */
    function sqlite_like(string $comparison, string $pattern): string
    {
        return "$comparison LIKE '$pattern'";
    }
}

if (!function_exists('sqlite_glob')) {
    /**
     * Generates an SQLite GLOB comparison string based on the given comparison and pattern.
     *
     * @param string $comparison The column or expression to compare.
     * @param string $pattern The pattern to match against.
     * @return string The generated SQLite GLOB comparison string.
     */
    function sqlite_glob(string $comparison, string $pattern): string
    {
        return "$comparison GLOB '$pattern'";
    }
}

if (!function_exists('sqlite_match')) {
    /**
     * Generates an SQLite MATCH comparison string based on the given comparison and pattern.
     *
     * @param string $comparison The column or expression to compare.
     * @param string $pattern The pattern to match against.
     * @return string The generated SQLite MATCH comparison string.
     */
    function sqlite_match(string $comparison, string $pattern): string
    {
        return "$comparison MATCH '$pattern'";
    }
}

if (!function_exists('sqlite_regexp')) {
    /**
     * Generates an SQLite REGEXP comparison string based on the given comparison and pattern.
     *
     * @param string $comparison The column or expression to compare.
     * @param string $pattern The pattern to match against.
     * @return string The generated SQLite REGEXP comparison string.
     */
    function sqlite_regexp(string $comparison, string $pattern): string
    {
        return "$comparison REGEXP '$pattern'";
    }
}

if (!function_exists('sqlite_and')) {
    /**
     * Generates a string of SQL conditions joined by 'AND' from an array of conditions.
     *
     * @param array $conditions An array of conditions.
     * @return string The generated SQL conditions string.
     */
    function sqlite_and(array $conditions): string
    {
        return implode(' AND ', array_map('remove_quotes', $conditions));
    }
}

if (!function_exists('sqlite_or')) {
    /**
     * Generates a string of SQL conditions joined by 'OR' from an array of conditions.
     *
     * @param array $conditions An array of conditions.
     * @return string The generated SQL conditions string.
     */
    function sqlite_or(array $conditions): string
    {
        return implode(' OR ', array_map('remove_quotes', $conditions));
    }
}

if (!function_exists('sqlite_not')) {
    /**
     * Generates a string representation of a SQL NOT condition for the given condition.
     *
     * @param string $condition The condition to negate.
     * @return string The SQL NOT condition string.
     */
    function sqlite_not(string $condition): string
    {
        return "NOT ($condition)";
    }
}

if (!function_exists('sqlite_between')) {
    /**
     * Generates a string representation of a SQL BETWEEN condition.
     *
     * @param string $comparison The column or expression to compare.
     * @param mixed $start The lower bound of the range.
     * @param mixed $end The upper bound of the range.
     * @return string The SQL BETWEEN condition string.
     */
    function sqlite_between(string $comparison, mixed $start, mixed $end): string
    {
        return "$comparison BETWEEN $start AND $end";
    }
}

if (!function_exists('sqlite_concatenate')) {
    /**
     * Concatenates two SQLite columns using the "||" operator.
     *
     * @param string $column1 The first column to concatenate.
     * @param string $column2 The second column to concatenate.
     * @return string The concatenated columns.
     */
    function sqlite_concatenate(string $column1, string $column2): string
    {
        if (
            (is_string($column1) && !is_raw_value($column1)) ||
            (is_string($column2) && !is_raw_value($column2))
        ) {
            return "$column1 || $column2";
        }
        return remove_quotes("$column1 || $column2");
    }
}

if (!function_exists('sqlite_left_shift')) {
    /**
     * Returns a string representation of the left shift operation on a given string value by a specified number of bits.
     *
     * @param string $value The string value to be left shifted.
     * @param int $shift The number of bits to shift the string value by.
     * @return string The result of the left shift operation as a string.
     */
    function sqlite_left_shift(string $value, int $shift): string
    {
        if (
            (is_string($value) && !is_raw_value($value)) ||
            (is_string($shift) && !is_raw_value($shift))
        ) {
            return "$value << $shift";
        }
        return remove_quotes("$value << $shift");
    }
}

if (!function_exists('sqlite_right_shift')) {
    /**
     * Returns the result of performing a right shift operation on a given string value by a specified number of bits.
     *
     * @param string $value The string value to be right shifted.
     * @param int $shift The number of bits to shift the value to the right.
     * @return string The result of the right shift operation.
     */
    function sqlite_right_shift(string $value, int $shift): string
    {
        if (
            (is_string($value) && !is_raw_value($value)) ||
            (is_string($shift) && !is_raw_value($shift))
        ) {
            return "$value >> $shift";
        }
        return remove_quotes("$value >> $shift");
    }
}

if (!function_exists('sqlite_bitwise_and')) {
    /**
     * Returns the bitwise AND of two strings.
     *
     * @param string $value1 The first string.
     * @param string $value2 The second string.
     * @return string The result of the bitwise AND operation.
     */
    function sqlite_bitwise_and(string $value1, string $value2): string
    {
        if (
            (is_string($value1) && !is_raw_value($value1)) ||
            (is_string($value2) && !is_raw_value($value2))
        ) {
            return "$value1 & $value2";
        }
        return remove_quotes("$value1 & $value2");
    }
}

if (!function_exists('sqlite_bitwise_or')) {
    /**
     * Returns the bitwise OR of two strings.
     *
     * @param string $value1 The first string.
     * @param string $value2 The second string.
     * @return string The result of the bitwise OR operation.
     */
    function sqlite_bitwise_or(string $value1, string $value2): string
    {
        if (
            (is_string($value1) && !is_raw_value($value1)) ||
            (is_string($value2) && !is_raw_value($value2))
        ) {
            return "$value1 | $value2";
        }
        return remove_quotes("$value1 | $value2");
    }
}

if (!function_exists('sqlite_bitwise_not')) {
    /**
     * Returns the bitwise NOT of a string.
     *
     * @param string $value The string to be negated.
     * @return string The bitwise NOT of the input string.
     */
    function sqlite_bitwise_not(string $value): string
    {
        if (is_string($value) && !is_raw_value($value)) {
            return "~$value";
        }
        return "~" . remove_quotes($value);
    }
}

if (!function_exists('sqlite_add')) {
    /**
     * Adds two values together, returning a string representation of the sum.
     *
     * @param string $value1 The first value to be added.
     * @param string $value2 The second value to be added.
     * @return string The string representation of the sum.
     */
    function sqlite_add(string $value1, string $value2): string
    {
        if (
            (is_string($value1) && !is_raw_value($value1)) ||
            (is_string($value2) && !is_raw_value($value2))
        ) {
            return "$value1 + $value2";
        }
        return remove_quotes("$value1 + $value2");
    }
}

if (!function_exists('sqlite_subtract')) {
    /**
     * Subtracts two values and returns the result as a string.
     *
     * @param string $value1 The first value to subtract.
     * @param string $value2 The second value to subtract.
     * @return string The result of the subtraction.
     */
    function sqlite_subtract(string $value1, string $value2): string
    {
        if (
            (is_string($value1) && !is_raw_value($value1)) ||
            (is_string($value2) && !is_raw_value($value2))
        ) {
            return "$value1 - $value2";
        }
        return remove_quotes("$value1 - $value2");
    }
}

if (!function_exists('sqlite_multiply')) {
    /**
     * Multiplies two values and returns the result as a string.
     *
     * @param string $value1 The first value to multiply.
     * @param string $value2 The second value to multiply.
     * @return string The result of the multiplication.
     */
    function sqlite_multiply(string $value1, string $value2): string
    {
        if (
            (is_string($value1) && !is_raw_value($value1)) ||
            (is_string($value2) && !is_raw_value($value2))
        ) {
            return "$value1 * $value2";
        }
        return remove_quotes("$value1 * $value2");
    }
}

if (!function_exists('sqlite_divide')) {
    /**
     * Divides two values and returns the result as a string.
     *
     * @param string $value1 The first value to divide.
     * @param string $value2 The second value to divide.
     * @return string The result of the division.
     */
    function sqlite_divide(string $value1, string $value2): string
    {
        if (
            (is_string($value1) && !is_raw_value($value1)) ||
            (is_string($value2) && !is_raw_value($value2))
        ) {
            return "$value1 / $value2";
        }
        return remove_quotes("$value1 / $value2");
    }
}

if (!function_exists('sqlite_modulus')) {
    /**
     * Calculates the modulus of two values and returns the result as a string.
     *
     * @param string $value1 The first value.
     * @param string $value2 The second value.
     * @return string The result of the modulus operation.
     */
    function sqlite_modulus(string $value1, string $value2): string
    {
        if (
            (is_string($value1) && !is_raw_value($value1)) ||
            (is_string($value2) && !is_raw_value($value2))
        ) {
            return "$value1 % $value2";
        }
        return remove_quotes("$value1 % $value2");
    }
}

if (!function_exists('sqlite_operators')) {
    /**
     * Returns an array of SQLite operators.
     *
     * @return array An array of SQLite operators.
     */
    function sqlite_operators()
    {
        $sqlite_operators = [
            '=',     // Equal
            '<',     // Less than
            '>',     // Greater than
            '<=',    // Less than or equal
            '>=',    // Greater than or equal
            '<>',    // Not equal
            '!=',    // Not equal (alternate)
            'IS',    // Is
            'IS NOT',// Is not
            'IN',    // In
            'NOT IN',// Not in
            'LIKE',  // Like
            'GLOB',  // Glob
            'MATCH', // Match
            'REGEXP',// Regular expression
            'AND',   // Logical AND
            'OR',    // Logical OR
            'NOT',   // Logical NOT
            'BETWEEN', // Between
            '||',    // String concatenation
            '<<',    // Left shift
            '>>',    // Right shift
            '&',     // Bitwise AND
            '|',     // Bitwise OR
            '~',     // Bitwise NOT
            '+',     // Addition
            '-',     // Subtraction
            '*',     // Multiplication
            '/',     // Division
            '%',     // Modulus
        ];
        return $sqlite_operators;
    }
}

if (!function_exists('useDB')) {
    /**
     * Returns a new instance of LibSQL using the database connection details
     * stored in the environment variables DB_URL and DB_TOKEN.
     *
     * @return LibSQL A new instance of LibSQL with the database connection details.
     */
    function useDB()
    {
        $dbname = libsql_url();
        $authToken = libsql_token();

        return new LibSQL("dbname=$dbname&authToken=$authToken");
    }
}

if (!function_exists('useQueryBuilder')) {
    /**
     * Returns a new instance of LibSQLQueryBuilder using the database connection
     * returned by the useDB() function.
     *
     * @return LibSQLQueryBuilder A new instance of LibSQLQueryBuilder.
     */
    function useQueryBuilder()
    {
        $db = useDB();
        $builder = new LibSQLQueryBuilder($db);
        return $builder;
    }
}

if (!function_exists('libsql_version')) {
    /**
     * Retrieves the version of the LibSQL database.
     *
     * @return string The version of the LibSQL database.
     */
    function libsql_version()
    {
        return useDB()->version();
    }
}

if (!function_exists('libsql_select_all_from')) {
    /**
     * Retrieves all records from the specified table using the LibSQLQueryBuilder.
     *
     * @param string $table The name of the table to retrieve records from.
     * @return mixed The result of the query, which can be an array of records or a specific object.
     */
    function libsql_select_all_from(string $table)
    {
        return useQueryBuilder()->get($table);
    }
}

if (!function_exists('libsql_select_from')) {
    /**
     * Retrieves records from the specified table using the LibSQLQueryBuilder.
     *
     * @param string $table The name of the table to retrieve records from.
     * @param array|string $columns The columns to select from the table.
     * @return mixed The result of the query, which can be an array of records or a specific object.
     */
    function libsql_select_from(string $table, array|string $columns)
    {
        return useQueryBuilder()->table($table)->select($columns)->get();
    }
}

if (!function_exists('libsql_select_order')) {
    /**
     * Retrieves records from the specified table, ordered by a given field in a specified direction.
     *
     * @param string $table The name of the table to retrieve records from.
     * @param array|string $columns The columns to select from the table.
     * @param string $order The field to order the records by.
     * @param string $direction The direction to order the records in. Defaults to 'asc'.
     * @return mixed The result of the query, which can be an array of records or a specific object.
     */
    function libsql_select_order(string $table, array|string $columns, string $order, string $direction = 'asc')
    {
        $direction = !in_array($direction, ['asc', 'desc']) ? 'asc' : 'desc';
        return useQueryBuilder()
            ->table($table)
            ->select($columns)
            ->orderBy($order, $direction)
            ->get();
    }
}

if (!function_exists('use_raw_value')) {
    /**
     * Returns a raw value from the query builder.
     *
     * @param string $value The raw value to be used.
     * @return mixed The raw value from the query builder.
     */
    function use_raw_value(string $value)
    {
        $builder = useQueryBuilder();
        return $builder->rawValue($value);
    }
}

if (!function_exists('use_raw_query')) {
    function use_raw_query(string $query, array $params = [], int $return = LibSQL::LIBSQL_ASSOC)
    {
        $db = useDB();
        $prepare = $db->prepare($query);
        $results = $prepare->query($params)->fetchArray($return);

        return $results;
    }
}

if (!function_exists('use_raw_execute')) {
    function use_raw_execute(string $query, array $params = [])
    {
        $db = useDB();
        $prepare = $db->prepare($query);
        $results = $prepare->execute($params);

        return $results;
    }
}

if (!function_exists('explain')) {
    /**
     * Explains a SQL query by retrieving the execution plan from the database.
     *
     * @param string $query The SQL query to explain.
     * @return array The execution plan of the SQL query as an associative array.
     */
    function explain(string $query)
    {
        $builder = useQueryBuilder();
        return $builder->explain($query);
    }
}

if (!function_exists('str_remove_word_begin_with')) {
    /**
     * Removes a specified keyword from the beginning of a SQL query string.
     *
     * @param string $keyword The keyword to remove from the beginning of the string.
     * @param string $query The SQL query string.
     * @return string The modified SQL query string.
     */
    function str_remove_word_begin_with($keyword, $query)
    {
        // Trim whitespace from the beginning of the query string
        $query = ltrim($query);

        // Define a pattern to match the keyword at the beginning of the query string
        $pattern = '/^' . preg_quote($keyword, '/') . '\s+/i';

        // Remove the keyword if it exists at the beginning
        $query = preg_replace($pattern, '', $query);

        return $query;
    }
}

if (!function_exists('remove_quotes')) {
    /**
     * Removes single and double quotes from a given string.
     *
     * @param string $string The input string from which quotes will be removed.
     * @return string The modified string with quotes removed.
     */
    function remove_quotes(string $string)
    {
        foreach (sqlite_functions() as $function) {
            if (stripos(strtolower($string), strtolower($function)) !== false || stripos($string, $function) !== false) {
                $string = str_replace('|>rawValue', '', $string);
                $pattern = '/(?<!\w)' . preg_quote($function, '/') . '(?!\w)/i';
                $string = preg_replace_callback($pattern, function ($matches) use ($function) {
                    return $function;
                }, $string);

                $pattern = '/\'(\w+)\s*\(\s*\'(.*?)\'\s*\)\s*\'|\"(\w+)\s*\(\s*\"(.*?)\"\s*\)\"/';
                $string = preg_replace_callback($pattern, function ($matches) {
                    if (!empty($matches[1])) {
                        return $matches[1] . '(' . $matches[2] . ')';
                    }
                    if (!empty($matches[3])) {
                        return $matches[3] . '(' . $matches[4] . ')';
                    }
                    return '';
                }, $string);

                return $string;
            }
        }

        if (strpos($string, "''") !== false) {
            $query = str_replace("''", "'", $string);
            $query = str_replace('|>rawValue', '', $query);
            if (preg_match("/= (.*)/", $query, $matches)) {
                $value = trim($matches[1]);

                if (preg_match("/^'(.*)'$/", $value, $quoteMatches) || preg_match('/^"(.*)"$/', $value, $quoteMatches)) {
                    $value = $quoteMatches[1];
                }

                $updatedQuery = preg_replace("/= .*/", "= $value", $query);
                return $updatedQuery;
            }
            return $query;
        }

        $value = str_replace(['\\"', "'"], '', $string);
        $value = str_replace('|>rawValue', '', $value);
        return $value;
    }
}

if (!function_exists('is_has_sqlite_functions')) {
    /**
     * Checks if a given SQL query contains any of the SQLite functions.
     *
     * @param string|array $sql The SQL query to check.
     * @return bool Returns true if the query contains any of the SQLite functions, false otherwise.
     */
    function is_has_sqlite_functions(string|array $sql)
    {
        if (is_array($sql)) {
            foreach ($sql as $query) {
                if (is_has_sqlite_functions($query)) {
                    return true;
                }
            }
        } else {
            foreach (sqlite_functions() as $function) {
                if (stripos(strtolower($sql), strtolower($function)) !== false || stripos($sql, $function) !== false) {
                    return true;
                }
            }
        }

        return false;
    }
}

if (!function_exists('is_has_sqlite_operators')) {
    /**
     * Checks if a given SQL query contains any of the SQLite operators.
     *
     * @param string $sql The SQL query to check.
     * @return bool Returns true if the query contains any of the SQLite operators, false otherwise.
     */
    function is_has_sqlite_operators(string $sql)
    {
        foreach (sqlite_operators() as $operator) {
            if (strpos($sql, $operator) !== false) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('sqlite_master_type')) {
    function sqlite_master_type(string|array $type)
    {
        if (is_array($type)) {
            $types = implode(',', array_map(fn($value) => "'{$value}'", $type));
            $sql = "SELECT type, name,  tbl_name, sql FROM sqlite_master WHERE type IN ($types)";
            $results = useDB()->query($sql)->fetchArray(LibSQL::LIBSQL_ASSOC);
            return $results;
        } else {
            $sql = "SELECT type, name,  tbl_name, sql FROM sqlite_master WHERE type = ?";
            $results = useDB()->prepare($sql)->query([$type])->fetchArray(LibSQL::LIBSQL_ASSOC);
            return $results;
        }
    }
}

if (!function_exists('has_potential_injection')) {
    function has_potential_injection(string|array $input)
    {
        // Common SQL injection patterns and keywords
        $patterns = [
            "/admin' --/i",
            "/admin' #/i",
            "/admin'\/\*/i",
            "/' or 1=1--/i",
            "/' or 1=1#/i",
            "/' or 1=1\/\*/i",
            "/'\) or \('1'='1/i",
            "/' UNION SELECT .+ FROM .+--/i",
            "/1 UNION SELECT .+ FROM .+--/i",
            "/';UPDATE .+ SET .+ WHERE .+;--/i",
            "/'; GO EXEC cmdshell\('.+'\) --/i",
            '/--/i',       // SQL comment
            '/;/i',       // SQL statement terminator
        ];

        // Check input against each pattern
        if (is_array($input)) {
            foreach ($patterns as $pattern) {
                foreach ($input as $inp) {
                    if (preg_match($pattern, $inp)) {
                        return true; // Pattern found
                    }
                }
            }
        } else {
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $input)) {
                    return true; // Pattern found
                }
            }
        }
        return false; // No pattern found
    }
}

if (!function_exists('is_raw_value')) {
    /**
     * Check if value contains "|>rawValue"
     *
     * @param string|array $value The value to check.
     * @return bool Returns true if value contains "|>rawValue", false otherwise.
     */
    function is_raw_value(string|array $value)
    {
        // Check if value contains "|>rawValue"
        if (is_array($value)) {
            foreach ($value as $val) {
                if (strpos($val, '|>rawValue') !== false) {
                    return true;
                }
            }
        } else {
            return strpos($value, '|>rawValue') !== false;
        }
        return false;
    }
}

if (!function_exists('is_nested_array')) {
    /**
     * Checks if a given array is nested, meaning it contains sub-arrays.
     *
     * @param array $array The array to check.
     * @return bool Returns true if the array is nested, false otherwise.
     */
    function is_nested_array(array $array)
    {
        if (!is_array($array)) {
            return false;
        }

        foreach ($array as $element) {
            if (is_array($element)) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('strim')) {
    /**
     * Trims whitespace from a string and replaces multiple spaces with a single space.
     *
     * @param string $input The input string to be trimmed.
     * @return string The trimmed string.
     */
    function strim($input)
    {
        $trimmed = preg_replace('/\s+/', ' ', $input);
        return trim($trimmed);
    }
}

if (!function_exists('is_expensive_query')) {
    /**
     * Checks if the given SQLite query plan indicates an expensive query.
     *
     * @param array $explainQueryPlanResults The results from EXPLAIN QUERY PLAN.
     * @return bool True if the query is potentially expensive, false otherwise.
     */
    function is_expensive_query(array $explainQueryPlanResults): bool
    {
        // Regex patterns for identifying expensive query characteristics
        $expensivePatterns = [
            '/SCAN TABLE/i',                     // Full table scan
            '/^SCAN \w+$/i',                     // Specific table scan
            '/USING TEMP B-TREE/i',              // Temporary B-Tree used for sorting or joining
            '/USING FILESORT/i',                 // Filesort operation
            '/SUBQUERY/i',                       // Subqueries
            '/CORRELATED SCALAR SUBQUERY/i',     // Specific type of subquery
            '/SEARCH/i',                         // Searching an index
            '/WITHOUT USING INDEX/i',            // Operation without index
            '/TABLE/i',                          // Full table operations
            '/NESTED LOOP/i'                     // Nested loop join
        ];

        foreach ($explainQueryPlanResults as $result) {
            foreach ($expensivePatterns as $pattern) {
                if (preg_match($pattern, $result['detail'])) {
                    return true;
                }
            }
        }

        return false;
    }
}

if (!function_exists('set_background')) {
    /**
     * Sets the background color of the input string based on the specified color.
     *
     * @param mixed $string The input string to set the background color for.
     * @param string $color The color to apply to the background. Defaults to 'green'.
     * @return mixed The input string with the specified background color applied.
     */
    function set_background($string, string $color = 'green')
    {
        $colors = [
            'green' => function ($string) {
                $boldGreenBgWhite = "\033[1;42;37m";
                $reset = "\033[0m";
                return "{$boldGreenBgWhite}{$string}{$reset}";
            },
            'red' => function ($string) {
                $boldRedBgWhite = "\033[1;41;37m";
                $reset = "\033[0m";
                return "{$boldRedBgWhite}{$string}{$reset}";
            },
            'yellow' => function ($string) {
                $boldYellowBgBlack = "\033[1;43;30m";
                $reset = "\033[0m";
                return "{$boldYellowBgBlack}{$string}{$reset}";
            }
        ];

        if (php_sapi_name() === 'cli') {
            if (isset($colors[$color])) {
                return $colors[$color]($string);
            } else {
                return $colors['green']($string);
            }
        }

        return $string;
    }
}

if (!function_exists('set_bold')) {
    /**
     * Sets the given string to be displayed in bold text if running in a CLI environment.
     *
     * @param string $string The string to be displayed in bold text.
     * @return string The string with the appropriate ANSI escape codes for bold text.
     */
    function set_bold($string)
    {
        $boldStart = "\033[1m";  // ANSI escape code for bold text
        $reset = "\033[0m";      // ANSI escape code to reset text formatting

        if (php_sapi_name() === 'cli') {
            return "{$boldStart}{$string}{$reset}";
        }

        return $string;
    }
}

if (!function_exists('isLocalDev')) {
    /**
     * Checks if the given database is a local development database.
     *
     * @param string|array $database The database to check. Can be a string or an array.
     *                              If an array, it should contain only one element.
     * @return bool Returns true if the database is a local development database, false otherwise.
     */
    function isLocalDev(string|array $database)
    {
        if (is_array($database)) {
            return count($database) === 1 && (preg_match('/127\.0\.0\.1|localhost/', $database[0]) || str_contains($database[0], 'http://'));
        }
        return preg_match('/127\.0\.0\.1|localhost/', $database) || str_contains($database, 'http://');
    }
}

if (!function_exists('validate_sql_syntax')) {
    /**
     * Validates the syntax of a given SQL query or array of queries.
     *
     * @param string|array $queryString The SQL query or array of queries to validate.
     * @param bool $log Whether to return the validation results or a boolean indicating whether all queries are valid. Defaults to false.
     * @throws Exception If an error occurs during query execution.
     * @return array|bool An array of validation results if $log is true, or a boolean indicating whether all queries are valid if $log is false.
     */
    function validate_sql_syntax(string|array $queryString, bool $log = false)
    {
        if (!is_array($queryString)) {
            $minified = str_replace(PHP_EOL, ' ', $queryString);
            $queries = array_filter(array_map('trim', explode(';', $minified)));
        } else {
            $queries = $queryString;
        }

        $db = new SQLite3(':memory:');

        $results = [];

        foreach ($queries as $query) {
            $query = trim($query);

            if (empty($query)) {
                continue;
            }

            try {
                error_reporting(0);
                $result = $db->exec($query);

                if ($result === false) {
                    $error = $db->lastErrorMsg();
                    $results[] = [
                        'query' => $query,
                        'valid' => false,
                        'error' => $error
                    ];
                } else {
                    $results[] = [
                        'query' => $query,
                        'valid' => true
                    ];
                }
            } catch (Exception $e) {
                $results[] = [
                    'query' => $query,
                    'valid' => false,
                    'error' => $e->getMessage()
                ];
            }
        }

        $db->close();

        if ($log) {
            return $results;
        }

        return array_key_exists('error', $results);
    }
}

if (!function_exists('libsql_timezone')) {
    function libsql_timezone()
    {
        $db_prefix = getenv('DB_TIMEZONE');
        $turso_prefix = getenv('TURSO_TIMEZONE');

        if (!empty($db_prefix)) {
            return $db_prefix;
        }

        if (!empty($turso_prefix)) {
            return $turso_prefix;
        }

        return null;
    }
}

if (!function_exists('libsql_url')) {
    function libsql_url()
    {
        $db_prefix = getenv('DB_URL');
        $turso_prefix = getenv('TURSO_URL');

        if (!empty($db_prefix)) {
            return $db_prefix;
        }

        if (!empty($turso_prefix)) {
            return $turso_prefix;
        }

        return null;
    }
}

if (!function_exists('libsql_token')) {
    function libsql_token()
    {
        $db_prefix = getenv('DB_TOKEN');
        $turso_prefix = getenv('TURSO_TOKEN');

        if (!empty($db_prefix)) {
            return $db_prefix;
        }

        if (!empty($turso_prefix)) {
            return $turso_prefix;
        }

        return null;
    }
}

if (!function_exists('libsql_strict_query')) {
    function libsql_strict_query()
    {
        $db_prefix = getenv('DB_STRICT_QUERY');
        $turso_prefix = getenv('TURSO_STRICT_QUERY');

        if (!empty($db_prefix)) {
            return $db_prefix;
        }

        if (!empty($turso_prefix)) {
            return $turso_prefix;
        }

        return null;
    }
}

if (!function_exists('libsql_log_debug')) {
    function libsql_log_debug()
    {
        $db_prefix = getenv('DB_LOG_DEBUG');
        $turso_prefix = getenv('TURSO_LOG_DEBUG');

        if (!empty($db_prefix)) {
            return $db_prefix;
        }

        if (!empty($turso_prefix)) {
            return $turso_prefix;
        }

        return null;
    }
}

if (!function_exists('libsql_log_name')) {
    function libsql_log_name()
    {
        $db_prefix = getenv('DB_LOG_NAME');
        $turso_prefix = getenv('TURSO_LOG_NAME');

        if (!empty($db_prefix)) {
            return $db_prefix;
        }

        if (!empty($turso_prefix)) {
            return $turso_prefix;
        }

        return null;
    }
}

if (!function_exists('libsql_log_path')) {
    function libsql_log_path()
    {
        $db_prefix = getenv('DB_LOG_PATH');
        $turso_prefix = getenv('TURSO_LOG_PATH');

        if (!empty($db_prefix)) {
            return $db_prefix;
        }

        if (!empty($turso_prefix)) {
            return $turso_prefix;
        }

        return null;
    }
}
