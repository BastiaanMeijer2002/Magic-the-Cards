<?php

namespace Model;

class Deck
{
    public string $tableName = "decks";

    public int $id;
    public string $name;
    public array $cards = [];
    public User $user;

    /**
     * @param string $name
     * @param array $cards
     */
    public function __construct(string $name, User $user, array $cards = [])
    {
        $this->name = $name;
        $this->cards = $cards;
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * @param array $cards
     */
    public function setCards(array $cards): void
    {
        $this->cards = $cards;
    }

    public function addCard(Card $card): void
    {
        $this->cards[] = $card;
    }

    public function deleteCard(Card $card): void
    {
        $this->cards = array_filter($this->cards, function ($obj) use ($card) {
            return $obj->getId() !== $card->getId();
        });
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

}