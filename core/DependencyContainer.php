<?php

namespace Core;

use Exception;
use Psr\Container\ContainerInterface;

class DependencyContainer implements ContainerInterface
{
    protected array $dependencies = [];

    /**
     * @throws Exception
     */
    public function get(string $id)
    {
        var_dump($this->has($id));
        if ($this->has($id)) {
            return $this->dependencies[$id];
        }

        throw new Exception("dependency not found");
    }

    public function set(string $id, $dependency): void
    {
        if (!$this->has($id)) {
            $this->dependencies[$id] = $dependency;
        }
    }

    public function has(string $id): bool
    {
        return isset($this->dependencies[$id]);
    }
}