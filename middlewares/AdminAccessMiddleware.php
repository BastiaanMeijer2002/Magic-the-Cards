<?php

namespace Middleware;

use Core\Middleware;
use Core\RedirectResponse;
use Core\Request;
use ReflectionException;
use Service\PermissionService;

class AdminAccessMiddleware
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
     * @throws ReflectionException
     */
    public function process(Request $request): Request|RedirectResponse
    {
        if (!$this->permissionService->checkAdminLevel()){
            return new RedirectResponse("/login?error=You+are+not+an+admin");
        }

        return $request;
    }
}