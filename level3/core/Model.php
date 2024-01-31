<?php

namespace Core;

use App\DB\Connection;
use mysqli;

class Model
{
    protected Connection $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }
}
