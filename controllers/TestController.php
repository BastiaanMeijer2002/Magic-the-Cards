<?php

namespace Controller;

use Core\Request;
use Core\Response;
use Core\Template;
use Service\CardService;

class TestController
{
    /**
     * @throws \ReflectionException
     */
    public function index(Request $request): Response
    {
        return new Response(Template::render("test", []));
    }
}