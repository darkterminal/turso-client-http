<?php

it('return data with select(*)', function () {
    $data = $this->builder->table('users')->select('*')->get();
    expect($data)->not->toBeEmpty();
});

it('return data with select("name") only', function () {
    $data = $this->builder->table('users')->select('name')->get();
    $column = array_keys(reset($data))[0];
    expect($column)->toBe('name');
});

it('return data with select("name, email") columns', function () {
    $data = $this->builder->table('users')->select('name, email')->get();
    $columns = array_keys(reset($data));
    expect($columns)->toMatchArray(['name', 'email']);
});

it('return data with select("name, email") columns order by "name" ASC', function () {
    $data = $this->builder->table('users')->select('name, email')->orderBy('name', 'ASC')->get();
    $columns = array_keys(reset($data));
    expect($columns)->toMatchArray(['name', 'email']);
});

it('return data with select("DISTINCT name, age") columns', function () {
    $data = $this->builder->table('users')->select('DISTINCT name, age')->get();
    $columns = array_keys(reset($data));
    expect($columns)->toMatchArray(['name', 'age']);
});
