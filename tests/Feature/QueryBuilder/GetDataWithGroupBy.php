<?php

it('return data GROUP BY', function () {
    $data = $this->builder->table('tracks')
        ->select([
            "AlbumId",
            "COUNT(TrackId) AS TrackId"
        ])
        ->groupBy('AlbumId')
        ->get();
    
    expect($data)->not->toBeEmpty();
});
