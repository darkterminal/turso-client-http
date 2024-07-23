<?php

it('inserts data with positional args', function () {
    createUserTable();
    $result = $this->db->execute("INSERT INTO users (name, email) VALUES (?, ?)", ['Test User 1', 'testuser1@email.com']) > 0;
    expect($result)->toBe(true);
    dropTables("users");
});
