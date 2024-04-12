<?php

namespace Controller;

use Core\RedirectResponse;
use Core\Request;
use Core\Response;
use Core\Template;
use Model\User;
use Service\AuthService;
use Service\RepositoryService;

class LoginController
{
    private RepositoryService $repository;
    private AuthService $authService;

    /**
     * @param RepositoryService $repository
     */
    public function __construct(RepositoryService $repository, AuthService $authService)
    {
        $this->repository = $repository;
        $this->authService = $authService;
    }

    public function index(Request $request, $error = null): Response
    {
        if ($error != null) {
            return new Response(Template::render("login/error", ["error" => $error]));
        }

        return new Response(Template::render("login/index", []));
    }

    /**
     * @throws \ReflectionException
     */
    public function loginUser(Request $request): RedirectResponse
    {
        $body = $request->getRequestBody();

        if (!$body["email"] || !$body["password"]) {
            return new RedirectResponse("/login?error=Please+enter+all+fields");
        }

        if (count($this->repository->findAllCustom(User::class, ["email" => $body["email"]])) < 1) {
            return new RedirectResponse("/login?error=User+does+not+exist");
        }

        $user = $this->repository->findAllCustom(User::class, ["email" => $body["email"]])[0];

        if (!password_verify($body["password"], $user->getPasswordHash())) {
            return new RedirectResponse("/login?error=User+does+not+exist");
        }

        $this->authService->setAuth(true, $user->getId());

        return new RedirectResponse("/");
    }

    /**
     * @throws \ReflectionException
     */
    public function logoutUser(Request $request): RedirectResponse
    {
        $user = $this->authService->getCurrentUser();

        $this->authService->setAuth(false, $user->getId());

        return new RedirectResponse("/");
    }

}