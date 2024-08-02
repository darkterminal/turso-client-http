CREATE TABLE IF NOT EXISTS "artists" (
  "ArtistId" INTEGER NOT NULL, 
  "Name" NVARCHAR(120), 
  PRIMARY KEY ("ArtistId" AUTOINCREMENT)
);
--##
CREATE TABLE IF NOT EXISTS "albums" (
  "AlbumId" INTEGER NOT NULL, 
  "Title" NVARCHAR(160) NOT NULL, 
  "ArtistId" INTEGER NOT NULL, 
  FOREIGN KEY ("ArtistId") REFERENCES "artists" ("ArtistId") ON DELETE NO ACTION ON UPDATE NO ACTION, 
  PRIMARY KEY ("AlbumId" AUTOINCREMENT)
);
--##
CREATE TABLE IF NOT EXISTS "genres" (
  "GenreId" INTEGER NOT NULL, 
  "Name" NVARCHAR(120), 
  PRIMARY KEY ("GenreId" AUTOINCREMENT)
);
--##
CREATE TABLE IF NOT EXISTS "media_types" (
  "MediaTypeId" INTEGER NOT NULL, 
  "Name" NVARCHAR(120), 
  PRIMARY KEY ("MediaTypeId" AUTOINCREMENT)
);
--##
CREATE TABLE IF NOT EXISTS "tracks" (
  "TrackId" INTEGER NOT NULL, 
  "Name" NVARCHAR(200) NOT NULL, 
  "AlbumId" INTEGER, 
  "MediaTypeId" INTEGER NOT NULL, 
  "GenreId" INTEGER, 
  "Composer" NVARCHAR(220), 
  "Milliseconds" INTEGER NOT NULL, 
  "Bytes" INTEGER, 
  "UnitPrice" NUMERIC(10, 2) NOT NULL, 
  PRIMARY KEY ("TrackId" AUTOINCREMENT), 
  FOREIGN KEY ("MediaTypeId") REFERENCES "media_types" ("MediaTypeId") ON DELETE NO ACTION ON UPDATE NO ACTION, 
  FOREIGN KEY ("AlbumId") REFERENCES "albums" ("AlbumId") ON DELETE NO ACTION ON UPDATE NO ACTION, 
  FOREIGN KEY ("GenreId") REFERENCES "genres" ("GenreId") ON DELETE NO ACTION ON UPDATE NO ACTION
);
--##
CREATE TABLE IF NOT EXISTS "employees" (
  "EmployeeId" INTEGER NOT NULL, 
  "LastName" NVARCHAR(20) NOT NULL, 
  "FirstName" NVARCHAR(20) NOT NULL, 
  "Title" NVARCHAR(30), 
  "ReportsTo" INTEGER, 
  "BirthDate" DATETIME, 
  "HireDate" DATETIME, 
  "Address" NVARCHAR(70), 
  "City" NVARCHAR(40), 
  "State" NVARCHAR(40), 
  "Country" NVARCHAR(40), 
  "PostalCode" NVARCHAR(10), 
  "Phone" NVARCHAR(24), 
  "Fax" NVARCHAR(24), 
  "Email" NVARCHAR(60), 
  FOREIGN KEY ("ReportsTo") REFERENCES "employees" ("EmployeeId") ON DELETE NO ACTION ON UPDATE NO ACTION, 
  PRIMARY KEY ("EmployeeId" AUTOINCREMENT)
);
--##
CREATE TABLE IF NOT EXISTS "customers" (
  "CustomerId" INTEGER NOT NULL, 
  "FirstName" NVARCHAR(40) NOT NULL, 
  "LastName" NVARCHAR(20) NOT NULL, 
  "Company" NVARCHAR(80), 
  "Address" NVARCHAR(70), 
  "City" NVARCHAR(40), 
  "State" NVARCHAR(40), 
  "Country" NVARCHAR(40), 
  "PostalCode" NVARCHAR(10), 
  "Phone" NVARCHAR(24), 
  "Fax" NVARCHAR(24), 
  "Email" NVARCHAR(60) NOT NULL, 
  "SupportRepId" INTEGER, 
  FOREIGN KEY ("SupportRepId") REFERENCES "employees" ("EmployeeId") ON DELETE NO ACTION ON UPDATE NO ACTION, 
  PRIMARY KEY ("CustomerId" AUTOINCREMENT)
);
--##
CREATE TABLE IF NOT EXISTS "invoices" (
  "InvoiceId" INTEGER NOT NULL, 
  "CustomerId" INTEGER NOT NULL, 
  "InvoiceDate" DATETIME NOT NULL, 
  "BillingAddress" NVARCHAR(70), 
  "BillingCity" NVARCHAR(40), 
  "BillingState" NVARCHAR(40), 
  "BillingCountry" NVARCHAR(40), 
  "BillingPostalCode" NVARCHAR(10), 
  "Total" NUMERIC(10, 2) NOT NULL, 
  FOREIGN KEY ("CustomerId") REFERENCES "customers" ("CustomerId") ON DELETE NO ACTION ON UPDATE NO ACTION, 
  PRIMARY KEY ("InvoiceId" AUTOINCREMENT)
);
--##
CREATE TABLE IF NOT EXISTS "invoice_items" (
  "InvoiceLineId" INTEGER NOT NULL, 
  "InvoiceId" INTEGER NOT NULL, 
  "TrackId" INTEGER NOT NULL, 
  "UnitPrice" NUMERIC(10, 2) NOT NULL, 
  "Quantity" INTEGER NOT NULL, 
  FOREIGN KEY ("TrackId") REFERENCES "tracks" ("TrackId") ON DELETE NO ACTION ON UPDATE NO ACTION, 
  FOREIGN KEY ("InvoiceId") REFERENCES "invoices" ("InvoiceId") ON DELETE NO ACTION ON UPDATE NO ACTION, 
  PRIMARY KEY ("InvoiceLineId" AUTOINCREMENT)
);
--##
CREATE TABLE IF NOT EXISTS "playlists" (
  "PlaylistId" INTEGER NOT NULL, 
  "Name" NVARCHAR(120), 
  PRIMARY KEY ("PlaylistId" AUTOINCREMENT)
);
--##
CREATE TABLE IF NOT EXISTS "playlist_track" (
  "PlaylistId" INTEGER NOT NULL, 
  "TrackId" INTEGER NOT NULL, 
  CONSTRAINT "PK_PlaylistTrack" PRIMARY KEY ("PlaylistId", "TrackId"), 
  FOREIGN KEY ("TrackId") REFERENCES "tracks" ("TrackId") ON DELETE NO ACTION ON UPDATE NO ACTION, 
  FOREIGN KEY ("PlaylistId") REFERENCES "playlists" ("PlaylistId") ON DELETE NO ACTION ON UPDATE NO ACTION
);
--##
CREATE TABLE IF NOT EXISTS departments (
  department_id INTEGER PRIMARY KEY, 
  department_name TEXT NOT NULL
);
--##
CREATE TABLE IF NOT EXISTS ranks (RANK TEXT NOT NULL);
--##
CREATE TABLE IF NOT EXISTS suits (suit TEXT NOT NULL);
--##
CREATE TABLE IF NOT EXISTS students (
  student_id INTEGER PRIMARY KEY, student_name TEXT NOT NULL
);
--##
CREATE TABLE IF NOT EXISTS courses (
  course_id INTEGER PRIMARY KEY, course_name TEXT NOT NULL
);
--##
CREATE TABLE IF NOT EXISTS enrollments (
  enrollment_id INTEGER PRIMARY KEY, 
  student_id INTEGER, 
  course_id INTEGER, 
  FOREIGN KEY (student_id) REFERENCES students (student_id), 
  FOREIGN KEY (course_id) REFERENCES courses (course_id)
);
--##
CREATE TABLE IF NOT EXISTS books (
  book_id INTEGER PRIMARY KEY, title TEXT, 
  author TEXT, genre TEXT, price REAL
);
--##
CREATE TABLE IF NOT EXISTS bookcustomers (
  bookcustomer_id INTEGER PRIMARY KEY, 
  name TEXT, email TEXT
);
--##
CREATE TABLE IF NOT EXISTS orders (
  order_id INTEGER PRIMARY KEY, 
  bookcustomer_id INTEGER, 
  book_id INTEGER, 
  order_date DATE, 
  FOREIGN KEY (bookcustomer_id) REFERENCES bookcustomers (bookcustomer_id), 
  FOREIGN KEY (book_id) REFERENCES books (book_id)
);
--##
CREATE TABLE IF NOT EXISTS positions (
  id INTEGER PRIMARY KEY, title TEXT NOT NULL, 
  min_salary NUMERIC
);
--##
CREATE TABLE IF NOT EXISTS leads (
  id integer PRIMARY KEY, first_name text NOT NULL, 
  last_name text NOT NULL, phone text NOT NULL, 
  email text NOT NULL, source text NOT NULL
);
--##
CREATE TABLE IF NOT EXISTS contacts (
  id INTEGER PRIMARY KEY, name TEXT NOT NULL, 
  email TEXT UNIQUE NOT NULL, phone TEXT NOT NULL, 
  effective_date DATE NOT NULL
);
--##
CREATE TABLE IF NOT EXISTS book_lists (
  id INTEGER PRIMARY KEY, title TEXT NOT NULL, 
  isbn TEXT NOT NULL, release_date DATE
);
--##
CREATE TABLE IF NOT EXISTS accounts (
  account_no INTEGER NOT NULL, 
  balance DECIMAL NOT NULL DEFAULT 0, 
  PRIMARY KEY (account_no), 
  CHECK (balance >= 0)
);
--##
CREATE TABLE IF NOT EXISTS account_changes (
  change_no integer NOT NULL PRIMARY KEY autoincrement, 
  account_no INTEGER NOT NULL, flag TEXT NOT NULL, 
  amount DECIMAL NOT NULL, changed_at TEXT NOT NULL
);
--##
CREATE TABLE IF NOT EXISTS test_users (
  id INTEGER PRIMARY KEY AUTOINCREMENT, 
  name TEXT, email TEXT
);
--##
CREATE TABLE IF NOT EXISTS "users" (
  id INTEGER PRIMARY KEY, name TEXT NOT NULL, 
  email TEXT NOT NULL, age INTEGER NOT NULL, 
  address TEXT, country TEXT NOT NULL, 
  status TEXT NOT NULL
);
