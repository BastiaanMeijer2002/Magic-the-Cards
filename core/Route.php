<?php

namespace Core;

class Route
{
    public string $method;
    public string $uri;
    public object $controller;
    public string $function;
    public array $middleware;

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     */
    public function setUri(string $uri): void
    {
        $this->uri = $uri;
    }

    /**
     * @return object
     */
    public function getController(): object
    {
        return $this->controller;
    }

    /**
     * @param $controller
     */
    public function setController($controller): void
    {
        $this->controller = $controller;
    }

    /**
     * @return string
     */
    public function getFunction(): string
    {
        return $this->function;
    }

    /**
     * @param string $function
     */
    public function setFunction(string $function): void
    {
        $this->function = $function;
    }

    /**
     * @return array
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    /**
     * @param array $middleware
     */
    public function setMiddleware(array $middleware): void
    {
        $this->middleware = $middleware;
    }





}