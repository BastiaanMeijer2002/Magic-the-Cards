<?php

namespace Core;

class Request
{
    private mixed $method;
    private mixed $pathInfo;
    private mixed $requestBody;
    private mixed $queryString;


    public function __construct($method, $pathInfo, $requestBody, $queryString)
    {
        $this->method = $method;
        $this->pathInfo = $pathInfo;
        $this->requestBody = $requestBody;
        $this->queryString = $queryString;
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