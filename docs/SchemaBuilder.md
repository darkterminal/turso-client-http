# Schema Builder

![Schema Builder](https://i.imgur.com/QzKaDro.jpeg)

## Create Simple Table

```php
$dbname = getenv('DB_URL');
$authToken = getenv('DB_TOKEN');

$db = new LibSQL("dbname=$dbname&authToken=$authToken");
$schemaBuilder = new LibSQLSchemaBuilder($db);

$schemaBuilder->create('users', function(LibSQLBlueprint $table) {
    $table->increments('id');
    $table->string('username')->notNull();
    $table->string('email')->unique()->notNull();
    $table->string('password')->notNull();
    $table->timestamps();
})->execute();
```

## Create Table Relation

```php
$dbname = getenv('DB_URL');
$authToken = getenv('DB_TOKEN');

$db = new LibSQL("dbname=$dbname&authToken=$authToken");
$schemaBuilder = new LibSQLSchemaBuilder($db);

$schemaBuilder->create('users', function(LibSQLBlueprint $table) {
    $table->increments('id');
    $table->string('username');
    $table->string('email')->unique();
    $table->timestamps();
})->execute();

$schemaBuilder->create('contacts', function(LibSQLBlueprint $table) {
    $table->increments('id');
    $table->integer('user_id');
    $table->string('contact_name');
    $table->string('phone');
    $table->timestamps();

    // Adding foreign key constraint
    $table->foreignKey('user_id', 'id', 'users');
})->execute();
```
