<?php

    namespace App\Controllers;

    use App\Model\Factory\ModelFactory;
    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;

    /**
     * Classe UserController
     * @package App\Controller
     */
    class UserController extends MainController
    {
        /**
         * @var array
         */
        private $user = [];

        /**
         * @var array
         */
        private $newData = [];

        /**
         * @data mixed|null
         */
        private $session = null;

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function defaultMethod()
        {
            if (empty($this->post["email"]) && empty($this->post["pass"]))
            {
                return $this->render("connexion.twig");
            }

            $this->user = ModelFactory::getModel("User")->readData($this->post["email"], "email");

            // si le mdp correspond on créer une session.
            if (password_verify($this->post["pass"], $this->user["pass"]))
            {
                $this->sessionMethod();

                $this->redirect("home");
            }
        }

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
        */
        public function createMethod()
        {
            $this->user["pseudo"] = $this->post["pseudo"];
            $this->user["email"]  = $this->post["email"];
            $this->user["pass"]   = password_hash($this->post["pass"], PASSWORD_DEFAULT);
            $this->user["admin"]  = 0;

            ModelFactory::getModel("User")->createData($this->user);

            $this->redirect("connexion");
        }

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
        */
        public function updateUserMethod()
        {
            if (!empty($this->post["newPass"] || !empty($this->post["newEmail"])))
            {
                $this->newData = 
                    [ "newPass"  => $this->post["newPass"],
                      "newEmail" => $this->post["newEmail"] ];

                ModelFactory::getModel("User")->updateData($this->post["newPass"], $this->session["id"]);

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
        $this->sessiondestroyMethod();
        $this->redirect('home');
        }

        /**
         * Création de la session avec les données de l'utilisateur
         * @param array userData
         */
        private function sessioncreate()
        {
            $this->session['user_data'] = [
                'id'     => $this->user["id"],
                'pseudo' => $this->user["pseudo"],
                'email'  => $this->user["email"],
                'pass'   => $this->user["password"],
                'admin'  => $this->user["admin"]
            ];
        }

        /**
         * @return void
         */
        private function sessiondestroy()
        {
            $_SESSION['users'] = [];
        }

    }