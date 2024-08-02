<?php

it('update a single data in the table', function () {
    $data = $this->builder->table('employees')
        ->where('EmployeeId', '=', 3)
        ->update([
            'lastName' => 'Smith'
        ]);
    expect($data)->toBeGreaterThan(0);
});

it('update multiple columns in the table', function () {
    $data = $this->builder->table('employees')
        ->where('EmployeeId', '=', 4)
        ->update([
            'City' => 'Toronto',
            'State' => 'ON',
            'PostalCode' => 'M5P 2N7'
        ]);
    expect($data)->toBeGreaterThan(0);
});

it('a mistake update all rows with SQLite Function but not using rawValue function', function () {
    $data = $this->builder->table('employees')
        ->update([
            'Email' => 'LOWER(FirstName || "." || LastName || "@duck.com")'
        ]);
    expect($data)->toBeGreaterThan(0);
});

it('should be the correct update all rows with SQLite Function with rawValue function', function () {
    $data = $this->builder->table('employees')
        ->update([
            'Email' => $this->builder->rawValue("LOWER(FirstName || '.' || LastName || '@duck.com')")
        ]);
    expect($data)->toBeGreaterThan(0);
});
