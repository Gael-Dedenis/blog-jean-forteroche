<?php

    namespace App;

    require_once "../config/config_dev.php";

    /**
     * Classe Router
     * @package App
     */
    class Router
    {
        /**
         * Controller requis.
         * @var string
         */
        private $controller = DEFAULT_CONTROLLER ;

        /**
         * Method requise.
         * @var string
         */
        private $method = DEFAULT_METHOD ;

        /**
         * Router constructor
         * Mets en place l'environement, puis analyse l'URL, ensuite mets en place les controllers et les méthodes.
         */
        public function __construct()
        {
            $this->parseUrl();
            $this->setController();
            $this->setMethod();
        }

        /**
         * Analyse l'URL pour prendre le bon controleur et ses méthodes.
         * @return mixed|void
         */
        public function parseUrl()
        {
            $access = filter_input(INPUT_GET, "access");

            if (!isset($access)) {
                $access = "home";
            }

            $access           = explode("!", $access);
            $this->controller = $access[0];
            $this->method     = count($access) == 1 ? "launch" : $access[1];
        }

        /**
         * Créer les requêtes controller.
         * @return mixed|void
         */
        public function setController()
        {
            $this->controller = ucfirst(\strtolower($this->controller)) . "controller";
            $this->controller = DEFAULT_PATH . $this->controller;

            if (!class_exists($this->controller)) {
                $this->controller = DEFAULT_PATH . DEFAULT_CONTROLLER;
            }
        }

        /**
         * Créer les requêtes pour les méthodes.
         * @return mixed|void
         */
        public function setMethod()
        {
            $this->method = strtolower($this->method) . "Method";

            if (!method_exists($this->controller, $this->method))
            {
                $this->method = DEFAULT_METHOD;
            }
        }

        /**
         * Créer l'objet Controller et appel 
         * @return mixed|void
         */
        public function run()
        {
            $this->controller = new $this->controller();
            $response         = call_user_func([$this->controller, $this->method]);

            echo filter_var($response);
        }
    }
