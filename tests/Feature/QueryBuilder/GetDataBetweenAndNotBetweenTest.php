<?php

it('return data BETWEEN age 17 AND 25', function () {
    $data = $this->builder->table('users')->between('age', 17, 25)->get();
    expect($data)->not->toBeEmpty();
});

it('return data NOT BETWEEN age 17 AND 25', function () {
    $data = $this->builder->table('users')->notBetween('age', 17, 25)->get();
    expect($data)->not->toBeEmpty();
});
