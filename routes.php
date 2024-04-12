<?php

$routes = [
    ["method" => "GET", "uri" => "/", "controller" => "homeController", "function" => "index", "middleware" => ["authMiddleware"]],
    ["method" => "GET", "uri" => "/register", "controller" => "registerController", "function" => "index", "middleware" => []],
    ["method" => "POST", "uri" => "/register", "controller" => "registerController", "function" => "registerUser", "middleware" => []],
    ["method" => "GET", "uri" => "/login", "controller" => "loginController", "function" => "index", "middleware" => []],
    ["method" => "POST", "uri" => "/login", "controller" => "loginController", "function" => "loginUser", "middleware" => []],
    ["method" => "GET", "uri" => "/decks", "controller" => "deckController", "function" => "index", "middleware" => ["authMiddleware", "premiumAccessMiddleware"]],
    ["method" => "POST", "uri" => "/decks/add", "controller" => "deckController", "function" => "addDeck", "middleware" => ["authMiddleware", "premiumAccessMiddleware"]],
    ["method" => "GET", "uri" => "/deck/card/add", "controller" => "deckController", "function" => "addCartToDeck", "middleware" => ["authMiddleware", "premiumAccessMiddleware"]],
    ["method" => "POST", "uri" => "/deck/card/add", "controller" => "deckController", "function" => "handleCartToDeck", "middleware" => ["authMiddleware", "premiumAccessMiddleware"]],
    ["method" => "GET", "uri" => "/deck/delete", "controller" => "deckController", "function" => "deleteDeck", "middleware" => ["authMiddleware", "premiumAccessMiddleware"]],
    ["method" => "GET", "uri" => "/deck/card/delete", "controller" => "deckController", "function" => "handleRemoveCard", "middleware" => ["authMiddleware", "premiumAccessMiddleware"]]
];
