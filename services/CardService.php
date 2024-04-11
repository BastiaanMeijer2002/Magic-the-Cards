<?php

namespace Service;

use Model\Card;
use ReflectionException;

class CardService
{
    /**
     * @throws ReflectionException
     */
    public static function retrieveCards($entityManager, $repository): void
    {
        $cards = file_get_contents("https://api.magicthegathering.io/v1/cards");

        if ($cards) {
            $cards = json_decode($cards);
            foreach ($cards->cards as $card) {
                $name = $card->name ?? "";
                $img = $card->imageUrl ?? "";
                $card = new Card($name, $img);

                if ($card->getImg() != "") {
                    $existingCard = $repository->cardExistsByName(Card::class, $card);
                    if ($existingCard != null) {
                        $card->setId($existingCard->getId());
                        $entityManager->updateEntity($card);
                    } else {
                        $entityManager->addEntity($card);
                    }
                }
            }
        }
    }
}