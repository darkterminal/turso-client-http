<?php

it('return data Using EXCEPT', function () {
    $q1 = $this->builder->table('books')
        ->select('title, author, genre, price')
        ->getQuery();

    $q2 = $this->builder->table('books')
        ->select('title, author, genre, price')
        ->join('orders', 'books.book_id', '=', 'orders.book_id')
        ->getQuery();

    $data = $this->builder->except($q1)
        ->except($q2)
        ->get();
    expect($data)->not->toBeEmpty();
});
