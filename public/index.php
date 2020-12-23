<?php

    use App\Router;
    use Tracy\Debugger;

    /* Appel de l'autoload */
    require "../vendor/autoload.php";

    Debugger::enable();

    /* Création du routeur */
    $router = new Router();
    $router->run();
