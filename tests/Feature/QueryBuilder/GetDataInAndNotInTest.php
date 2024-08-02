<?php

it('return data WHERE IN', function () {
    $data = $this->builder->table('users')->in('id', [70, 65, 74, 20, 71])->get();
    expect($data)->not->toBeEmpty();
});

it('return data WHERE NOT IN', function () {
    $data = $this->builder->table('users')->notIn('id', [70, 65, 74, 20, 71])->get();
    expect($data)->not->toBeEmpty();
});
