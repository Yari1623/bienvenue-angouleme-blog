<?php

namespace App\Core;

use PDO;

class Model
{
    protected PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getPDO();
    }
}