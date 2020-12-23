<?php

    namespace App\Controllers;

    use Twig\Environment;
    use Twig\Loader\FilesystemLoader;
    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;

    require_once "../config/config_dev.php";

    /**
     * Classe MainController
     * @package App\Controller
     */
    abstract class MainController extends GlobalController
    {

        /**
         * @var Environment
         */
        protected $twig = null;

        /**
         * constructor MainController
         * @param 
         * @param Environment $twign
         */
        public function __construct()
        {
            parent::__construct();

            $this->setEnvironment();
        }

        /**
         * Mise en place environement Twig.
         * Ajout de la global Session aux Vues Twig.
         * Ajouts de fonctionnalités pour les Vues Twig.
         * @return mixed|void
         */
        public function setEnvironment()
        {
            $this->twig = new Environment(new FilesystemLoader("../src/Views"), array(
                "cache" => false,
                "debug" => true
            ));
            $this->twig->addGlobal("_session", $_SESSION);
            $this->twig->addExtension(new \Twig\Extension\DebugExtension());
            $this->twig->addExtension(new ExtensionFeaturesTwig());
        }

        /**
         * Retourne l'url construite de la page.
         * @param string $page
         * @param array $params
         * @return string
         */
        public function url(string $page, array $params = [])
        {
            $params["access"] = $page;
            return "index.php?" . http_build_query($params);
        }

        /**
         * Retourne l'url de redirection.
         * @param string $page
         * @param array $params
         */
        public function redirect(string $page, array $params = [])
        {
            header("Location: " . $this->url($page, $params));
            die;
        }

        /**
         * Rafraichit la page après l'ajout d'un commentaire.
         * @param string $value
         * @param string $params
         */
        public function refresh(string $value, string $controller, array $params = [])
        {
            header("Location: index.php?id=" . $value . "&access=" . $controller . $params);
            die;
        }

        /**
         * Création de la vue.
         * @param string $view
         * @param array $params
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function render(string $views, array $params = [])
        {
            return $this->twig->render($views, $params);
        }

    }