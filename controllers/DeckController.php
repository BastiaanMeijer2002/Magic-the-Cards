<?php

namespace Controller;

use Core\RedirectResponse;
use Core\Request;
use Core\Response;
use Core\Template;
use ReflectionException;
use Service\CardService;
use Service\DeckService;

class DeckController
{
    private DeckService $deckService;
    private CardService $cardService;

    /**
     * @param DeckService $deckService
     */
    public function __construct(DeckService $deckService, CardService $cardService)
    {
        $this->deckService = $deckService;
        $this->cardService = $cardService;
    }

    /**
     * @throws ReflectionException
     */
    public function index(Request $request, $error = ""): Response
    {
        $decks = $this->deckService->getCurrentUserDecks();
        $deckList = [];

        foreach ($decks as $deck) {
            $cardList = "";
            $cardCount = 0;
            foreach ($deck->getCards() as $card) {
                $cardCount++;
                $cardList .= Template::render('deck/deck', ["deck" =>$deck->getId(),"id" => $card->getId(),"name" => $card->getName(), "img" => $card->getImg()]);
            }

            $deckList[] = ["id" => $deck->getId(),"deckName" => $deck->getName(),"cardCount" => $cardCount, "cards" => $cardList];
        }

        $errorStatus = false;
        if ($error != "") {
            $errorStatus = true;
        }

        return new Response(Template::render("deck/index", ["decks" => $deckList, "error" => $error, "errorStatus" => $errorStatus]));
    }

    /**
     * @throws ReflectionException
     */
    public function addDeck(Request $request): RedirectResponse
    {
        $body = $request->getRequestBody();

        if (!$body["name"]) {
            $uri = "/decks?error=Please+enter+a+name";
            return new RedirectResponse($uri);
        }

        $this->deckService->addDeck($body["name"]);

        return new RedirectResponse("/decks");
    }

    /**
     * @throws ReflectionException
     */
    public function deleteDeck(Request $request, $deck=null): RedirectResponse
    {
        if ($deck != null) {
            $delete = $this->deckService->deleteDeck($deck);

            if (!$delete) {
                return new RedirectResponse("/decks?error=Something+went+wrong");
            }

            return new RedirectResponse("/decks");
        }

        return new RedirectResponse("/decks?error=Please+select+a+deck");
    }

    /**
     * @throws ReflectionException
     */
    public function addCartToDeck(Request $request, $deck = null, $search = ''): Response
    {
        $cardList = [];

        if ($search != null) {
            $cards = $this->cardService->getCard($search);
        } else {
            $cards = $this->cardService->getCards();
        }

        foreach ($cards as $card) {
            $cardList[] = ["id" => $card->getId(),"name" => $card->getName(), "img" => $card->getImg()];
        }

        return new Response(Template::render("deck/add_cards", ["cards" => $cardList, "deck" => $deck]));
    }

    /**
     * @throws ReflectionException
     */
    public function handleCartToDeck(Request $request): RedirectResponse
    {
        $body = $request->getRequestBody();

        if (!$body["deck"] || !$body["card"]) {
            return new RedirectResponse("/decks?error=Please+select+a+card");
        }

        $add = $this->deckService->addCartToDeck($body["deck"], $body["card"]);

        if (!$add) {
            return new RedirectResponse("/decks?error=Adding+card+to+deck+failed");
        }

        return new RedirectResponse("/decks");
    }

    /**
     * @throws ReflectionException
     */
    public function handleRemoveCard(Request $request, $deck = null, $card = null): RedirectResponse
    {
        if (!$deck || !$card) {
            return new RedirectResponse("/decks?error=Please+select+card+and+deck");
        }

        $delete = $this->deckService->deleteCardFromDeck($deck, $card);

        if (!$delete) {
            return new RedirectResponse("/decks?error=Something+went+wrong");
        }

        return new RedirectResponse("/decks");
    }

}