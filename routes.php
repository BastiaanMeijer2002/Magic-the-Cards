<?php

$routes = [
    ["method" => "GET", "uri" => "/", "controller" => "homeController", "function" => "index", "middleware" => ["authMiddleware"]],
    ["method" => "GET", "uri" => "/register", "controller" => "registerController", "function" => "index", "middleware" => []],
    ["method" => "POST", "uri" => "/register", "controller" => "registerController", "function" => "registerUser", "middleware" => []],
    ["method" => "GET", "uri" => "/login", "controller" => "loginController", "function" => "index", "middleware" => []],
    ["method" => "POST", "uri" => "/login", "controller" => "loginController", "function" => "loginUser", "middleware" => []],
    ["method" => "GET", "uri" => "/decks", "controller" => "deckController", "function" => "index", "middleware" => ["authMiddleware", "premiumAccessMiddleware"]],
];
