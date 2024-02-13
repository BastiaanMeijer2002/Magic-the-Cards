<?php

namespace Core;

use Service\AuthService;
use Service\PermissionService;

class Middleware
{
    public AuthService $authService;
    public PermissionService $permissions;

    public function __construct()
    {
        $this->authService = DependencyContainer::instance()->get(AuthService::class);
        $this->permissions = DependencyContainer::instance()->get(PermissionService::class);
    }


}