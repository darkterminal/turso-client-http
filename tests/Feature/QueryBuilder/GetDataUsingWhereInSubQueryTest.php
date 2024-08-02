<?php

it('return data Using WHERE IN SubQuery', function () {
    $data = $this->builder->table('Customers c')
        ->select('CustomerId, FirstName, LastName, Company')
        ->whereSubQuery('CustomerId', 'IN', function ($builder) {
            $builder->autoCommitBuilder(false);
            $query = $builder->table('Invoices')
            ->select('CustomerId')
            ->getQueryString();
            $builder->autoCommitBuilder(true);
            return $query;
        })
        ->orderBy(['FirstName', 'LastName'])
        ->get();
    expect($data)->not->toBeEmpty();
});
