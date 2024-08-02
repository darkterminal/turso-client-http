<?php

it('return data Using HAVING clause with INNER JOIN', function () {
    $data = $this->builder->table('tracks')
        ->select([
            "tracks.AlbumId",
            "title",
            "SUM(Milliseconds) AS length",
        ])
        ->join('albums', 'albums.AlbumId', '=', 'tracks.AlbumId')
        ->groupBy('tracks.AlbumId')
        ->having('length > 60000000')
        ->get();
    expect($data)->toBeArray();
});
