<?php
return [
  // DATABASE
  "DB_NAME" => "linkedin_clone_db",
  "DB_HOST" => "localhost",
  "DB_PORT" => 3306,
  "DB_USERNAME" => "root",
  "DB_PASSWORT" => "",
  "DB_CHARSET" => "utf8mb4",
  "DB_OPTIONS" => [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ],

  // SERVER
  "baseFolder" => "src",
  "Secret_Key" => "asnfbaknsfbaksfbkabfajhk"
];