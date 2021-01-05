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
        private $user_data = [];

        /**
         * Action: Si les champs pour se log ne sont pas vide, on vérifie le mdp et on créer une session.
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function defaultMethod()
        {
            if (empty($this->post["email"]) || empty($this->post["pass"]))
            {
                return $this->render("connexion.twig");
            }

            $this->checkLogUser();
            $this->redirect("home");
        }

        /**
         * Action: vérification du login
         */
        private function checkLogUser () {
            $this->user_data = ModelFactory::getModel("User")->readData($this->post["email"], "email");

            if (password_verify($this->post["pass"], $user_data["pass"])) {
                $this->setSession(
                    $this->user_data["id"],
                    $this->user_data["pseudo"],
                    $this->user_data["email"],
                    $this->user_data["pass"],
                    $this->user_data["status"]
                );
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
            if (!empty($this->post))
            {
                $this->setDataUser();
                $this->redirect("home");
            }
             return $this->render("create_account.twig");
        }

        /**
         * 
         */
        private function setDataUser()
        {
                $this->user["pseudo"] = $this->post["pseudo"];
                $this->user["email"]  = $this->post["email"];
                $this->user["pass"]   = password_hash($this->post["pass"], PASSWORD_DEFAULT);
                $this->user["status"] = 1;

                ModelFactory::getModel("User")->createData($this->user);
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
        private function setSession(int $id, string $pseudo, string $email, string $pass, string $status)
        {
            $_SESSION['user'] = [
                'id'     => $id,
                'pseudo' => $pseudo,
                'email'  => $email,
                'pass'   => $pass,
                'status' => $status
            ];
        }

        /**
         * @return void
         */
        private function sessiondestroy()
        {
            $_SESSION['user'] = [];
        }

    }