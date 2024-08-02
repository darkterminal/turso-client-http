<?php

it('return all data with table() and get()', function () {
    $data = $this->builder->table('users')->get();
    expect($data)->not->toBeEmpty();
});

it('return all data only with get("tableName")', function () {
    $data = $this->builder->get('users');
    expect($data)->not->toBeEmpty();
});
