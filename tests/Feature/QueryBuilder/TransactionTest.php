<?php

it('using TRANSACTION', function () {
    $queries = [];

    $queries[] = $this->builder->table('accounts')
        ->where('account_no', '=', 100)
        ->update([
            'balance' => $this->builder->rawValue('balance - 1000')
        ], true);

    $queries[] = $this->builder->table('accounts')
        ->where('account_no', '=', 200)
        ->update([
            'balance' => $this->builder->rawValue('balance + 1000')
        ], true);

    $queries[] = $this->builder->table('account_changes')->insert([
        'account_no' => 100,
        'flag' => '-',
        'amount' => 1000,
        'changed_at' => $this->builder->rawValue("datetime('now')")
    ], true);

    $queries[] = $this->builder->table('account_changes')->insert([
        'account_no' => 200,
        'flag' => '+',
        'amount' => 1000,
        'changed_at' => $this->builder->rawValue("datetime('now')")
    ], true);

    $data = $this->builder->transactions($queries);
    expect($data)->toBeBool();
});
