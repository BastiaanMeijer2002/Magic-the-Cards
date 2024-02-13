<?php

use Controller\AdminDashboardController;
use Controller\EnrollController;
use Controller\ExamController;
use Controller\GradeController;
use Controller\HomeController;
use Controller\KaasController;
use Controller\LoginController;
use Controller\RegisterController;
use Controller\StudentDashboardController;
use Controller\TeacherDashboardController;
use Controller\TestController;
use Middleware\EnrollPermissionMiddleware;
use Middleware\ExamPermissionMiddleware;
use Middleware\StudentAccessMiddleware;
use Middleware\TeacherAccessMiddleware;

$routes = [
    ["method" => "GET", "uri" => "/", "controller" => TestController::class, "function" => "index", "middleware" => []],
];
