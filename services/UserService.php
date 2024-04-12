<?php

namespace Service;

use Core\RedirectResponse;
use Model\User;
use ReflectionException;

class UserService
{
    private EntityManagerService $entityManagerService;
    private RepositoryService $repositoryService;
    private AuthService $authService;

    /**
     * @param EntityManagerService $entityManagerService
     * @param RepositoryService $repositoryService
     */
    public function __construct(EntityManagerService $entityManagerService, RepositoryService $repositoryService, AuthService $authService)
    {
        $this->entityManagerService = $entityManagerService;
        $this->repositoryService = $repositoryService;
        $this->authService = $authService;
    }

    /**
     * @throws ReflectionException
     */
    public function getUsers(): array
    {
        return $this->repositoryService->findAll(User::class);
    }

    /**
     * @throws ReflectionException
     */
    public function updateAdmin(string $user): bool
    {
        $user = $this->repositoryService->findById(User::class, intval($user));

        if (!$user) {
            return false;
        }

        $user->setIsAdmin(!$user->getIsAdmin());

        $this->entityManagerService->updateEntity($user);

        return true;
    }

    /**
     * @throws ReflectionException
     */
    public function updatePremium(string $user): bool
    {
        $user = $this->repositoryService->findById(User::class, intval($user));

        if (!$user) {
            return false;
        }

        $user->setIsPremium(!$user->getIsPremium());

        $this->entityManagerService->updateEntity($user);

        return true;
    }

    /**
     * @throws ReflectionException
     */
    public function deleteUser(string $user): bool
    {
        $user = $this->repositoryService->findById(User::class, intval($user));

        if (!$user || $user == $this->authService->getCurrentUser()) {
            return false;
        }

        $this->entityManagerService->deleteEntity($user);

        return true;

    }


}