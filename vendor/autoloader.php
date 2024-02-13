<?php
function autoloader($class): void
{

    $locations = [
        "Controller\\" => dirname(__DIR__, 1) . "/controllers/",
        "Core\\" => dirname(__DIR__, 1) . "/core/",
        "Service\\" => dirname(__DIR__, 1) . "/services/",
        "Model\\" => dirname(__DIR__, 1) . "/models/",
        "Middleware\\" => dirname(__DIR__, 1) . "/middlewares/"
    ];

    foreach ($locations as $namespace => $location) {
        $classFile = str_replace($namespace, "", $class) . '.php';
        $classPath = str_replace('\\', '/', $classFile);
        $filePath = $location . $classPath;

        if (file_exists($filePath)) {
            require_once $filePath;
        }
    }
}

spl_autoload_register("autoloader", true, true);