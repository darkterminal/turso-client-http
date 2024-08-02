<?php

it('return data Using INTERSECT', function () {
    $q1 = $this->builder->table('customers')
        ->select('CustomerId, FirstName, LastName')
        ->getQuery();

    $q2 = $this->builder->table('invoices')
        ->select('CustomerId, FirstName, LastName')
        ->joinUsing('customers', 'CustomerId')
        ->getQuery();

    $data = $this->builder->intersect($q1)
        ->intersect($q2)
        ->get();
    expect($data)->not->toBeEmpty();
});
