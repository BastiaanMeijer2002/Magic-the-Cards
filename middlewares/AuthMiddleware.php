<?php

namespace Middleware;

use Core\Middleware;
use Core\RedirectResponse;
use Core\Request;

class AuthMiddleware extends Middleware
{
    public function process(Request $request): Request|RedirectResponse
    {
        if (!$this->authService->checkAuth()){
            return new RedirectResponse("/login");
        }

        return $request;
    }
}