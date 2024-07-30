# Query Builder

![Query Builder](https://i.imgur.com/K7xuXxl.jpeg)

## Initiate

```php
$dbname = getenv('DB_URL');
$authToken = getenv('DB_TOKEN');

$db = new LibSQL("dbname=$dbname&authToken=$authToken");
$builder = new LibSQLQueryBuilder($db);
```

## Get All Data

```php
$builder->table('users')->get();
$builder->get('users');
```

equal to

```sql
SELECT * FROM users;
SELECT * FROM users;
```

## Get Selected Data by Column or All

```php
$builder->table('users')->select('*')->get();
$builder->table('users')->select('name')->get();
$builder->table('users')->select('name, email')->get();
$builder->table('users')->select(['name, email'])->get();
```

equal to

```sql
SELECT * FROM users
SELECT name FROM users
SELECT name, email FROM users
SELECT name, email FROM users
```

## Using ORDER BY clause

```php
$builder->table('users')
    ->select(['name', 'email'])
    ->orderBy('name', 'ASC')
    ->get();
```

equal to

```sql
SELECT name, email FROM users ORDER BY name ASC
```

## Using SELECT DISTINCT clause

```php
$builder->table('users')
    ->select('DISTINCT name, age')
    ->get();
```

equal to

```sql
SELECT DISTINCT name, age FROM users
```

## Using WHERE clause

```php
$builder->table('users')
    ->where('age', '>', 30)
    ->get();
```

equal to

```sql
SELECT * FROM users WHERE age > 30
```

## Using AND operator

```php
$builder->table('users')
    ->where('age', '>', 30)
    ->andWhere('country', '=', 'INA')
    ->get();
```

equal to

```sql
SELECT * FROM users WHERE age > 30 AND country = 'INA'
```

## Using OR operator

```php
$builder->table('users')
    ->where('age', '>', 30)
    ->orWhere('country', '=', 'INA')
    ->get();
```

equal to

```sql
SELECT * FROM users WHERE age > 30 OR country = 'INA'
```

## Using WHERE Group

```php
$builder->table($tableName)
    ->select('*')
    ->whereGroup(function($query) {
        $query->where('age', '>', 18)
              ->orWhere('age', '<', 65);
    }, 'AND')
    ->whereGroup(function($query) {
        $query->where('status', '=', 'active')
              ->orWhere('status', '=', 'pending');
    }) // Without operator to finish where clause
    ->get();
```

equal to

```sql
SELECT * FROM users
WHERE
    (age > 18 AND age < 65)
AND
    (status = 'active' AND status = 'pending')
```

## Using LIMIT

```php
$builder->table('users')->limit(10)->get();
```

equal to

```sql
SELECT * FROM users LIMIT 10
```

## Using BETWEEN and NOT BETWEEN

```php
$builder->table('users')->between('age', 17, 25)->get();
$builder->table('users')->notBetween('age', 17, 25)->get();
```

equal to

```sql
SELECT * FROM users WHERE age BETWEEN 17 AND 25
SELECT * FROM users WHERE age NOT BETWEEN 17 AND 25
```

## Using IN and NOT IN

```php
$builder->table('users')->in('id', [1,2,3,4,5])->get();
$builder->table('users')->notIn('id', [1,2,3,4,5])->get();
```

equal to

```sql
SELECT * FROM users WHERE id IN (1,2,3,4,5)
SELECT * FROM users WHERE id NOT IN (1,2,3,4,5)
```

## Using LIKE and NOT LIKE operator

```php
$builder->table('users')->like('address', '%elm%')->get();
$builder->table('users')->notLike('address', '%elm%')->get();
```

equal to

```sql
SELECT * FROM users WHERE address LIKE '%elm%'
SELECT * FROM users WHERE address NOT LIKE '%elm%'
```

## Using IS NULL and NOT NULL operator

```php
$builder->table('users')->isNull('address', '%elm%')->get();
$builder->table('users')->notNull('address', '%elm%')->get();
```

equal to

```sql
SELECT * FROM users WHERE address IS NULL
SELECT * FROM users WHERE address IS NOT NULL
```

## Using GLOB operator

```php
$builder->table('tracks')->select(['trackId', 'name'])->glob('name', 'Man*')->get();
```

equal to

```sql
SELECT trackId, name FROM tracks WHERE name GLOB 'Man*'
```

## Using JOIN

```php
$builder->table('albums')
    ->select(['Title', 'Name'])
    ->join('artists', 'artists.Artistid', '=', 'albums.ArtistId')
    ->get();
```

equal to

```sql
SELECT Title, Name FROM albums INNER JOIN artists ON artists.Artistid = albums.ArtistId
```

## Using LEFT JOIN

```php
$builder->table('artists')
    ->select(['artists.ArtistId AS ArtistId', 'AlbumId'])
    ->leftJoin('albums', 'albums.ArtistId', '=', 'artists.ArtistId')
    ->get();
```

equal to

```sql
SELECT
    artists.ArtistId AS ArtistId,
    AlbumId
FROM
    artists
LEFT JOIN
    albums
ON
    albums.ArtistId = artists.ArtistId
```

## Using RIGHT JOIN

```php
$builder->table('departments')
    ->select([
        'CONCAT(employees.FirstName, " ", employees.LastName) AS employee_name',
        'department_name'
    ])
    ->rightJoin('employees', 'employees.ReportsTo', '=', 'departments.department_id')
    ->get();
```

equal to

```sql
SELECT
    CONCAT(employees.FirstName, " ", employees.LastName) AS employee_name,
    department_name
FROM
    departments
RIGHT JOIN
    employees
ON
    employees.ReportsTo = departments.department_id
```

## Using CROSS JOIN

```php
$builder->table('ranks')
    ->select(['RANK', 'suit'])
    ->crossJoin('suits')
    ->orderBy('suit')
    ->get();
```

equal to

```sql
SELECT RANK, suit FROM ranks CROSS JOIN suits ORDER BY suit
```

## Using SELF JOIN

```php
$builder->table('employees e')
    ->select([
        "m.FirstName || ' ' || m.LastName AS 'Manager'",
        "e.FirstName || ' ' || e.LastName AS 'Direct report'"
    ])
    ->selfJoin('employees m', 'm.EmployeeId', '=', 'e.ReportsTo')
    ->orderBy('manager')
    ->get();
```

equal to

```sql
SELECT m.FirstName || ' ' || m.LastName AS 'Manager',
       e.FirstName || ' ' || e.LastName AS 'Direct report'
FROM employees e
INNER JOIN employees m ON m.EmployeeId = e.ReportsTo
ORDER BY Manager
```

## Using FULL OUTER JOIN

```php
$builder->table('students s')
    ->select([
        "s.student_name",
        "c.course_name"
    ])
    ->fullOuterJoin('enrollments e', 's.student_id', 'e.student_id')
    ->fullOuterJoin('courses c', 'e.course_id', 'c.course_id')
    ->get();
```

equal to

```sql
SELECT
  s.student_name,
  c.course_name
FROM
  students s
  FULL OUTER JOIN enrollments e ON s.student_id = e.student_id
  FULL OUTER JOIN courses c ON e.course_id = c.course_id
```

## Using FULL OUTER JOIN with USING

```php
$builder->table('students s')
    ->select([
        "student_name",
        "course_name"
    ])
    ->fullOuterJoinUsing('enrollments', 'student_id')
    ->fullOuterJoinUsing('courses', 'course_id')
    ->get();

$builder->table('students s')
    ->select([
        "student_name",
        "course_name"
    ])
    ->fullOuterJoinUsing('enrollments', 'student_id')
    ->fullOuterJoinUsing('courses', 'course_id')
    ->isNotNull('course_name')
    ->get();
```

equal to

```sql
SELECT
  student_name,
  course_name
FROM
  students
  FULL OUTER JOIN enrollments USING(student_id)
  FULL OUTER JOIN courses USING (course_id);

-- OR

SELECT
  student_name,
  course_name
FROM
  students
  FULL OUTER JOIN enrollments USING(student_id)
  FULL OUTER JOIN courses USING (course_id)
WHERE
    course_name IS NOT NULL;
```

## Using GROUP BY

```php
$builder->table('tracks')
    ->select([
        "AlbumId",
        "COUNT(TrackId) AS TrackId"
    ])
    ->groupBy('AlbumId')
    ->get();
```

equal to

```sql
SELECT
	albumid,
	COUNT(trackid)
FROM
	tracks
GROUP BY
	albumid;
```

## Using HAVING clause with INNER JOIN

```php
$builder->table('tracks')
    ->select([
        "tracks.AlbumId",
        "title",
        "SUM(Milliseconds) AS length",
    ])
    ->join('albums', 'albums.AlbumId', 'tracks.AlbumId')
    ->groupBy('tracks.AlbumId')
    ->having('length > 60000000')
    ->get();
```

equal to

```sql
SELECT
	tracks.AlbumId,
	title,
	SUM(Milliseconds) AS length
FROM
	tracks
INNER JOIN albums ON albums.AlbumId = tracks.AlbumId
GROUP BY
	tracks.AlbumId
HAVING
	length > 60000000;
```

## Using UNION

```php
$q1 = $builder->table('employees')
    ->select([
        "FirstName",
        "LastName",
        "'Employee' AS Type"
    ])->getQuery();

$q2 = $builder->table('customers')
    ->select([
        "FirstName",
        "LastName",
        "'Customer'"
    ])->getQuery();

$builder->union($q1)
    ->union($q2, true) // Using UNION ALL
    ->get();
```

equal to

```sql
SELECT
  FirstName,
  LastName,
  'Employee' AS Type
FROM
  employees
UNION
SELECT
  FirstName,
  LastName,
  'Customer'
FROM
  customers;
```

## Using EXCEPT

```php
$q1 = $builder->table('books')
    ->select('title, author, genre, price')
    ->getQuery();

$q2 = $builder->table('books')
    ->select('title, author, genre, price')
    ->join('orders', 'books.book_id', '=', 'orders.book_id')
    ->getQuery();

$builder->except($q1)
    ->except($q2)
    ->get();
```

equal to

```sql
SELECT title, author, genre, price
FROM books
EXCEPT
SELECT title, author, genre, price
FROM books
JOIN orders ON books.book_id = orders.book_id;
```

## Using INTERSECT

```php
$q1 = $builder->table('customers')
    ->select('CustomerId, FirstName, LastName')
    ->getQuery();

$q2 = $builder->table('invoices')
    ->select('CustomerId, FirstName, LastName')
    ->joinUsing('customers', 'CustomerId')
    ->getQuery();

$builder->intersect($q1)
    ->intersect($q2)
    ->get();
```

equal to

```sql
SELECT CustomerId, FirstName, LastName
FROM customers
INTERSECT
SELECT CustomerId, FirstName, LastName
FROM invoices
INNER JOIN customers USING (CustomerId)
ORDER BY CustomerId;
```

## Using WHERE Subquery

```php
$builder->table('tracks')
    ->select('trackId, name, albumId')
    ->whereSubQuery('albumId', '=', function () {
        return useQueryBuilder()->table('albums')
            ->select('albumId')
            ->where('title', '=', 'Let There Be Rock')->getQueryString();
    })
    ->get();
```

equal to

```sql
SELECT trackid,
       name,
       albumid
FROM tracks
WHERE albumid = (
   SELECT albumid
   FROM albums
   WHERE title = 'Let There Be Rock'
);
```

## Using WHERE EXISTS

```php
$builder->table('Customers c')
    ->select('CustomerId, FirstName, LastName, Company')
    ->exists(function ($builder) {
        return $builder->table('Invoices')
            ->select('1')
            ->where('CustomerId', '=', $builder->rawValue('c.CustomerId'))
            ->getQueryString();
    })
    ->orderBy(['FirstName', 'LastName'])
    ->get();
```

equal to

```sql
SELECT
    CustomerId,
    FirstName,
    LastName,
    Company
FROM
    Customers c
WHERE
    EXISTS (
        SELECT
            1
        FROM
            Invoices
        WHERE
            CustomerId = c.CustomerId
    )
ORDER BY
    FirstName,
    LastName;
```

## Using WHERE IN Subquery

```php
$builder->table('Customers c')
    ->select('CustomerId, FirstName, LastName, Company')
    ->whereSubQuery('CustomerId', 'IN', function ($builder) {
        return $builder->table('Invoices')
            ->select('CustomerId')
            ->getQueryString();
    })
    ->orderBy(['FirstName', 'LastName'])
    ->get();
```

equal to

```sql
SELECT
  CustomerId,
  FirstName,
  LastName,
  Company
FROM
  Customers c
WHERE
  CustomerId IN (
    SELECT
      CustomerId
    FROM
      Invoices
  )
ORDER BY
  FirstName,
  LastName;
```

## Using WHERE NOT EXISTS

```php
$builder->table('Artists a')
    ->notExists(function ($builder) {
        return $builder->table('Albums')
            ->select('1')
            ->where('ArtistId', '=', $builder->rawValue('a.ArtistId'))
            ->getQueryString();
    })
    ->orderBy('Name')
    ->get();
```

equal to

```sql
SELECT
  *
FROM
  Artists a
WHERE
  NOT EXISTS(
    SELECT
      1
    FROM
      Albums
    WHERE
      ArtistId = a.ArtistId
  )
ORDER BY
  Name;
```

## Using CASE

```php
$cases = $builder->buildCaseExpression('Country', [
    "'USA'" => "'Domestic'"
], "'Foreign'");

$builder->table('Customers')
    ->select('CustomerId, FirstName, LastName')
    ->addCaseExpression($cases, 'CustomerGroup')
    ->orderBy(['FirstName', 'LastName'])
    ->get();
```

equal to

```sql
SELECT customerid,
       firstname,
       lastname,
       CASE country
           WHEN 'USA'
               THEN 'Domestic'
           ELSE 'Foreign'
       END CustomerGroup
FROM
    customers
ORDER BY
    LastName,
    FirstName;
```

## Insert

```php
$builder->table('Artists')
    ->insert(['Name' => 'Danilla Riyadi']);
```

equal to

```sql
INSERT INTO Artists (name)
VALUES('Danilla Riyadi');
```

## Insert Batch

```php
$builder->table('Artists')
    ->insertBatch([
        ['Name' => 'Bud Powell'],
        ['Name' => 'Buddy Rich'],
        ['Name' => 'Candido'],
        ['Name' => 'Charlie Byrd']
    ]);
```

equal to

```sql
INSERT INTO Artists (name)
VALUES
    ('Bud Powell'),
    ('Buddy Rich'),
	('Candido'),
	('Charlie Byrd');
```

## Update

**Update Single Column**

```php
$builder->table('employees')
    ->where('EmployeeId', '=', 3)
    ->update([
        'lastName' => 'Smith'
    ]);
```

equal to

```sql
UPDATE employees
SET lastName = 'Smith'
WHERE EmployeeId = 3;
```

**Update Multiple Columns**

```php
$builder->table('employees')
    ->where('EmployeeId', '=', 4)
    ->update([
        'City' => 'Toronto',
        'State' => 'ON',
        'PostalCode' => 'M5P 2N7'
    ]);
```

equal to

```sql
UPDATE employees
SET city = 'Toronto',
    state = 'ON',
    postalcode = 'M5P 2N7'
WHERE
    employeeid = 4;
```

**Update All Rows**

Note: make sure you know what you doing, because this operation will update all rows in your table. If you want to use some SQLite Functions/Formula make sure you use `$builder->rawValue()` function to mark them as an raw value.

```php
$builder->table('employees')
    ->update([
        'Email' => 'LOWER(FirstName || "." || LastName || "@duck.com")'
    ]);
```

Will become

```sql
UPDATE employees
SET Email = 'LOWER(FirstName || "." || LastName || "@duck.com")';
```

Different with this

```php
$builder->table('employees')
    ->update([
        'Email' => $builder->rawValue('LOWER(FirstName || "." || LastName || "@duck.com")')
    ]);
```

Will become

```sql
UPDATE employees
SET Email = LOWER(FirstName || "." || LastName || "@duck.com");
```

## Using DELETE

**Simple Delete**

```php
$builder->table('artists_backup')
    ->where('ArtistId', 1)
    ->delete();

// OR

$builder->table('artists_backup')
    ->where('ArtistId', '=' ,1)
    ->delete();
```

equal to

```sql
DELETE FROM artists_backup
WHERE ArtistId = 1;
```

**Delete With Other Operators**

```php
$builder->table('artists_backup')
    ->where('Name', 'LIKE', '%Santana%')
    ->delete();

$builder->table('artists_backup')
    ->like('Name', '%Santana%')
    ->delete();

// OR

$builder->table('artists_backup')
    ->where('Name', 'NOT LIKE', '%Santana%')
    ->delete();

$builder->table('artists_backup')
    ->notLike('Name', '%Santana%')
    ->delete();
```

equal to

```sql
DELETE FROM artists_backup
WHERE Name LIKE '%Santana%';

DELETE FROM artists_backup
WHERE Name NOT LIKE '%Santana%';
```

**Delete All Rows from Table**

```php
$builder->table('artists_backup');
```

equal to

```sql
DELETE FROM artists_backup;
```

## Using REPLACE INTO

```php
$builder->replaceInto('positions', [
    'title' => 'Software Freestyle Engineer',
    'min_salary' => 690000
]);
```

equal to

```sql
REPLACE INTO positions (title, min_salary)
VALUES('Software Freestyle Engineer', 690000);
```

## Using UPSERT

```php
$onInsert = [
    'name' => 'Jane Smith',
    'email' => 'jane@test.com',
    'phone' => '(408)-111-3333',
    'effective_date' => '2024-05-05'
];

$onUpdate = [
    'name' => $builder->rawValue('excluded.name'),
    'phone' => $builder->rawValue('excluded.phone'),
    'effective_date' => $builder->rawValue('excluded.effective_date'),
];

$builder->where(
        $builder->rawValue('excluded.effective_date'),
        '>',
        $builder->rawValue('contacts.effective_date')
    )
    ->upsert('contacts', $onInsert, $onUpdate, 'email');
```

equal to

```sql
INSERT INTO contacts (name, email, phone, effective_date)
VALUES (
    'Jane Smith',
    'jane@test.com',
    '(408)-111-3333',
    '2024-05-05'
)
ON CONFLICT (email) DO UPDATE SET
    name = excluded.name,
    phone = excluded.phone,
    effective_date = excluded.effective_date
WHERE
    excluded.effective_date > contacts.effective_date;
```

## Using INSERT RETURN

```php
$builder->insertReturn('book_lists', [
    'title' => 'The Catcher in the Rye',
    'isbn' => '9780316769488',
    'release_date' => '1951-07-16'
], ['title', 'isbn']);
```

equal to

```sql
INSERT INTO book_lists(title, isbn, release_date)
VALUES('The Catcher in the Rye', '9780316769488', '1951-07-16')
RETURNING title, isbn;
```

## Using TRANSACTION

The `true` value in each operation builder will return Generated SQL.

```php
$queries = [];

$queries[] = $builder->table('accounts')
    ->where('account_no', '=', 100)
    ->update([
        'balance' => $builder->rawValue('balance - 1000')
    ], true);

$queries[] = $builder->table('accounts')
    ->where('account_no', '=', 200)
    ->update([
        'balance' => $builder->rawValue('balance + 1000')
    ], true);

$queries[] = $builder->table('account_changes')->insert([
    'account_no' => 100,
    'flag' => '-',
    'amount' => 1000,
    'changed_at' => $builder->rawValue("datetime('now')")
], true);

$queries[] = $builder->table('account_changes')->insert([
    'account_no' => 200,
    'flag' => '+',
    'amount' => 1000,
    'changed_at' => $builder->rawValue("datetime('now')")
], true);

$builder->transactions($queries);
```

equal to

```sql
BEGIN TRANSACTION;
UPDATE accounts SET balance = balance - 1000 WHERE account_no = 100;
UPDATE accounts SET balance = balance + 1000 WHERE account_no = 200;
INSERT INTO account_changes (account_no, flag, amount, changed_at) VALUES (100, '-', 1000, datetime('now'));
INSERT INTO account_changes (account_no, flag, amount, changed_at) VALUES (200, '+', 1000, datetime('now'))
COMMIT;
```

## Create View

Note: Make sure you use aliases on the selected columns to make reading and creating views easier.

**Simple View**

```php
$queryView = $builder->table('tracks')
    ->select([
        'trackid',
        'tracks.name',
        'albums.Title AS album',
        'media_types.Name AS media',
        'genres.Name AS genres'
    ])
    ->join('albums', 'Albums.AlbumId', '=', $builder->rawValue('tracks.AlbumId'))
    ->join('media_types', 'media_types.MediaTypeId', '=', $builder->rawValue('tracks.MediaTypeId'))
    ->join('genres', 'genres.GenreId', '=', $builder->rawValue('tracks.GenreId'))
    ->getQueryString();

$builder->createView('v_tracks')
    ->viewQuery($queryView)
    ->generateView();
```

equal to

```sql
CREATE VIEW v_tracks AS
SELECT
  trackid,
  tracks.name,
  albums.Title AS album,
  media_types.Name AS media,
  genres.Name AS genres
FROM
  tracks
  INNER JOIN albums ON Albums.AlbumId = tracks.AlbumId
  INNER JOIN media_types ON media_types.MediaTypeId = tracks.MediaTypeId
  INNER JOIN genres ON genres.GenreId = tracks.GenreId
```

**View with Custom Column**

```php
$queryView = $builder->table('tracks')
    ->select([
        $builder->rawValue('albums.title AS AlbumTitle'),
        $builder->rawValue('SUM(milliseconds) / 60000 AS Minutes')
    ])
    ->joinUsing('albums', 'AlbumId')
    ->groupBy('AlbumTitle')
    ->getQueryString();

$builder->createView('v_albums')
        ->viewColumns(['AlbumTitle', 'Minutes'])
    ->viewQuery($queryView)
    ->generateView();
```

equal to

```sql
CREATE VIEW v_albums (AlbumTitle, Minutes) AS
SELECT
  albums.title AS AlbumTitle,
  SUM(milliseconds) / 60000 AS Minutes
FROM
  tracks
  INNER JOIN albums USING (AlbumId)
GROUP BY
  AlbumTitle
```

## Using Trigger

```php
$builder->setTrigerName('validate_email_before_insert')
    ->setTriggerTime('BEFORE')
    ->setTriggerEvent('INSERT')
    ->setTriggerTable('leads')
    ->setTriggerCondition('NEW.email NOT LIKE "%_@__%.__%"')
    ->addRaiseAbort('Invalid email address')
    ->createTrigger();
```

equal to

```sql
CREATE TRIGGER validate_email_before_insert BEFORE INSERT ON leads WHEN NEW.email NOT LIKE "%_@__%.__%" BEGIN
SELECT
  RAISE (ABORT, 'Invalid email address');
END;
```

## Miscs

### Get All Indexes in Current Database

```php
$builder->getAllIndexes();
```

### Get All Indexes in Selected Table

```php
$table = 'users';
$builder->getAllIndexes($table);
```

### Create Index

```php
// Single Column Index
$builder->createIndex('idx_contacts_name', 'contacts', 'fullName');

// Mutiple Column Indexes
$builder->createIndex('idx_contacts_name', 'contacts', ['firstName', 'lastName']);
```

### Create Unique Index

```php
// Single Column Index
$builder->createIndex('idx_contacts_email', 'contacts', 'email');

// Mutiple Column Indexes
$builder->createIndex('idx_contacts_address', 'contacts', ['streetName', 'postalCode']);
```

### Drop Index

```php
// Single Column Index
$builder->dropIndex('idx_contacts_email');
```

### Drop Indexes

```php
// Drop All Index in selected table
$builder->dropAllIndexes('contacts');

// Drop All Index in database
$builder->dropAllIndexes();
```

## Truncate Table

Will delete all data in table and reset the `sqlite_sequence` for tahble.

```php
$builder->truncateTable('users');
```

## Drop Table

```php
$builder->dropTable('users');
```

## Drop All Tables

```php
$builder->dropAllTables();
```
