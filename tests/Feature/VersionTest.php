<?php

test('it can get the LibSQL version', function () {
    $version = $this->db->version();
    expect($version)->not->toBeEmpty();
});
