<?php

namespace Service;

use Model\User;
use ReflectionException;

class AuthService
{
    protected DatabaseService $database;
    private RepositoryService $repository;

    public function __construct(DatabaseService $database, RepositoryService $repository)
    {
        $this->database = $database;
        $this->repository = $repository;
    }

    public function checkAuth(): bool
    {
        $status = $this->database->query("SELECT logged_in FROM session_status WHERE session_id = ? ORDER BY id DESC ", [session_id()]);

        if (isset($status[0]["logged_in"])){
            if ($status[0]["logged_in"] === "1") {
                return true;
            }

            return false;
        }

        return false;

    }

    public function setAuth(bool $status, string $userId): void
    {
        $this->database->query("INSERT INTO session_status(session_id, session_status.logged_in, session_status.user_id) VALUES (?, ?, ?)", [session_id(), intval($status), $userId]);
    }

    /**
     * @throws ReflectionException
     */
    public function getCurrentUser(): User|bool
    {
        if (!$this->checkAuth()){
            return false;
        }

        $currentSession = $this->database->query("SELECT user_id FROM session_status WHERE session_id = ? ORDER BY id DESC", [session_id()]);

        return $this->repository->findById(User::class, $currentSession[0]["user_id"]);
    }
}