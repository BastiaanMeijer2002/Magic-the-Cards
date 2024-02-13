<?php

namespace Core;

class Request
{
    private mixed $method;
    private mixed $pathInfo;
    private mixed $requestBody;
    private mixed $queryString;


    public function __construct()
    {
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->pathInfo = $_SERVER['PATH_INFO'] ?? '/';
        $this->requestBody = $_POST;
        $this->queryString = $_SERVER["QUERY_STRING"] ?? null;
    }

    /**
     * @return mixed
     */
    public function getQueryString(): mixed
    {
        return $this->queryString;
    }

    /**
     * @return string|bool
     */
    public function getRequestBody(): mixed
    {
        return $this->requestBody;
    }

    /**
     * @return mixed
     */
    public function getMethod(): mixed
    {
        return $this->method;
    }

    /**
     * @return mixed|string
     */
    public function getPathInfo(): mixed
    {
        return $this->pathInfo;
    }

    /**
     * @param mixed $pathInfo
     */
    public function setPathInfo(mixed $pathInfo): void
    {
        $this->pathInfo = $pathInfo;
    }

    /**
     * @param false|string $requestBody
     */
    public function setRequestBody(bool|string $requestBody): void
    {
        $this->requestBody = $requestBody;
    }




}