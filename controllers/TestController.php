<?php

namespace Controller;

use Core\Controller;
use Core\Request;
use Core\Response;

class TestController extends Controller
{
    /**
     * @throws \ReflectionException
     */
    public function index(Request $request)
    {
        return new Response($this->template->render("test", []));
    }
}