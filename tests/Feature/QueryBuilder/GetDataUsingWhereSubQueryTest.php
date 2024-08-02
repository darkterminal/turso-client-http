<?php

it('return data Using WHERE SubQuery', function () {
    $data = $this->builder->table('tracks')
        ->select(sqlite_count('trackId'))
        ->whereSubQuery('albumId', '=', function () {
            return useQueryBuilder()->table('albums')
                ->select('albumId')
                ->where('title', '=', 'Let There Be Rock')->getQueryString();
        })
        ->get();
    expect($data)->not->toBeEmpty();
});
