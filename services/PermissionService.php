<?php

namespace Service;

use Exception;
use Model\User;
use ReflectionException;

class PermissionService
{
    private DatabaseService $database;
    private AuthService $auth;

    /**
     * @param DatabaseService $database
     * @param AuthService $auth
     */
    public function __construct(DatabaseService $database, AuthService $auth)
    {
        $this->database = $database;
        $this->auth = $auth;

    }

    /**
     * @throws ReflectionException
     */
    public function checkPermission(string $permissionLevel): bool
    {
       $userRoles = $this->getRoles();

       foreach ($userRoles as $role)
       {
           if ($role["name"] === $permissionLevel){
               return true;
           }
       }

       return false;
    }

    /**
     * @throws ReflectionException
     */
    public function getRoles(): bool|array
    {
        $user = $this->auth->getCurrentUser();

        if (!$user){
            return false;
        }

        return $this->database->query("SELECT r.name
                                        FROM roles r
                                        JOIN users_roles ur ON ur.role_id = r.id
                                        JOIN users u ON u.id = ur.user_id
                                        WHERE u.id = ?;", [$user->getId()]);

    }

    /**
     * @throws Exception
     */
    public function addRole(User $user, string $role): void
    {
        $this->deleteRole($user);
        $role = $this->database->query("SELECT id FROM roles WHERE name = ?", [$role]);

        if (empty($role)){
            throw new Exception("Role not found");
        }

        $this->database->query("INSERT INTO users_roles(user_id, role_id) VALUES (?, ?)", [$user->getId(), $role[0]["id"]]);

    }

    /**
     * @throws Exception
     */
    public function deleteRole(User $user): void
    {

        $this->database->query("DELETE FROM users_roles WHERE user_id = ?", [$user->getId()]);

    }
    public function getRole(User $user): bool|array
    {
        return $this->database->query("SELECT r.name
                                        FROM roles r
                                        JOIN users_roles ur ON ur.role_id = r.id
                                        JOIN users u ON u.id = ur.user_id
                                        WHERE u.id = ?;", [$user->getId()])[0];

    }

}