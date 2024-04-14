<?php

namespace Service;

use Core\DependencyContainer;
use Core\RedirectResponse;
use Core\Request;
use ReflectionClass;
use ReflectionException;

class RequestHandlerService
{
    private RouteRepositoryService $routes;
    private DependencyContainer $container;

    /**
     * @param RouteRepositoryService $routes
     */
    public function __construct(RouteRepositoryService $routes, DependencyContainer $container)
    {
        $this->routes = $routes;
        $this->container = $container;
    }

    /**
     * @throws ReflectionException
     * @throws \Exception
     */
    public function handle(Request $request): mixed
    {
        $middlewares = $this->routes->getMiddleware($request->getPathInfo());

        if(!empty($middlewares)){
            foreach ($middlewares as $middleware){
                $middleware = $this->container->get($middleware);
                $request = $middleware->process($request);
            }
        }

        return $request;
    }


}