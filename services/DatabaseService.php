<?php

namespace Service;

use Core\Service;
use Exception;
use PDO;
use PDOException;


class DatabaseService
{
    private PDO $db;

    /**
     * @throws Exception
     */
    public function __construct(EnvironmentService $environment)
    {
        try {
            $this->db = new PDO('sqlite:database.db');
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function query(String $query, array $args = []): bool|array
    {
        $statement = $this->db->prepare($query);
        $statement->execute($args);
        return $statement->fetchAll();
    }

    public function getLinkingTable($class1, $class2): string|bool
    {
        $tables = $this->query("SHOW TABLES");

        foreach ($tables as $table)
        {
            if (str_contains($table["Tables_in_database"], $class1) && str_contains($table["Tables_in_database"], $class2)){
                return $table["Tables_in_database"];
            }

        }

        return false;
    }
}
