<?php

declare(strict_types=1);

namespace Darkterminal\TursoHttp\Laravel;

use Darkterminal\TursoHttp\LibSQL;
use Illuminate\Support\ServiceProvider;

/**
 * Darkterminal TursoHTTP service provider.
 *
 * Exposes a shared `turso` binding for the LibSQL HTTP client configured via
 * the `turso-http` config (publish with: php artisan vendor:publish --provider="Darkterminal\TursoHttp\Laravel\TursoServiceProvider").
 */
class TursoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/turso-http.php', 'turso-http');

        $this->app->singleton('turso', function ($app): LibSQL {
            $config = $app['config']['turso-http'];

            $url = (string) ($config['url'] ?? '');
            $token = (string) ($config['token'] ?? '');
            $options = (array) ($config['options'] ?? []);

            return new LibSQL("dbname={$url}&authToken={$token}", $options);
        });

        $this->app->bind(LibSQL::class, fn ($app) => $app['turso']);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/turso-http.php' => config_path('turso-http.php'),
        ], 'turso-http-config');
    }
}
