<?php

it('should be autocommit', function () {
    $result = $this->db->isAutoCommit();
    expect($result)->toBe(true);
});
