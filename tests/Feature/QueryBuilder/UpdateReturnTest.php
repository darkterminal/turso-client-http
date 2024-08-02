<?php

it('using UPDATE RETURN', function () {
    $data = $this->builder->updateReturn('book_lists', ['isbn' => '0141439512'], sqlite_equal('id', 1));
    expect($data)->not->toBeEmpty();
});
