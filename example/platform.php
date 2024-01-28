<?php

use Darkterminal\TursoHttp\core\Platform\APITokens;
use Darkterminal\TursoHttp\core\Platform\Databases;
use Darkterminal\TursoHttp\core\Platform\Groups;

require_once getcwd() . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

$token = 'your-api-token'; // Generate using turso cli

$group = new Groups($token);
// echo $group->list('darkterminal')->toJSON();
// echo $group->create('darkterminal', 'default')->toJSON();
// echo $group->get_group('darkterminal', 'default')->toJSON();
// echo $group->get_group('darkterminal', 'default')->toJSON();
// echo $group->transfer('darkterminal', 'default', 'new_group')->toJSON();

// $database = new Databases($token);
// echo $database->list('darkterminal')->toJSON();
// echo $database->create('darkterminal', 'test-database', 'default')->toJSON();
// echo $database->get_database('darkterminal', 'test-database')->toJSON();
// echo $database->usage('darkterminal', 'test-database')->toJSON();
// echo $database->delete('darkterminal', 'test-database')->toJSON();
// echo $database->list_instances('darkterminal', 'test-database')->toJSON();
// echo $database->get_instance('darkterminal', 'test-database', 'sin')->toJSON();

// $apiToken = new APITokens($token);
// echo $apiToken->create('testToken')->toJSON();
// echo $apiToken->list()->toJSON();
// echo $apiToken->validate()->toJSON();
// echo $apiToken->revoke('testToken')->toJSON();
