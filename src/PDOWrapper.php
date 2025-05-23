<?php

declare(strict_types=1);

namespace aLe;

use PDO;
use PDOStatement;
use function array_replace;

class PDOWrapper extends PDO
{
    /**
     * PDOWrapper constructor.
     *
     * @param string      $dsn      The Data Source Name, or DSN, containing the information required to connect to the database.
     * @param string|null $username The username for the DSN string. Defaults to null.
     * @param string|null $password The password for the DSN string. Defaults to null.
     * @param array       $options  An array of options for the PDO connection. Defaults to an empty array.
     *
     * @throws PDOException If the connection fails.
     */
    public function __construct(string $dsn, string|null $username = null, string|null $password = null, array $options = [])
    {
        $default_options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Error reporting, let PDO throw an exception
        ];
        $options = array_replace($default_options, $options);
        parent::__construct($dsn, $username, $password, $options);
    }

    /**
     * Prepares and executes an SQL statement.
     *
     * @param string $sql  The SQL query to execute.
     * @param array  $args An array of arguments to bind to the query. Defaults to an empty array.
     *
     * @return PDOStatement|false Returns a PDOStatement object on success, or false on failure.
     */
    public function run(string $sql, array $args = []): PDOStatement|false
    {
        $stmt = $this->prepare($sql);
        if ($stmt === false) {
            return false;
        }
        $stmt->execute($args);
        return $stmt;
    }
}
