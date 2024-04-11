<?php

namespace Middleware;

use Core\Middleware;
use Core\RedirectResponse;
use Core\Request;
use Service\AuthService;

class AuthMiddleware
{
    private AuthService $authService;

    /**
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function process(Request $request): Request|RedirectResponse
    {
        if (!$this->authService->checkAuth()){
            return new RedirectResponse("/login");
        }

        return $request;
    }
}