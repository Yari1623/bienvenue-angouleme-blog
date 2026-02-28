<?php

use App\Core\Database;

Database::connect([
    'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'dbname' => $_ENV['DB_NAME'] ?? '',
    'username' => $_ENV['DB_USER'] ?? '',
    'password' => $_ENV['DB_PASS'] ?? '',
]);