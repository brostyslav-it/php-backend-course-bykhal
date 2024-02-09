<?php

namespace Core;

use App\DB\Connection;
use App\DB\Constants;
use mysqli_result;

/**
 * Class Model
 *
 * Base model class providing common database-related functionality for derived models.
 *
 * @package Core
 */
class Model
{
    /**
     * @var Connection The database connection instance.
     */
    protected Connection $db;

    /**
     * @var array Default data structure with 'ok' and 'errors' keys.
     */
    protected const array DEFAULT_DATA = ['ok' => true, 'errors' => []];

    /**
     * Model constructor.
     *
     * Initializes the database connection.
     */
    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Executes a SQL query using the provided script path and optional parameter bindings.
     *
     * @param string $sqlScriptPath The path to the SQL script file.
     * @param array|null $bindings Optional parameter bindings for the SQL query.
     *
     * @return mysqli_result|false The result of the query or false on failure.
     */
    protected function query(string $sqlScriptPath, array $bindings = null): mysqli_result|false
    {
        return $this->db->query(
            file_get_contents(Constants::SQL_SCRIPTS_PATH . '/' . $sqlScriptPath),
            $bindings
        );
    }
}
