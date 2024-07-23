<?php

it('get the number of changes', function () {
    createUserTable();

    $this->db->execute("INSERT INTO users (name, email) VALUES (:name, :email)", [
        ':name' => 'Test User 2',
        ':email' => 'testuser2@email.com'
    ]);

    $this->db->execute("UPDATE users SET name = ? WHERE id = ?", ['Test User 1 Updated', 1]);
    
    $changes = $this->db->changes();
    expect($changes)->toBeGreaterThan(0);
    dropTables("users");
});
