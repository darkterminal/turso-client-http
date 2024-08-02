<?php

it('return data using GLOB', function () {
    $data = $this->builder->table('tracks')
        ->select(['TrackId', 'Name'])
        ->glob('Name', 'Man*')
        ->get();
    expect($data)->toBeArray();
});
