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
         * @data mixed
         */
        protected $get = null;

        /**
         * @data mixed
         */
        protected $post = null;

        /**
         * @data mixed|null
         */
        private $session = null;

        /**
         * @data mixed
         */
        private $user = null;

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
            $this->accessGlobal();
            $this->setEnvironment();
        }

        /**
         * Mise en place environement Twig.
         * Ajouts de fonctionnalités pour les Vues Twig.
         * @return mixed|void
         */
        public function setEnvironment()
        {
            $this->twig = new Environment(new FilesystemLoader("../src/Views"), array(
                "cache" => false,
                "debug" => true
            ));
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
            $params['access'] = $page;
            return 'index.php?' . http_build_query($params);
        }

        /**
         * Retourne l'url de redirection.
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

        /**
         * @return mixed|void
         */
        public function accessGlobal() {
            $this->get     = filter_input_array(INPUT_GET);
            $this->post    = filter_input_array(INPUT_POST);

            $this->session = filter_var_array($_SESSION);
            if (isset($this->session['user_data']))
            {
                $this->user = $this->session['user_data'];
            }
        }
    }