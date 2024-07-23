<?php

it('inserts data with named args', function () {
    createUserTable();
    $result = $this->db->execute("INSERT INTO users (name, email) VALUES (:name, :email)", [
        ':name' => 'Test User 2',
        ':email' => 'testuser2@email.com'
    ]) > 0;
    expect($result)->toBe(true);
    dropTables("users");
});
