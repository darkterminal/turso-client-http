<?php

/**
 * THIS IS POOR TESTING, IF YOU HAVE KNOWLEDGE ABOUT UNIT TESTING
 * PULL REQUEST ARE VERY WELCOME!
 */

use Darkterminal\TursoHttp\LibSQL;
use Darkterminal\TursoHttp\sadness\LibSQLBlueprint;
use Darkterminal\TursoHttp\sadness\LibSQLSchemaBuilder;

require_once getcwd() . '/vendor/autoload.php';

try {
    $dbname = getenv('DB_URL');
    $authToken = getenv('DB_TOKEN');

    $db = new LibSQL("dbname=$dbname&authToken=$authToken");
    $schemaBuilder = new LibSQLSchemaBuilder($db);

    $schemaBuilder->create('contacts', function(LibSQLBlueprint $table) {
        $table->increments('id');
        $table->string('name');
        $table->unique('email');
        $table->string('phone');
        $table->timestamp('created_at');
    })->execute();

    echo "Table created successfully.\n";

    $schemaBuilder->table('contacts', function(LibSQLBlueprint $table) {
        $table->addColumn('DATETIME', 'updated_at', null, 'DEFAULT CURRENT_TIMESTAMP');
    })->execute();

    echo "Column added successfully.\n";
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}
