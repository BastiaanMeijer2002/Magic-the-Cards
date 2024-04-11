<?php

namespace Controller;

use Core\Request;
use Core\Response;
use Core\Template;
use ReflectionException;
use Service\DeckService;

class DeckController
{
    private DeckService $deckService;

    /**
     * @param DeckService $deckService
     */
    public function __construct(DeckService $deckService)
    {
        $this->deckService = $deckService;
    }


    /**
     * @throws ReflectionException
     */
    public function index(Request $request): Response
    {
        $decks = $this->deckService->getCurrentUserDecks();
        $deckList = [];
        foreach ($decks as $deck) {
            $cardList = "";
            foreach ($deck->getCards() as $card) {
                $cardList .= Template::render('deck/deck', ["name" => $card->getName(), "img" => $card->getImg()]);
            }
            $deckList[] = ["deckName" => $deck->getName(), "cards" => $cardList];
        }

        return new Response(Template::render("deck/index", ["decks" => $deckList]));
    }

}