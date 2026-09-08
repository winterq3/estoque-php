<?php

return [
    'host'  => getenv('MYSQLHOST') ?: "localhost",
    'dbname'  => getenv('MYSQLDATABASE') ?: "estoque_db",
    'user'  => getenv('MYSQLUSER') ?: 'root',
    'password'  => getenv('MYSQLPASSWORD') ?: '',
    'charset'  => 'utf8mb4',
];