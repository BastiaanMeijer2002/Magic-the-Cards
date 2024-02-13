<?php

namespace Service;

use Model\Exam;
use Model\User;

class EnrollService
{
    private RepositoryService $repository;
    private EntityManagerService $entityManager;
    private PermissionService $permissions;

    /**
     * @param EntityManagerService $entityManager
     */
    public function __construct(EntityManagerService $entityManager, PermissionService $permissions)
    {
        $this->entityManager = $entityManager;
        $this->permissions = $permissions;
    }

    /**
     * @throws \Exception
     */
    public function enroll(User $user, Exam $exam): bool
    {
        $enrollDate = new \DateTime($exam->getEnrollDate());
        $now = new \DateTime("now");

        if ($enrollDate > $now || $this->permissions->checkPermission("teacher") || $this->permissions->checkPermission("admin"))
        {
            $exam->addUser($user);
            $this->entityManager->updateEntity($exam);
            return true;
        }

        return false;
    }

    /**
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function unenroll(User $user, Exam $exam): bool
    {
        $exam->deleteUser($user);
//        var_dump($user);
        $this->entityManager->updateEntity($exam);
        return true;
    }


}