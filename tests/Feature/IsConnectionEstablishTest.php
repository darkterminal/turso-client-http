<?php
use Darkterminal\TursoHttp\LibSQL;

it('is connection established', function () {
    expect($this->db)->toBeInstanceOf(LibSQL::class);
});
