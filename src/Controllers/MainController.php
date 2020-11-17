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
    abstract class MainController
    {
        /**
         * @var Environment
         */
        protected $twig = null;

        /**
         * constructor MainController
         * @param Environment $twig
         */
        public function __construct()
        {
            $this->setEnvironment();
        }

        /**
         * Mise en place environement Twig
         * @return mixed|void
         */
        public function setEnvironment()
        {
            $this->twig = new Environment(new FilesystemLoader("../src/Views"), array(
                "cache" => false,
                "debug" => true
            ));
        }

        /**
         * @param string $page
         * @param array $params
         * @return string
         */
        public function url(string $page, array $params = [])
        {
            $params['access'] = $page;
            return 'index.php?' . http_build_query($params);
        }

        /**
         * @param string $page
         * @param array $params
         */
        public function redirect(string $page, array $params = [])
        {
            header('Location: ' . $this->url($page, $params));
            exit;
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