<?php

namespace Service;

use Model\Card;
use ReflectionClass;
use ReflectionException;

class RepositoryService
{
    private DatabaseService $database;

    /**
     * @param DatabaseService $database
     */
    public function __construct(DatabaseService $database)
    {
        $this->database = $database;
    }

    /**
     * @throws ReflectionException
     */
    public function findAll($entityClass, array $orderByArgs = []): bool|array
    {
        $reflection = new ReflectionClass($entityClass);
        $table = $reflection->getProperties()[0]->getDefaultValue();

        $orderBy = "";
        if (isset($orderByArgs[0]) && isset($orderByArgs[1])){
            if ($orderByArgs[1]){
                $orderBy = "ORDER BY $orderByArgs[0] DESC";
            } else {
                $orderBy = "ORDER BY $orderByArgs[0]";
            }
        }


        $data = $this->database->query("SELECT * FROM $table $orderBy");

        $results = [];
        foreach ($data as $entry)
        {
            $id = $entry["id"];
            array_shift($entry);
            $entry = $this->handleRelations($reflection, $entry);
            $obj = $reflection->newInstanceArgs($entry);
            $obj->setId($id);
            $obj = $this->handleManyRelations($reflection, $id, $obj);
            $results[] = $obj;
        }

        return $results;
    }

    /**
     * @throws ReflectionException
     */
    public function findById($entityClass, int $id)
    {
        $reflection = new ReflectionClass($entityClass);
        $table = $reflection->getProperties()[0]->getDefaultValue();
        $entry = $this->database->query("SELECT * FROM $table WHERE id = ?", [$id]);

        if (!empty($entry)) {
            $entry = $entry[0];
            $id = $entry[0];
            array_shift($entry);
            $entry = $this->handleRelations($reflection, $entry);
            $obj = $reflection->newInstanceArgs($entry);
            $obj->setId($id);
            return $this->handleManyRelations($reflection, $id, $obj);
        }

        return null;
    }

    /**
     * @throws ReflectionException
     */
    public function findAllCustom($entityClass, array $whereParams = [], array $orderByArgs = []): array
    {
        $reflection = new ReflectionClass($entityClass);
        $table = $reflection->getProperties()[0]->getDefaultValue();

        $orderBy = "";
        if (isset($orderByArgs[0]) && isset($orderByArgs[1])){
            if ($orderByArgs[1]){
                $orderBy = "ORDER BY $orderByArgs[0] DESC";
            } else {
                $orderBy = "ORDER BY $orderByArgs[0]";
            }
        }

        $args = [];
        $whereString = "";
        if (!empty($whereParams)){
            foreach ($whereParams as $key => $value){
                if ($key === array_key_first($whereParams)){
                    $whereString .= "WHERE $key = :$key";
                } else {
                    $whereString .= " AND $key = :$key";
                }

                if (is_object($value)){
                    $args[$key] = $value->getId();
                } else {
                    $args[$key] = $value;
                }
            }
        }

        $data = $this->database->query("SELECT * FROM $table $whereString $orderBy", $args);

        $results = [];
        foreach ($data as $entry)
        {
            $id = $entry["id"];
            array_shift($entry);

            $entry = $this->handleRelations($reflection, $entry);

            $obj = $reflection->newInstanceArgs($entry);
            $obj->setId($id);
            $obj = $this->handleManyRelations($reflection, $id, $obj);
            $results[] = $obj;
        }

        return $results;
    }

    /**
     * @throws ReflectionException
     */
    private function handleRelations(ReflectionClass $reflection, $entry): array
    {
        $entry = array_filter($entry, 'is_string', ARRAY_FILTER_USE_KEY);
        foreach ($entry as $key => $item)
        {
            if ($reflection->hasProperty($key)) {
                $prop = $reflection->getProperty($key);
                if (class_exists($prop->getType()->getName())) {
                    $relationItem = $this->findById($prop->getType()->getName(), intval($item));
                    $id = $relationItem->getId();
                    $relationItem = get_object_vars($relationItem);
                    array_shift($relationItem);
                    array_shift($relationItem);
                    $relationObject = new ReflectionClass($prop->getType()->getName());
                    $relationObject = $relationObject->newInstanceArgs($relationItem);
                    $relationObject->setId($id);
                    $entry[$key] = $relationObject;
                }
            }
        }

        return $entry;
    }

    /**
     * @throws ReflectionException
     */
    private function handleManyRelations(ReflectionClass $reflection, $id, $obj): object
    {
        foreach ($reflection->getProperties() as $property)
        {
            if ($property->getType()->getName() === "array"){
                $linkingTable = $this->database->getLinkingTable($reflection->getProperty("tableName")->getDefaultValue(), $property->getName());
                $sql = "SELECT * FROM $linkingTable WHERE ".$reflection->getProperty("tableName")->getDefaultValue()."_id = ?";
                $relationItems = $this->database->query($sql,[$id]);
                $entityName = substr(ucfirst($property->getName()),0,-1);
                $className = "Model\\$entityName";
                $key = $property->getName()."_id";

                if(!empty($relationItems)){
                    foreach ($relationItems as $item)
                    {
                        $reflection->getMethod("add" . $entityName)->invoke($obj, $this->findById($className, $item[$key]));
                    }

                }

            }
        }

        return $obj;
    }

    /**
     * @throws ReflectionException
     */
    public function cardExistsByName(string $entityClass, Card $card): ?Card
    {
        $reflection = new ReflectionClass($entityClass);
        $table = $reflection->getProperties()[0]->getDefaultValue();

        $result = $this->database->query("SELECT id FROM $table WHERE name = ?", [$card->getName()]);

        if (count($result) > 0) {
            $card->setId($result[0]["id"]);
            return $card;
        }

        return null;
    }


}