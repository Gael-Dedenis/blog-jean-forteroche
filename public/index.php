<?php

    use App\Router;
    use Tracy\Debugger;

    /* Appel de l'autoload */
    require "../vendor/autoload.php";

    session_start();

    Debugger::enable();

    /* Création du routeur */
    $router = new Router();
    $router->run();
