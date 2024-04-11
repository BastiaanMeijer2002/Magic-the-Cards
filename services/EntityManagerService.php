<?php

namespace Service;

use Exception;
use ReflectionClass;
use ReflectionException;

class EntityManagerService
{
    private DatabaseService $database;
    private RepositoryService $repository;

    public function __construct(DatabaseService $databaseService, RepositoryService $repository)
    {
        $this->database = $databaseService;
        $this->repository = $repository;
    }

    /**
     * @throws ReflectionException
     */
    public function addEntity($entity): void
    {
        $reflection = new ReflectionClass(get_class($entity));
        $props = $reflection->getProperties();
        $table = $props[0]->getDefaultValue();

        array_shift($props);
        array_shift($props);

        $getMethods = array_filter($reflection->getMethods(), function($method) {
            return str_starts_with($method->getName(), 'get');
        });

        array_shift($getMethods);

        $keyString = "";
        foreach ($props as $property)
        {
            if ($property->getType()->getName() === "array"){
                continue;
            }

            if (!empty($keyString)){
                $keyString .= ", ";
            }

            $keyString .= $property->getName();
        }

        $valueString = "";
        $args = [];
        $relationItems = [];
        foreach ($getMethods as $method)
        {
            $arg = $method->invoke($entity);
            if (is_array($arg)) {
                if (!empty($arg)){
                    $relationItems[get_class($arg[0])] = $arg;
                }
                continue;
            } else if (is_object($arg)){
                $args[] = $arg->getId();
            } else {
                $args[] = $arg;
            }

            if (!empty($valueString)){
                $valueString .= ", ";
            }

            $valueString .= "?";
        }

        $sql = "INSERT INTO $table($keyString) VALUES ($valueString)";
        $this->database->query($sql, $args);

        if (!empty($relationItems)){
            $entityId = $this->database->query("SELECT LAST_INSERT_ID()")[0]["LAST_INSERT_ID()"];
            foreach ($relationItems as $relationType => $relationItem)
            {
                $this->handleRelation($entity, $entityId, $relationType, $relationItem);
            }
        }


    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function updateEntity($entity): void
    {

        $class = get_class($entity);
        $id = $entity->getId();


        if (empty($this->repository->findById($class, $id))){
            throw new Exception("No record found for this Id");
        }

        $reflection = new ReflectionClass($class);
        $props = $reflection->getProperties();
        $table = $props[0]->getDefaultValue();

        array_shift($props);
        array_shift($props);

        $getMethods = array_filter($reflection->getMethods(), function($method) {
            return str_starts_with($method->getName(), 'get');
        });

        array_shift($getMethods);

        $updateString = "";
        foreach ($props as $key => $property)
        {
            if ($property->getType()->getName() === "array"){
                continue;
            }

            if (!empty($updateString)){
                $updateString .= ", ";
            }

            $updateString .=  $property->getName()." = ?";
        }

        $args = [];
        $relationItems = [];
        foreach ($getMethods as $method)
        {
            $arg = $method->invoke($entity);
            if (is_array($arg)) {
                $relationItems[$this->getRelationType($method->getName())] = $arg;
            } else if (is_object($arg)){
                $args[] = $arg->getId();
            } else {
                $args[] = $arg;
            }
        }

        $args[] = $id;
        $sql = "UPDATE $table SET $updateString WHERE id = ?";

        $this->database->query($sql, $args);

        if (!empty($relationItems)){
            $entityId = $this->database->query("SELECT LAST_INSERT_ID()")[0]["LAST_INSERT_ID()"];
            foreach ($relationItems as $relationType => $relationItem)
            {
                $this->handleRelation($entity, $id, $relationType, $relationItem);
            }
        }
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function deleteEntity($entity): bool|array
    {
        $class = get_class($entity);
        $id = $entity->getId();

        if (empty($this->repository->findById($class, $id))){
            throw new Exception("No record found for this Id");
        }

        $reflection = new ReflectionClass($class);
        $props = $reflection->getProperties();
        $table = $props[0]->getDefaultValue();

        $sql = "DELETE from $table WHERE id = ?";
        return $this->database->query($sql, [$id]);
    }

    /**
     * @throws ReflectionException
     */
    public function handleRelation(object $entityClass, $entityId, string $relationType , array $relationItems): void
    {
        $entityTable = new ReflectionClass($entityClass);
        $entityTable = $entityTable->getProperty("tableName")->getDefaultValue();

        $relationTable = new ReflectionClass($relationType);
        $relationTable = $relationTable->getProperty("tableName")->getDefaultValue();

        $linkingTable = $this->database->getLinkingTable($entityTable, $relationTable);

        if ($linkingTable){
            $sql = "DELETE from $linkingTable WHERE ".$entityTable."_id = ?";
            $this->database->query($sql, [$entityId]);

            foreach ($relationItems as $relationItem) {
                $sql = "INSERT INTO $linkingTable(".$entityTable."_id, ".$relationTable."_id) VALUES (?, ?)";
                $this->database->query($sql, [$entityId, $relationItem->getId()]);
           }
        }

    }

    private function getRelationType($getMethod): ?string
    {
        $name = substr($getMethod, 3, -1);
        $name = ucfirst($name);
        $class = "Model\\".$name;


        if (class_exists($class)){
            return $class;
        }

        return null;

    }


}