<?php

it('return data IS NULL', function () {
    $data = $this->builder->table('users')->like('address', '%elm%')->get();
    expect($data)->not->toBeEmpty();
});

it('return data IS NOT NULL', function () {
    $data = $this->builder->table('users')->notLike('address', '%elm%')->get();
    expect($data)->not->toBeEmpty();
});
