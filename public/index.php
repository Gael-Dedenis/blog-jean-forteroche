<?php

    use App\Router;
    use Tracy\Debugger; // A commenter pour la version online/prod

    // Appel de l'autoload
    require_once "../vendor/autoload.php";
    require_once "../config/config.php";

    // Détermine si une session existe, si 'non' en créer une.
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    Debugger::enable(); // A commenter pour la version online/prod

    // Création du routeur
    $router = new Router();

    // Execution de l'application
    $router->run();
