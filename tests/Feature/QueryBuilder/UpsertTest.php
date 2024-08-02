<?php

it('using UPSERT', function () {
    $onInsert = [
        'name' => 'Jane Smith',
        'email' => 'jane@test.com',
        'phone' => '(408)-111-3333',
        'effective_date' => '2024-05-05'
    ];

    $onUpdate = [
        'name' => $this->builder->rawValue('excluded.name'),
        'phone' => $this->builder->rawValue('excluded.phone'),
        'effective_date' => $this->builder->rawValue('excluded.effective_date'),
    ];

    $data = $this->builder->where(
        $this->builder->rawValue('excluded.effective_date'),
        '>',
        $this->builder->rawValue('contacts.effective_date')
    )
        ->upsert('contacts', $onInsert, $onUpdate, 'email');
    expect($data)->toBeGreaterThanOrEqual(0);
});
