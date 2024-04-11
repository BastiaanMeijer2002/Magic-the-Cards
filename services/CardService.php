<?php

namespace Service;

use Model\Card;
use ReflectionException;

class CardService
{
    private EntityManagerService $entityManagerService;
    private RepositoryService $repositoryService;

    /**
     * @param EntityManagerService $entityManagerService
     * @param RepositoryService $repositoryService
     */
    public function __construct(EntityManagerService $entityManagerService, RepositoryService $repositoryService)
    {
        $this->entityManagerService = $entityManagerService;
        $this->repositoryService = $repositoryService;
    }

    /**
     * @throws ReflectionException
     */
    public function retrieveCards(): void
    {
        $cards = file_get_contents("https://api.magicthegathering.io/v1/cards");

        if ($cards) {
            $cards = json_decode($cards);
            foreach ($cards->cards as $card) {
                $name = $card->name ?? "";
                $img = $card->imageUrl ?? "";
                $card = new Card($name, $img);

                if ($card->getImg() != "") {
                    $existingCard = $this->repositoryService->cardExistsByName(Card::class, $card);
                    if ($existingCard != null) {
                        $card->setId($existingCard->getId());
                        $this->entityManagerService->updateEntity($card);
                    } else {
                        $this->entityManagerService->addEntity($card);
                    }
                }
            }
        }
    }

    /**
     * @throws ReflectionException
     */
    public function getCards(): bool|array
    {
        return $this->repositoryService->findAll(Card::class);
    }

    /**
     * @throws ReflectionException
     */
    public function getCard(String $name): bool|array
    {
        return $this->repositoryService->findAllCustom(Card::class, ["name" => $name]);
    }
}