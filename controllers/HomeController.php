<?php

namespace Controller;

use Core\Request;
use Core\Response;
use Core\Template;
use Service\CardService;

class HomeController
{
    private CardService $cardService;

    /**
     * @param CardService $cardService
     */
    public function __construct(CardService $cardService)
    {
        $this->cardService = $cardService;
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

        return new Response(Template::render("home", ["cards" => $cardList]));
    }
}