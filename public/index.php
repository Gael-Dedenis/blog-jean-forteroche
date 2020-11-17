<?php

    use App\Router;
    use Tracy\Debugger;

    /* Appel de l'autoload */
    require_once "../vendor/autoload.php";

    require_once "../config/config_dev.php";
    Debugger::enable();

    /* Création du routeur */
    $router = new Router();
    $router->run();
