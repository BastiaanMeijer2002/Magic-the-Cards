<?php

namespace Controller;

use Core\RedirectResponse;
use Core\Request;
use Core\Response;
use ReflectionException;
use Service\CardService;

class CardController
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
     * @throws ReflectionException
     */
    public function deleteCard(Request $request): RedirectResponse
    {
        $body = $request->getRequestBody();

        if (!$body["card"]) {
            return new RedirectResponse("/admin?error=Please+select+user");
        }

        if (!$this->cardService->deleteCard($body["card"])) {
            return new RedirectResponse("/admin?error=Something+went+wrong");
        }

        return new RedirectResponse("/admin");
    }

    /**
     * @throws ReflectionException
     */
    public function addCard(Request $request): RedirectResponse
    {
        $files = $request->getFiles();

        if (!array_key_exists("file", $files)) {
            return new RedirectResponse("/admin?error=Please+select+an+image");
        }

        $body = $request->getRequestBody();

        if (!$body["name"]) {
            return new RedirectResponse("/admin?error=Please+enter+a+name");
        }

        $this->cardService->addCustomCard($body["name"], $files["file"]);

        return new RedirectResponse("/admin");
    }


}