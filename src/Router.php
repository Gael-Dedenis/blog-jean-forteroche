<?php

    namespace App\Controllers;

    require_once "../config/config_dev.php";

    use Twig\Environment;
    use Twig\Extension\DebugExtension;
    use Twig\Loader\FilesystemLoader;

    /**
     * Classe Router
     * @package App
     */
    class Router
    {

        public function __construct()
        {
            $this->setEnvironment();
            $this->setHome();
        }

        /**
         * Mise en place environement Twig
         * @return mixed|void
         */
        public function setEnvironment()
        {
            $this->twig = new Environment(new FilesystemLoader("../src/View"), array(
                "cache" => false,
                "debug" => true
            ));

            $this->twig->addExtension(new DebugExtension());
            $this-$twig->addFilter(FILTER_NL2BR);
        }

        /**
         * Redirection sur la page d'accueil
         */
        public function setHome()
        {
            
        }
    }
