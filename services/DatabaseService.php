<?php

namespace Service;

use Exception;
use PDO;
use PDOException;


class DatabaseService
{
    private PDO $db;

    /**
     * @throws Exception
     */
    public function __construct()
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
        $tables = $this->query("SELECT name FROM sqlite_master WHERE type='table';");

        foreach ($tables as $table)
        {
            if (str_contains($table["name"], $class1) && str_contains($table["name"], $class2)){
                return $table["name"];
            }

        }

        return false;
    }
}
