<?php

declare(strict_types=1);

namespace Darkterminal\TursoHttp\core\Http;

use Darkterminal\TursoHttp\LibSQL;
use DateTime;

/**
 * Represents the result of a LibSQL query.
 *
 * Typed Hrana-v3 cells are normalized to plain PHP values in the constructor,
 * so every fetcher (ASSOC / NUM / BOTH / ALL) returns consistent data.
 */
class LibSQLResult
{
    protected array $results;
    protected array $cols;
    protected array $rows;
    protected int $rows_read;
    protected int $rows_written;
    protected float $query_duration_ms;

    public string|null $baton;

    public string|null $base_url;

    public function __construct(array $results)
    {
        $this->baton = $results['baton'] ?? null;
        $this->base_url = $results['base_url'] ?? null;
        $this->results = \Darkterminal\TursoHttp\core\Utils::removeCloseResponses($results['results']);
        $this->rows_read = $this->results['rows_read'] ?? 0;
        $this->rows_written = $this->results['rows_written'] ?? 0;
        $this->query_duration_ms = $this->results['query_duration_ms'] ?? 0.0;
        $this->cols = $this->results['cols'] ?? [];
        $this->rows = array_map(
            [LibSQLTypes::class, 'decodeRow'],
            $this->results['rows'] ?? [],
        );
    }

    /** Fetch the result set as an array. */
    public function fetchArray(int $mode = LibSQL::LIBSQL_BOTH): array
    {
        if ($mode === LibSQL::LIBSQL_ALL) {
            return $this->results;
        }

        return match ($mode) {
            LibSQL::LIBSQL_ASSOC => $this->getAssocRows(),
            LibSQL::LIBSQL_NUM => $this->getNumRows(),
            default => array_map(
                static fn (array $assoc, array $num) => array_merge($assoc, $num),
                $this->getAssocRows(),
                $this->getNumRows(),
            ),
        };
    }

    /** Lazily iterate rows as [assoc => value, num => value] pairs. */
    public function lazyFetchArray(): \Generator
    {
        $merged = array_merge($this->getAssocRows(), $this->getNumRows());
        foreach ($merged as $data) {
            yield $data;
        }
    }

    /** Finalize the result set. */
    public function finalize(): void
    {
        // nothing to free — rows are self-contained arrays
    }

    /** Reset the result set. */
    public function reset(): void
    {
        // rows are read-only after construction
    }

    /** Query execution statistics. */
    public function getStats(): array
    {
        return [
            'rows_read' => $this->rows_read,
            'rows_written' => $this->rows_written,
            'query_duration_ms' => $this->query_duration_ms,
        ];
    }

    public function columnName(int $column): string
    {
        return $this->cols[$column]['name'] ?? throw new \InvalidArgumentException("Column index $column out of range");
    }

    public function columnType(int $column): ?string
    {
        return $this->cols[$column]['decltype'] ?? null;
    }

    public function numColumns(): int
    {
        return count($this->cols);
    }

    /** @return list<array<string, mixed>> */
    private function getAssocRows(): array
    {
        $columns = array_map(
            static fn (array $col) => $col['name'] ?? '',
            $this->cols,
        );

        $results = [];
        foreach ($this->rows as $row) {
            $assoc = [];
            foreach ($columns as $i => $name) {
                $assoc[$name] = $this->castCell($this->columnType($i), $row[$i] ?? null);
            }
            $results[] = $assoc;
        }

        return $results;
    }

    /** @return list<list<mixed>> */
    private function getNumRows(): array
    {
        $results = [];
        foreach ($this->rows as $row) {
            $num = [];
            foreach ($row as $i => $value) {
                $num[] = $this->castCell($this->columnType($i), $value);
            }
            $results[] = $num;
        }

        return $results;
    }

    /** @return mixed */
    private function castCell(?string $columnType, mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        if (is_bool($value) || is_int($value) || is_float($value)) {
            return $value;
        }

        if (is_string($value) && ($columnType === 'blob' || self::isBlobValue($value))) {
            // rows are already decoded values; raw BLOBs arrive base64-wrapped
            $decoded = base64_decode(base64_decode($value, true), true);
            return $decoded !== false ? $decoded : $value;
        }

        if (is_string($value) && $this->isValidDateTime($value)) {
            $timezone = \Darkterminal\TursoHttp\core\Enums\Timezone::fromString(libsql_timezone() ?: 'UTC');
            if ($timezone) {
                return $timezone->convertFromUtc($value);
            }
        }

        return $value;
    }

    private static function isBlobValue(string $value): bool
    {
        $inner = base64_decode($value, true);
        if ($inner === false) {
            return false;
        }

        return base64_encode($inner) === $value && ctype_print($inner);
    }

    private function isValidDateTime(string $string, string $format = 'Y-m-d H:i:s'): bool
    {
        $dateTime = DateTime::createFromFormat($format, $string);

        return $dateTime && $dateTime->format($format) === $string;
    }
}
