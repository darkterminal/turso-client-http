<?php

it('return data Using CASE WHEN', function () {
    $cases = $this->builder->buildCaseExpression('Country', [
        "'USA'" => "'Domestic'"
    ], "'Foreign'");

    $data = $this->builder->table('Customers')
        ->select('CustomerId, FirstName, LastName')
        ->addCaseExpression($cases, 'CustomerGroup')
        ->orderBy(['FirstName', 'LastName'])
        ->get();
        
    expect($data)->not->toBeEmpty();
});
