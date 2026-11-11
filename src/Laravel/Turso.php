<?php

declare(strict_types=1);

namespace Darkterminal\TursoHttp\Laravel;

use Illuminate\Support\Facades\Facade;

/**
 * Turso HTTP client facade — access the shared \Darkterminal\TursoHttp\LibSQL binding.
 *
 * @method static string version()
 * @method static \Darkterminal\TursoHttp\core\Http\LibSQLResult query(string $sql, array $parameters = [])
 * @method static int execute(string $sql, array $parameters = [])
 * @method static bool isAutoCommit()
 * @method static void close()
 * @see \Darkterminal\TursoHttp\LibSQL
 */
class Turso extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'turso';
    }
}
