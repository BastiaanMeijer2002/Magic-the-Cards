<?php

namespace Middleware;

use Core\Middleware;
use Core\RedirectResponse;
use Core\Request;
use ReflectionException;

class AdminAccessMiddleware extends Middleware
{
    /**
     * @throws ReflectionException
     */
    public function process(Request $request): Request|RedirectResponse
    {
        if (!$this->permissions->checkPermission("admin")){
            return new RedirectResponse("/login");
        }

        return $request;
    }
}