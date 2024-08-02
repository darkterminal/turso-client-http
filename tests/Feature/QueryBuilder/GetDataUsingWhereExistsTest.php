<?php

it('return data Using WHERE SubQuery', function () {
    $data = $this->builder->table('Customers c')
        ->select('CustomerId, FirstName, LastName, Company')
        ->exists(function ($builder) {
            $builder->autoCommitBuilder(false);
            $query = $builder->table('Invoices')
                ->select('1')
                ->where('CustomerId', '=', $builder->rawValue('c.CustomerId'))
                ->getQueryString();
            $builder->autoCommitBuilder(true);
            return $query;
        })
        ->orderBy(['FirstName', 'LastName'])
        ->get();
    expect($data)->not->toBeEmpty();
});
