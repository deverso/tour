<?php
$db = new SQLite3('tour-radar.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);

// Create a table.
$db->query(
'CREATE TABLE operators (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    title VARCHAR,                                  
	destinations VARCHAR,
	age VARCHAR,
	travel_style VARCHAR,
	operated_lang VARCHAR,
	operator VARCHAR,
	operator_badge VARCHAR,
    cover_image VARCHAR
  );'
);

// Close the connection
$db->close();