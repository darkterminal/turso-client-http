<?php

it('creates users table', function () {
    createUserTable();
    dropTables("users");
});
