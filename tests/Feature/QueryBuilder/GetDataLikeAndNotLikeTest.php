<?php

it('return data LIKE', function () {
    $data = $this->builder->table('users')->like('address', '%elm%')->get();
    expect($data)->not->toBeEmpty();
});

it('return data NOT LIKE', function () {
    $data = $this->builder->table('users')->notLike('address', '%elm%')->get();
    expect($data)->not->toBeEmpty();
});
