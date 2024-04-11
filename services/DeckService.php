<?php

namespace Service;

use Model\Deck;

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
     * @throws \ReflectionException
     */
    public function getCurrentUserDecks(): array
    {
        $user = $this->authService->getCurrentUser();

        return $this->repositoryService->findAllCustom(Deck::class, ["user" => $user]);
    }


}