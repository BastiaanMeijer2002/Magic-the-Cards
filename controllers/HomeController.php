<?php

namespace Controller;

use Core\Request;
use Core\Response;
use Core\Template;
use Service\CardService;
use Service\PermissionService;

class HomeController
{
    private CardService $cardService;
    private PermissionService $permissionService;

    /**
     * @param CardService $cardService
     */
    public function __construct(CardService $cardService, PermissionService $permissionService)
    {
        $this->cardService = $cardService;
        $this->permissionService = $permissionService;
    }

    /**
     * @throws \ReflectionException
     */
    public function index(Request $request, $search = null): Response
    {
        $cardList = [];

        if ($search != null) {
            $cards = $this->cardService->getCard($search);
        } else {
            $cards = $this->cardService->getCards();
        }

        foreach ($cards as $card) {
            $cardList[] = ["name" => $card->getName(), "img" => $card->getImg()];
        }

        $isPremium = $this->permissionService->checkPremiumLevel();
        $isAdmin = $this->permissionService->checkAdminLevel();

        return new Response(Template::render("home", ["cards" => $cardList, "isPremium" => $isPremium, "isAdmin" => $isAdmin]));
    }
}