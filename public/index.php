<?php

    use App\Router;
    use Tracy\Debugger;

    /* Appel de l'autoload */
    require_once "../vendor/autoload.php";

    /* Création du routeur */
    $router = new Router();
    $router->run();

    Debugger::enable();
