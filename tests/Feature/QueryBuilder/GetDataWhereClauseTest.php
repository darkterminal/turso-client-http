<?php

it('return data WHERE age > 30', function () {
    $data = $this->builder->table('users')->where('age', '>', 30)->get();
    expect($data)->each(fn($user) => $user->age->toBeGreaterThan(30));
});

it('return data WHERE age > 30 AND country = INA', function () {
    $data = $this->builder->table('users')
        ->where('age', '>', 30)
        ->andWhere('country', '=', 'INA')
        ->get();
    expect($data)->not->toBeEmpty();
});

it('return data WHERE age > 30 OR country = INA', function () {
    $data = $this->builder->table('users')
        ->where('age', '>', 30)
        ->orWhere('country', '=', 'INA')
        ->get();
    expect($data)->not->toBeEmpty();
});

it('return data using WHERE group', function () {
    $data = $this->builder->table('users')
        ->select('*')
        ->whereGroup(function ($query) {
            $query->where('age', '>', 18)
                ->orWhere('age', '<', 65);
        }, 'AND')
        ->whereGroup(function ($query) {
            $query->where('status', '=', 'active')
                ->orWhere('status', '=', 'pending');
        })
        ->get();
    expect($data)->not->toBeEmpty();
});
