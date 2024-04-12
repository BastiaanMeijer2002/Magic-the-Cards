<?php

namespace Service;

use Model\Card;
use Model\Deck;
use ReflectionException;

class DeckService
{
    private RepositoryService $repositoryService;
    private EntityManagerService $entityManagerService;
    private AuthService $authService;

    /**
     * @param RepositoryService $repositoryService
     * @param EntityManagerService $entityManagerService
     * @param AuthService $authService
     */
    public function __construct(RepositoryService $repositoryService, EntityManagerService $entityManagerService, AuthService $authService)
    {
        $this->repositoryService = $repositoryService;
        $this->entityManagerService = $entityManagerService;
        $this->authService = $authService;
    }

    /**
     * @throws ReflectionException
     */
    public function getCurrentUserDecks(): array
    {
        $user = $this->authService->getCurrentUser();

        return $this->repositoryService->findAllCustom(Deck::class, ["user" => $user]);
    }

    /**
     * @throws ReflectionException
     */
    public function addDeck(string $name): void
    {
        $user = $this->authService->getCurrentUser();

        $deck = new Deck($name, $user);

        $this->entityManagerService->addEntity($deck);

    }

    /**
     * @throws ReflectionException
     */
    public function addCartToDeck(int $deck, string $card): bool
    {
        $card = $this->repositoryService->findById(Card::class, intval($card));

        if ($card == null) {
            return false;
        }

        $deck = $this->repositoryService->findById(Deck::class, $deck);

        if ($deck == null) {
            return false;
        }

        $cards = $deck->getCards();

        if (count($cards) > 9) {
            return false;
        }

        $count = 0;

        foreach ($cards as $deckCard) {
            if ($card == $deckCard) {
                $count++;
            }
        }

        if ($count > 1) {
            return false;
        }

        $deck->addCard($card);

        $this->entityManagerService->updateEntity($deck);

        return true;
    }

    /**
     * @throws ReflectionException
     */
    public function deleteDeck(string $deck): bool
    {
        $deck = $this->repositoryService->findById(Deck::class, intval($deck));

        if ($deck == null){
            return false;
        }

        $currentUser = $this->authService->getCurrentUser();

        if ($currentUser != $deck->getUser()) {
            return false;
        }

        return is_array($this->entityManagerService->deleteEntity($deck));
    }

    /**
     * @throws ReflectionException
     */
    public function deleteCardFromDeck(string $deck, string $card): bool
    {
        $card = $this->repositoryService->findById(Card::class, intval($card));

        if ($card == null) {
            return false;
        }

        $deck = $this->repositoryService->findById(Deck::class, intval($deck));

        if ($deck == null){
            return false;
        }

        $currentUser = $this->authService->getCurrentUser();

        if ($currentUser != $deck->getUser()) {
            return false;
        }

        $deck->deleteCard($card);

        $this->entityManagerService->updateEntity($deck);

        return true;
    }

}