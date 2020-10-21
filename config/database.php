<?php
// PDO 
return[
    'driver' => 'mysql',
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'aleide_db',
    'dsn' =>'mysql:host=localhost;dbname=aleide_db;charset=utf8',
    'pdooptions' => [
        [ PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    ]
];
