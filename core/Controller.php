<?php

namespace Core;

use Service\AuthService;
use Service\DatabaseService;
use Service\EntityManagerService;
use Service\PermissionService;
use Service\RepositoryService;
use Service\RouteRepositoryService;

class Controller
{
    public Template $template;
    public EntityManagerService $entityManager;
    public RepositoryService $repository;
    public AuthService $auth;
    public DatabaseService $database;
    public RouteRepositoryService $routes;
    public PermissionService $permissions;

    public function __construct(Template $template, EntityManagerService $entityManager, RepositoryService $repository, AuthService $auth, DatabaseService $database, RouteRepositoryService $routes, PermissionService $permissions)
    {
        $this->template = $template;
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->auth = $auth;
        $this->database = $database;
        $this->routes = $routes;
        $this->permissions = $permissions;
    }


}