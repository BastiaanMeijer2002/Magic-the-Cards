<?php

namespace Core;

use Exception;
use ReflectionClass;

class DependencyContainer
{
    protected static $instance;
    private array $services = [];

    public function add($service): void
    {
        $this->services[$service] = $service;
    }

    private function getService($service)
    {
        return $this->services[$service];
    }

    /**
     * @throws Exception
     */
    public function get($service, $args = [])
    {
        if (!$this->has($service)){
            $this->add($service);
        }

        $service = $this->getService($service);

        if (is_callable($service)){
            return call_user_func_array($service, $args);
        }

        if (class_exists($service)){
            $reflection = new ReflectionClass($service);
            $constructor = $reflection->getConstructor();

            if (!$constructor){
                return $reflection->newInstanceWithoutConstructor();
            }

            $dependencies = [];


            foreach ($constructor->getParameters() as $parameter) {
                $parameter = $parameter->getType();
                $parameter = $parameter->getName();
                $dependencies[] = $this->get($parameter, $args);
            }

            return $reflection->newInstanceArgs($dependencies);

        }

        return null;
    }

    public function has($serviceName): bool
    {
        return isset($this->services[$serviceName]);
    }

    public static function instance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

}