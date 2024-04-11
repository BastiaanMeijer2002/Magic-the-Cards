<?php

namespace Controller;

use Core\Request;
use Core\Response;
use Core\Template;

class DeckController
{
    public function index(Request $request): Response
    {
        return new Response(Template::render("deck/index", []));
    }

}