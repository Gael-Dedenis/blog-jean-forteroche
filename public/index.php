<?php

    use App\Router;
    use Tracy\Debugger;

    // Appel de l'autoload
    require_once "../vendor/autoload.php";
    require_once "../config/config_dev.php";

    // Détermine si une session existe, si 'non' en créer une.
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    Debugger::enable();

    /* Création du routeur */
    $router = new Router();
    $router->run();
