<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Darkterminal\TursoHttp\Laravel\Turso;
use Darkterminal\TursoHttp\LibSQL;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Command;

class ProofCommand extends Command
{
    protected $signature = 'proof:run';
    protected $description = 'End-to-end proof of darkterminal/turso-client-http inside a fresh Laravel 12 app';

    public function handle(): int
    {
        $checks = [];
        $ok = function (string $name, bool $cond) use (&$checks): void {
            $checks[$name] = $cond;
            $this->output->writeln(($cond ? '  PASS ' : '  FAIL ') . $name);
        };

        // 1) TursoServiceProvider was auto-discovered and binding exists
        $loaded = App::getLoadedProviders();
        $ok('service_provider_registered', isset($loaded[\Darkterminal\TursoHttp\Laravel\TursoServiceProvider::class]));

        // 2) Facade accessor resolves to the shared LibSQL client
        $ok('facade_resolves', Turso::getFacadeRoot() instanceof LibSQL);

        // 3) connectivity
        $version = Turso::version();
        $ok('version_non_empty', is_string($version) && $version !== '');

        // 4) raw DDL+DML through facade
        Turso::execute('CREATE TABLE IF NOT EXISTS proof_clients (id INTEGER PRIMARY KEY, name TEXT)');
        Turso::execute("INSERT INTO proof_clients (name) VALUES ('eloquent')");

        $result = Turso::query('SELECT count(*) FROM proof_clients WHERE name = ?', ['eloquent']);
        $count = (int) $result->fetchArray(LibSQL::LIBSQL_ASSOC)[0]['count(*)'];
        $ok('facade_round_trip', $count === 1);

        // 5) transaction shipyards
        $tx = Turso::transaction('deferred');
        $tx->execute("INSERT INTO proof_clients (name) VALUES ('transactional')");
        $tx->commit();

        $ok('transaction_committed', (int) Turso::query("SELECT count(*) FROM proof_clients WHERE name = 'transactional'")->fetchArray(LibSQL::LIBSQL_ASSOC)[0]['count(*)'] === 1);

        // 6) cleanup
        Turso::execute('DROP TABLE IF EXISTS proof_clients');

        $pass = count(array_filter($checks));
        $fail = count($checks) - $pass;
        $this->output->writeln('PROOF-LARAVEL: ' . json_encode(['ok' => $pass, 'fail' => $fail, 'all_ok' => $fail === 0, 'checks' => $checks]));

        return $fail === 0 ? self::SUCCESS : self::FAILURE;
    }
}
