<?php

    namespace App\Controller;

    use App\Model\Factory\ModelFactory;
    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;

    /**
     * Classe ConnexionController
     * @package App\Controller
     */
    class ConnexionController extends MainController
    {
        /**
         * @var
         */
        private $user = [];

        /**
         * @var
         */
        private $isConnected = "";

        /**
         * 
         */
        public function defaultMethod()
        {
            if (!empty($this->$_POST('email')) and !empty($this->$_POST('pass')))
            {
                $this->user = ModelFactory::getModel("Users")->readData($this->$_POST("email"), "email");
            }

        }

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function logoutMethod()
        {
            $this->sessionDestroy();
            $this->redirect('home');
        }

    }