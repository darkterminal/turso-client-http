<?php

uses()->group('thatNeedsSampleTableWithData');

it('deleting data', function () {
    $data = $this->builder->table('artists_backup')
        ->where('ArtistId', 1)
        ->delete();
    expect($data)->toBeGreaterThan(0);
});

it('deleting data WHERE LIKE', function () {
    $data = $this->builder->table('artists_backup')
        ->where('Name', 'LIKE', '%Santana%')
        ->delete();
    expect($data)->toBeGreaterThan(0);
});

it('deleting data with LIKE operator', function () {
    $data = $this->builder->table('artists_backup')
        ->like('Name', '%Santana%')
        ->delete();
    expect($data)->toBeGreaterThan(0);
});

it('deleting data WHERE NOT LIKE', function () {
    $data = $this->builder->table('artists_backup')
        ->where('Name', 'NOT LIKE', '%Santana%')
        ->delete();
    expect($data)->toBeGreaterThan(0);
});

it('deleting data with NOT LIKE operator', function () {
    $data = $this->builder->table('artists_backup')
        ->notLike('Name', '%Santana%')
        ->delete();
    expect($data)->toBeGreaterThan(0);
});

it('deleting all data', function () {
    $data = $this->builder->table('artists_backup')->delete();
    expect($data)->toBeGreaterThan(0);
});
