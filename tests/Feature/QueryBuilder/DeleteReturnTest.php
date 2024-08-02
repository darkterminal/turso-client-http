<?php

it('using DELETE RETURN', function () {
    $data = $this->builder->deleteReturn('book_lists', sqlite_equal('id', 2));
    expect($data)->not->toBeEmpty();
});
