<?php

it('return data Using UNION', function () {

    $q1 = $this->builder->table('employees')
        ->select([
            "FirstName",
            "LastName",
            "'Employee' AS Type"
        ])->getQuery();

    $q2 = $this->builder->table('customers')
        ->select([
            "FirstName",
            "LastName",
            "'Customer'"
        ])->getQuery();

    $data = $this->builder->union($q1)
        ->union($q2, true) // Using UNION ALL
        ->get();
    expect($data)->not->toBeEmpty();
});
