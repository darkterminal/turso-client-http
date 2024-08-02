<?php

it('return data Using WHERE NOT EXISTS', function () {
    $data = $this->builder->table('Artists a')
        ->notExists(function ($builder) {
            $builder->autoCommitBuilder(false);
            $query = $builder->table('Albums')
                ->select('1')
                ->where('ArtistId', '=', $builder->rawValue('a.ArtistId'))
                ->getQueryString();
            $builder->autoCommitBuilder(true);
            return $query;
        })
        ->orderBy('Name')
        ->get();
    expect($data)->not->toBeEmpty();
});
