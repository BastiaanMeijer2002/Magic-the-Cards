<?php

namespace Controller;

use Core\RedirectResponse;
use Core\Request;
use Core\Response;
use Core\Template;
use Service\CardService;
use Service\UserService;

class AdminDashboardController
{
    private UserService $userService;
    private CardService $cardService;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService, CardService $cardService)
    {
        $this->userService = $userService;
        $this->cardService = $cardService;
    }


    /**
     * @throws \ReflectionException
     */
    public function index(Request $request, $error=""): Response
    {
        $users = $this->userService->getUsers();
        $usersList = [];

        foreach ($users as $user) {
            $usersList[] = ["id" => $user->getId(), "name" => $user->getName(), "email" => $user->getEmail(),
                "isAdmin" => $user->getIsAdmin(), "isPremium" => $user->getIsPremium()];
        }

        $cards = $this->cardService->getCustomCards();
        $cardsList = [];

        foreach ($cards as $card) {
            $cardsList[] = ["id" => $card->getId(), "name" => $card->getName()];
        }

        $errorStatus = false;
        if ($error != "") {
            $errorStatus = true;
        }

        return new Response(Template::render("admin/index", ["users" => $usersList, "error" => $error, "errorStatus" => $errorStatus, "cards" => $cardsList]));
    }

    /**
     * @throws \ReflectionException
     */
    public function updateUserAdmin(Request $request): RedirectResponse
    {
        $body = $request->getRequestBody();

        if (!$body["user"]) {
            return new RedirectResponse("/admin?error=Please+select+user");
        }

        if (!$this->userService->updateAdmin($body["user"])) {
            return new RedirectResponse("/admin?error=Something+went+wrong");
        }

        return new RedirectResponse("/admin");
    }

    /**
     * @throws \ReflectionException
     */
    public function updateUserPremium(Request $request): RedirectResponse
    {
        $body = $request->getRequestBody();

        if (!$body["user"]) {
            return new RedirectResponse("/admin?error=Please+select+user");
        }

        if (!$this->userService->updatePremium($body["user"])) {
            return new RedirectResponse("/admin?error=Something+went+wrong");
        }

        return new RedirectResponse("/admin");
    }

    /**
     * @throws \ReflectionException
     */
    public function deleteUser(Request $request): RedirectResponse
    {
        $body = $request->getRequestBody();

        if (!$body["user"]) {
            return new RedirectResponse("/admin?error=Please+select+user");
        }

        if (!$this->userService->deleteUser($body["user"])) {
            return new RedirectResponse("/admin?error=User+not+found+or+user+is+you");
        }

        return new RedirectResponse("/admin");
    }

}