<?php

    namespace App\Controllers;

    use Twig\Environment;
    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;

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
         * @var mixed
         */
        protected $get = null;

        /**
         * @var mixed
         */
        protected $post = null;

        /**
         * @var array
         */
        protected $allValues = [];

        /**
         * @var mixed|null
         */
        protected $session = [];

        /**
         * constructor MainController
         */
        public function __construct(Environment $twig) {
            $this->twig = $twig;

            $this->post    = filter_input_array(INPUT_POST);
            $this->get     = filter_input_array(INPUT_GET);
            $this->session = filter_var_array($_SESSION);
        }

        /**
         * Retourne l'url construite de la page.
         * @param string $page
         * @param array $params
         * @return string
         */
        public function url(string $page, array $params = []) {
            $params["access"] = $page;
            return "index.php?" . http_build_query($params);
        }

        /**
         * Retourne l'url de redirection.
         * @param string $page
         * @param array $params
         */
        public function redirect(string $page, array $params = []) {
            header("Location: " . $this->url($page, $params));
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
        public function render(string $views, array $params = []) {
            return $this->twig->render($views, $params);
        }

    }