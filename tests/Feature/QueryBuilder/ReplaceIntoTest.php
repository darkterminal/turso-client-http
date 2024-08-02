<?php

it('use REPLACE INTO', function () {
    $data = $this->builder->replaceInto('positions', [
        'title' => 'Software Freestyle Engineer',
        'min_salary' => 690000
    ]);
    expect($data)->toBeGreaterThan(0);
});
