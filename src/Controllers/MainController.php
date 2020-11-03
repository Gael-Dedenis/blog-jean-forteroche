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
         * constructor MainController
         * @param Environment $twig
         */
        public function __construct(Environment $twig)
        {
            parent::__construct();

            $this->twig = $twig;
            $twig->addGlobal("session", $_SESSION);
            $this->twig->addFilter( new \Twig\twigFilter(FILTER_NL2BR));
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
        public function render(string $view, array $params = [])
        {
            return $this->twig->render($view, $params);
        }
    }