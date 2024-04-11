<?php

namespace Service;

use Exception;

class RoutingService
{
    private RequestHandlerService $requestHandler;
    private RouteRepositoryService $routes;

    /**
     * @param RouteRepositoryService $routes
     * @param RequestHandlerService $requestHandler
     */
    public function __construct(RouteRepositoryService $routes, RequestHandlerService $requestHandler)
    {
        $this->routes = $routes;
        $this->requestHandler = $requestHandler;
    }


    public function handleParameters($request): array
    {
        $parameters["request"] = $request;

        if($request->getQueryString()) {
            $parametersPairs = explode("&", $request->getQueryString());
            foreach ($parametersPairs as $parametersPair) {
                $pair = explode("=", $parametersPair);
                $parameters[$pair[0]] = str_replace("+", " ", $pair[1]);
            }
        }

        return $parameters;
    }

    /**
     * @throws Exception
     */
    public function route($request)
    {
        $this->requestHandler->handle($request);

        foreach ($this->routes->getRoutes() as $route){
            if ($route->getMethod() === $request->getMethod() && $route->getUri() === $request->getPathInfo()) {
                return call_user_func_array([$route->getController(), $route->getFunction()], $this->handleParameters($request));
            }
        }

        return null;
    }





}