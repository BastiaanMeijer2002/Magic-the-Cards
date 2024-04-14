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

        if (!$user) {
            return false;
        }

        if ($user->getIsAdmin() == 1) {
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

        if (!$user) {
            return false;
        }

        if ($user->getIsPremium() == 1) {
            return true;
        }

        return false;
    }
}