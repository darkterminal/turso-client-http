<?php

it('using INSERT RETURN', function () {
    $data = $this->builder->insertReturn('book_lists', [
        'title' => 'The Catcher in the Rye',
        'isbn' => '9780316769488',
        'release_date' => '1951-07-16'
    ], ['title', 'isbn']);
    expect($data)->not->toBeEmpty();
});
