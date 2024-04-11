<?php

namespace Controller;

use Core\RedirectResponse;
use Core\Request;
use Core\Response;
use Core\Template;
use Model\User;
use Service\EntityManagerService;
use Service\RepositoryService;

class RegisterController
{
    private RepositoryService $repository;
    private EntityManagerService $entityManager;

    /**
     * @param RepositoryService $repository
     * @param EntityManagerService $entityManager
     */
    public function __construct(RepositoryService $repository, EntityManagerService $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function index(Request $request, $error = null): Response
    {
        if ($error != null) {
            return new Response(Template::render("register/error", ["error" => $error]));
        }

        return new Response(Template::render("register/index", []));
    }

    /**
     * @throws \ReflectionException
     */
    public function registerUser(Request $request): RedirectResponse
    {
        $body = $request->getRequestBody();

        if (!$body["name"] || !$body["email"] || !$body["password"] || !$body["repeatpassword"]) {
            return new RedirectResponse("/register?error=Please+enter+all+fields");
        }

        if (count($this->repository->findAllCustom(User::class, ["email" => $body["email"]])) > 0) {
            return new RedirectResponse("/register?error=User+already+exists+with+this+email");
        }

        if ($body["password"] != $body["repeatpassword"]) {
            return new RedirectResponse("/register?error=Passwords+do+not+match");
        }

        $user = new User($body["name"], $body["email"], password_hash($body["password"], PASSWORD_BCRYPT));
        $this->entityManager->addEntity($user);

        return new RedirectResponse("/login");
    }

}