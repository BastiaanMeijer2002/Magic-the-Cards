<?php

namespace Service;

use Core\Controller;
use Core\Route;
use Exception;

class RouteRepositoryService
{
    /**
     * @throws Exception
     */
    public function getRoutes(): array
    {
        global $routes;
        include 'routes.php';

        $routeList = [];
        foreach ($routes as $route)
        {
            $routeObject = new Route();
            $routeObject->setMethod($route["method"]);
            $routeObject->setUri($route["uri"]);
            $routeObject->setController($route["controller"]);
            $routeObject->setFunction($route["function"]);

            $routeList[] = $routeObject;
        }

        return $routeList;
    }

    public function getMiddleware(string $uri): array
    {
        global $routes;
        include 'routes.php';

        $routeList = [];
        foreach ($routes as $route)
        {
            if ($route['uri'] === $uri){
                return $route["middleware"];
            }
        }

        return [];
    }
}