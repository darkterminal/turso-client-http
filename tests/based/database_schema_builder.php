<?php

/**
 * THIS IS POOR TESTING, IF YOU HAVE KNOWLEDGE ABOUT UNIT TESTING
 * PULL REQUEST ARE VERY WELCOME!
 */

use Darkterminal\TursoHttp\LibSQL;
use Darkterminal\TursoHttp\core\Enums\DataType;
use Darkterminal\TursoHttp\core\Builder\LibSQLBlueprint;
use Darkterminal\TursoHttp\core\Builder\LibSQLSchemaBuilder;

require_once getcwd() . '/vendor/autoload.php';

try {
    $dbname = getenv('DB_URL');
    $authToken = getenv('DB_TOKEN');

    $db = new LibSQL("dbname=$dbname;authToken=$authToken");
    $schemaBuilder = new LibSQLSchemaBuilder($db);

    // Creating table
    $schemaBuilder->create('contacts', function(LibSQLBlueprint $table) {
        $table->increments('id');
        $table->string('name');
        $table->unique('email');
        $table->string('phone');
        $table->timestamps();
    })->execute();

    echo "Table created successfully.\n";

    // Add new column in the table
    $schemaBuilder->table('contacts', function(LibSQLBlueprint $table) {
        $table->addColumn(DataType::TEXT, 'address');
    })->execute();

    echo "Column added successfully.\n";

    // Drop the table
    // $schemaBuilder->drop('contacts')->execute();

    // echo "Table contacts successfully dropped!.\n";
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}
