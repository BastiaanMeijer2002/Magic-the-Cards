<?php

namespace Service;

use Core\Request;
use ReflectionClass;
use ReflectionException;

class RequestHandlerService
{
    private RouteRepositoryService $routes;

    /**
     * @param RouteRepositoryService $routes
     */
    public function __construct(RouteRepositoryService $routes)
    {
        $this->routes = $routes;
    }

    /**
     * @throws ReflectionException
     */
    public function handle(Request $request): Request
    {
        $middlewares = $this->routes->getMiddleware($request->getPathInfo());

        if(!empty($middlewares)){
            foreach ($middlewares as $middleware){
                $middleware = new ReflectionClass($middleware);
                $middleware = $middleware->newInstance();
                $request = $middleware->process($request);
            }
        }

        return $request;
    }


}