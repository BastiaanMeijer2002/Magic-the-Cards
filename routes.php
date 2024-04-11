<?php

$routes = [
    ["method" => "GET", "uri" => "/", "controller" => "homeController", "function" => "index", "middleware" => []],
    ["method" => "GET", "uri" => "/register", "controller" => "registerController", "function" => "index", "middleware" => []],
    ["method" => "POST", "uri" => "/register", "controller" => "registerController", "function" => "registerUser", "middleware" => []],
    ["method" => "GET", "uri" => "/login", "controller" => "loginController", "function" => "index", "middleware" => []],
    ["method" => "POST", "uri" => "/login", "controller" => "loginController", "function" => "loginUser", "middleware" => []]
];
