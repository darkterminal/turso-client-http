<?php

namespace Darkterminal\TursoHttp\core\Http;

/**
 * Source from: https://github.com/richan-fongdasen/turso-laravel/blob/main/src/Enums/TursoType.php
 */
enum LibSQLTypes: string
{
    case NULL = 'null';
    case INTEGER = 'integer';
    case FLOAT = 'float';
    case TEXT = 'text';
    case BLOB = 'blob';

    public static function fromValue(mixed $value): static
    {
        $result = match (gettype($value)) {
            'NULL' => self::NULL,
            'boolean', 'integer' => self::INTEGER,
            'double', 'float' => self::FLOAT,
            'string' => self::fromString($value),

            default => null,
        };

        return ($result !== null)
            ? $result
            : self::TEXT;
    }

    public static function fromString(string $value): self
    {
        if (! ctype_print($value) || ! mb_check_encoding($value, 'UTF-8')) {
            return self::BLOB;
        }

        return self::TEXT;
    }

    public function bind(mixed $value): array
    {
        return match ($this) {
            self::NULL => [
                'type'  => $this->value,
                'value' => 'null',
            ],
            self::FLOAT => [
                'type'  => $this->value,
                'value' => $value,
            ],
            self::BLOB => [
                'type'   => $this->value,
                'base64' => base64_encode(base64_encode($value)),
            ],

            default => [
                'type'  => $this->value,
                'value' => (string) $value,
            ],
        };
    }

    /**
     * Decode a Hrana-v3 typed cell back into a plain PHP value.
     * Raw (v2-style) values pass through unchanged.
     *
     * @param mixed $cell A cell from an HTTP response result row.
     * @return mixed
     */
    public static function decode(mixed $cell): mixed
    {
        if (!is_array($cell) || !isset($cell['type'])) {
            return $cell;
        }

        return match ($cell['type']) {
            'null' => null,
            'integer' => PHP_INT_SIZE === 8
                ? (int) $cell['value']
                : (string) $cell['value'],
            'float' => isset($cell['value']) ? (float) $cell['value'] : null,
            'text' => $cell['value'] ?? '',
            'blob' => isset($cell['base64']) ? base64_decode($cell['base64'], true) : (base64_decode($cell['value'] ?? '', true) ?: null),
            default => $cell,
        };
    }

    /**
     * Decode a full result row (array of typed cells).
     *
     * @param array $row Row as returned by the HTTP pipeline.
     * @return array
     */
    public static function decodeRow(array $row): array
    {
        return array_map([self::class, 'decode'], $row);
    }
}
