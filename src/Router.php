<?php

    namespace App;

    require_once "../config/config_dev.php";

    use App\Controllers\ExtensionFeaturesTwig;
    use Twig\Environment;
    use Twig\Loader\FilesystemLoader;
    use Twig\Extension\DebugExtension;
    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;

    /**
     * Classe Router
     * @package App
     */
    class Router
    {
        /**
         * @var Environment
         */
        private $twig = null;

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
        public function __construct() {
            $this->setEnvironment();
            $this->parseUrl();
            $this->setController();
            $this->setMethod();
        }

        /**
         * Mise en place environement Twig.
         * Ajout de la global Session aux Vues Twig.
         * Ajouts de fonctionnalités pour les Vues Twig.
         * @return mixed|void
         */
        private function setEnvironment() {
            $this->twig = new Environment(new FilesystemLoader("../src/Views"), array(
                "cache" => false,
                "debug" => true
            ));
            $this->twig->addGlobal("session", $_SESSION);
            $this->twig->addExtension(new \Twig\Extension\DebugExtension());
            $this->twig->addExtension(new ExtensionFeaturesTwig());
        }

        /**
         * Analyse l'URL pour prendre le bon controleur et ses méthodes.
         * @return mixed|void
         */
        public function parseUrl() {
            $access = filter_input(INPUT_GET, "access");

            if (!isset($access)) {
                $access = "home";
            }

            $access           = explode("!", $access);
            $this->controller = $access[0];
            $this->method     = count($access) == 1 ? "default" : $access[1];
        }

        /**
         * Créer les requêtes controller.
         * @return mixed|void
         */
        public function setController() {
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
        public function setMethod() {
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
        public function run() {
            $this->controller = new $this->controller($this->twig);
            $response         = call_user_func([$this->controller, $this->method]);

            echo filter_var($response);
        }
    }
