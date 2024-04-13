<?php

namespace Service;

use Exception;
use Model\Card;
use ReflectionException;

class CardService
{
    private EntityManagerService $entityManagerService;
    private RepositoryService $repositoryService;
    private EnvironmentService $environmentService;
    private FileService $fileService;

    /**
     * @param EntityManagerService $entityManagerService
     * @param RepositoryService $repositoryService
     */
    public function __construct(EntityManagerService $entityManagerService, RepositoryService $repositoryService,
                                EnvironmentService $environmentService, FileService $fileService)
    {
        $this->entityManagerService = $entityManagerService;
        $this->repositoryService = $repositoryService;
        $this->environmentService = $environmentService;
        $this->fileService = $fileService;
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function retrieveCards(): void
    {
        $cards = file_get_contents($this->environmentService->getVariable("MAGIC_API"));

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

    /**
     * @throws ReflectionException
     */
    public function getCustomCards(): bool|array
    {
        return $this->repositoryService->findAllCustom(Card::class, ["isCustom" => 1]);
    }

    /**
     * @throws ReflectionException
     */
    public function deleteCard(int $card): bool
    {
        $card = $this->repositoryService->findById(Card::class, $card);

        if (!$card) {
            return false;
        }

        if (!$card->getIsCustom()) {
            return false;
        }

        $this->entityManagerService->deleteEntity($card);

        return true;
    }

    /**
     * @throws ReflectionException
     */
    public function addCustomCard(string $name, array $img): bool
    {
        $upload = $this->fileService->uploadFile($img);

        if ($upload == "") {
            return false;
        }

        $card = new Card($name, $upload, 1);

        $this->entityManagerService->addEntity($card);

        return true;
    }
}