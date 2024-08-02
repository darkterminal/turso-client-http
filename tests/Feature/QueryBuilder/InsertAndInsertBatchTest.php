<?php

it('return insert statement in string', function () {
    $data = $this->builder->table('Artists')->insert(['Name' => 'Danilla Riyadi'], true);
    expect($data)->toBeString();
});

it('inserting data into table', function () {
    $data = $this->builder->table('Artists')->insert(['Name' => 'Danilla Riyadi']);
    expect($data)->toBeNumeric();
});

it('return insertBatch statement in string', function () {
    $data = $this->builder->table('Artists')
        ->insertBatch([
            ['Name' => 'Bud Powell'],
            ['Name' => 'Buddy Rich'],
            ['Name' => 'Candido'],
            ['Name' => 'Charlie Byrd']
        ], true);
    expect($data)->toBeString();
});

it('inserting batch data into table', function () {
    $data = $this->builder->table('Artists')
        ->insertBatch([
            ['Name' => 'Bud Powell'],
            ['Name' => 'Buddy Rich'],
            ['Name' => 'Candido'],
            ['Name' => 'Charlie Byrd']
        ]);
    expect($data)->toBeNumeric();
});
