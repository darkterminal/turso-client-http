<?php

namespace Darkterminal\TursoHttp\core;

/**
 * Interface Response
 *
 * Represents an HTTP response.
 */
interface Response
{
    /**
     * Get the response as an array.
     *
     * @return array The response data as an array.
     */
    public function get(): array;

    /**
     * Get the response as a JSON string or array.
     *
     * @return string|array|null The response data as a JSON string, array, or null if not applicable.
     */
    public function toJSON(): string|array|null;
}
