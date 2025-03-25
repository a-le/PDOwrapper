<?php

declare(strict_types=1);

namespace DBAL;

use PDO;
use PDOStatement;

class PDOStatementWrapper extends PDOStatement
{
    /**
     * @var PDOWrapper The parent PDOWrapper instance.
     */
    protected PDOWrapper $pdoWrapper;

    /**
     * PDOStatementWrapper constructor.
     *
     * @param PDOWrapper $pdoWrapper The parent PDOWrapper instance.
     */
    protected function __construct(PDOWrapper $pdoWrapper)
    {
        $this->pdoWrapper = $pdoWrapper;
    }

    /**
     * Fetches the first column of all rows from the result set.
     *
     * @return array Returns an array of values from the first column, or an empty array if no rows are found.
     */
    public function fetchFirstColumn(): array
    {
        return $this->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Fetches the value of the first column of the next row from the result set.
     *
     * @return mixed Returns the value of the first column, or false if there are no more rows.
     */
    public function fetchOne(): mixed
    {
        return $this->fetch(PDO::FETCH_COLUMN);
    }

    /**
     * Fetches all rows from the result set as objects of a specified class.
     *
     * @param string $className The name of the class to create objects of.
     * @param array $constructorArgs An array of arguments to pass to the class constructor.
     *
     * @return array Returns an array of objects of the specified class.
     */
    public function fetchAllObjects(string $className, array $constructorArgs = []): array
    {
        return $this->fetchAll(PDO::FETCH_CLASS, $className, $constructorArgs);
    }

    /**
     * Fetches the next row from the result set as an object of a specified class.
     *
     * @param string $className The name of the class to create an object of.
     * @param array $constructorArgs An array of arguments to pass to the class constructor.
     *
     * @return object|false Returns an object of the specified class, or false if there are no more rows.
     */
    public function fetchOneObject(string $className, array $constructorArgs = []): object|false
    {
        $this->setFetchMode(PDO::FETCH_CLASS, $className, $constructorArgs);
        return $this->fetch();
    }
}
