<?php

it('using prepare statement', function () {
    createUserTable();
    $result = $this->db->prepare("INSERT INTO users (:name, :email)")->execute([':name' => 'Test user 1', ':email' => 'testuser1@email.com']) > 0;
    $result = $this->db->prepare("INSERT INTO users (?, ?)")->execute(['Test user 1', 'testuser1@email.com']) > 0;
    expect($result)->toBe(true);
    dropTables("users");
});
