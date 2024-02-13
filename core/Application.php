<?php

namespace Core;

use Exception;
use Service\RoutingService;

class Application
{
    public RoutingService $router;
    public DependencyContainer $container;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        session_start();
        $this->container = DependencyContainer::instance();
        $this->router = $this->container->get(RoutingService::class);

    }
}