<?php

namespace Service;

use Core\Service;
use Exception;

class EnvironmentService
{
    protected array $environmentVariables = [];

    /**
     * @throws Exception
     */
    public function loadVariables(): void
    {
        $file = dirname(__DIR__, 1) . "/.env";

        if (is_file($file)) {
            $data = file_get_contents($file);
            $data = explode("\n", $data);

            foreach ($data as $var) {
                $vars = explode("=", $var);
                $this->environmentVariables[$vars[0]] = $vars[1];
            }
        } else {
            throw new Exception("Environment file not found");
        }
    }

    /**
     * @throws Exception
     */
    public function getVariable(String $name)
    {
        $this->loadVariables();
        return $this->environmentVariables[$name];
    }
}