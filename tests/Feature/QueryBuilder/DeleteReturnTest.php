<?php

it('using DELETE RETURN', function () {
    // Re-entrant by design: insert a disposable row this test owns, then
    // deleteReturn() it. Deleting shared fixture rows would make the test
    // unreproducible on repeat runs.
    $marker = 'book_lists_return_test_marker';
    $this->builder->table('book_lists')->insert([
        'title' => $marker,
        'isbn' => 'return-test-isbn',
        'release_date' => '1951-07-16',
    ]);

    $inserted = $this->db->query("SELECT id FROM book_lists WHERE title = '{$marker}'")->fetchArray(\Darkterminal\TursoHttp\LibSQL::LIBSQL_ASSOC);
    expect($inserted)->not->toBeEmpty();

    $id = (int) $inserted[0]['id'];

    $data = $this->builder->deleteReturn('book_lists', sqlite_equal('id', $id));

    expect($data)->not->toBeEmpty();
});
