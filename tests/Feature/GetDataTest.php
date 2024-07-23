<?php

use Darkterminal\TursoHttp\LibSQL;

it('select all from users', function () {
    createUserTable();
    $this->db->execute("INSERT INTO users (name, email) VALUES (:name, :email)", [
        ':name' => 'Test User 2',
        ':email' => 'testuser2@email.com'
    ]);

    $result = $this->db->query("SELECT * FROM users")->fetchArray(LibSQL::LIBSQL_ASSOC);
    expect($result)->not->toBeEmpty(true);
    dropTables("users");
});
