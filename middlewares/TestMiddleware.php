<?php

namespace Middleware;

use Core\Request;

class TestMiddleware
{
    public function process(Request $request): Request
    {
        $request->setPathInfo("/mooi");
        return $request;
    }
}