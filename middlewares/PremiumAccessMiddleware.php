<?php

namespace Middleware;

use Core\RedirectResponse;
use Core\Request;
use Service\PermissionService;

class PremiumAccessMiddleware
{
    private PermissionService $permissionService;

    /**
     * @param PermissionService $permissionService
     */
    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * @throws \ReflectionException
     */
    public function process(Request $request): Request|RedirectResponse
    {
        if (!$this->permissionService->checkPremiumLevel()) {
            return new RedirectResponse("/login?error=You+are+not+a+Premium+member");
        }
        return $request;
    }
}