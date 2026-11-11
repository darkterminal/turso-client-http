<?php

declare(strict_types=1);

namespace Darkterminal\TursoHttp\core;

class LibSQLError extends \Exception
{
    /**
     * @param string $message Human-readable description of the failure.
     * @param string $code    Machine-readable code supplied by the server or this library.
     */
    public function __construct(string $message, string $code)
    {
        parent::__construct($message, $this->normalizeCode($code));
    }

    private function normalizeCode(string $code): int
    {
        return (ctype_digit($code) ? (int) $code : 0);
    }
}
