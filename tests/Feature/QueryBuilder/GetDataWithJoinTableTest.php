<?php

it('return data with JOIN', function () {
    $data = $this->builder->table('albums')
        ->select(sqlite_count('Title'))
        ->join('artists', 'artists.Artistid', '=', 'albums.ArtistId')
        ->get();
    $data = reset($data);
    expect($data['Title_count'])->toBeGreaterThan(0);
});

it('return data with LEFT JOIN', function () {
    $data = $this->builder->table('artists')
        ->select(sqlite_count('AlbumId'))
        ->leftJoin('albums', 'albums.ArtistId', '=', 'artists.ArtistId')
        ->get();
    $data = reset($data);
    expect($data['AlbumId_count'])->toBeGreaterThan(0);
});

it('return data with RIGHT JOIN', function () {
    $data = $this->builder->table('departments')
        ->select(sqlite_count('department_name'))
        ->rightJoin('employees', 'employees.ReportsTo', '=', 'departments.department_id')
        ->get();
    $data = reset($data);
    expect($data['department_name_count'])->toBeGreaterThan(0);
});

it('return data with CROSS JOIN', function () {
    $data = $this->builder->table('ranks')
        ->select(sqlite_count('suit'))
        ->crossJoin('suits')
        ->orderBy('suit')
        ->get();
    $data = reset($data);
    expect($data['suit_count'])->toBeGreaterThan(0);
});

it('return data with SELF JOIN', function () {
    $data = $this->builder->table('employees e')
        ->select([
            "m.FirstName || ' ' || m.LastName AS 'Manager'",
            "e.FirstName || ' ' || e.LastName AS 'Direct report'"
        ])
        ->selfJoin('employees m', 'm.EmployeeId', '=', 'e.ReportsTo')
        ->orderBy('manager')
        ->get();
    expect($data)->not->toBeEmpty();
});

it('return data with FULL OUTER JOIN', function () {
    $data = $this->builder->table('students s')
        ->select([
            "s.student_name",
            "c.course_name"
        ])
        ->fullOuterJoin('enrollments e', 's.student_id', 'e.student_id')
        ->fullOuterJoin('courses c', 'e.course_id', 'c.course_id')
        ->get();
    expect($data)->not->toBeEmpty();
});

it('return data with FULL OUTER JOIN with USING', function () {
    $data = $this->builder->table('students s')
        ->select([
            "s.student_name",
            "c.course_name"
        ])
        ->fullOuterJoin('enrollments e', 's.student_id', 'e.student_id')
        ->fullOuterJoin('courses c', 'e.course_id', 'c.course_id')
        ->get();
    expect($data)->not->toBeEmpty();
});

it('return data with FULL OUTER JOIN with USING IS NOT NULL', function () {
    $data = $this->builder->table('students s')
        ->select([
            "student_name",
            "course_name"
        ])
        ->fullOuterJoinUsing('enrollments', 'student_id')
        ->fullOuterJoinUsing('courses', 'course_id')
        ->isNotNull('course_name')
        ->get();
    expect($data)->not->toBeEmpty();
});
