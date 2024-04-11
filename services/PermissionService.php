<?php

namespace Service;

use Exception;
use Model\User;
use ReflectionException;

class PermissionService
{
    private AuthService $auth;

    /**
     * @param AuthService $auth
     */
    public function __construct(AuthService $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @throws ReflectionException
     */
    public function checkAdminLevel(): bool
    {
        $user = $this->auth->getCurrentUser();

        if ($user->isAdmin == 1) {
            return true;
        }

        return false;
    }

    /**
     * @throws ReflectionException
     */
    public function checkPremiumLevel(): bool
    {
        $user = $this->auth->getCurrentUser();

        if ($user->isPremium == 1) {
            return true;
        }

        return false;
    }
}