<?php

namespace Controller;

use Core\Request;
use Core\Response;
use Core\Template;

class TestController
{
    public function index(Request $request): Response
    {
        return new Response(Template::render("test", []));
    }
}