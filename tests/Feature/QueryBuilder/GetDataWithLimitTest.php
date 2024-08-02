<?php

it('return data LIMIT 10', function () {
    $data = $this->builder->table('users')->limit(10)->get();
    expect($data)->toHaveCount(10);
});
