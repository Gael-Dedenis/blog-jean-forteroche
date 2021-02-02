<?php

    namespace App\Controllers;

    use App\Models\Factory\ModelFactory;
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
         * @var mixed|null
         */
        private $user = null;

        /**
         * Formulaire de connection.
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
        */
        public function defaultMethod() {

            if (!empty($this->post['email']) && !empty($this->post['pass'])) {
                $this->checkLogUser();
            }

            return $this->render('connexion.twig');
        }

        /**
         * Action: méthode vérifiant les logins de l'utilisateur grâce à son email et mdp.
         * @param string $email
         * @param string $pass
         * @return array
        */
        private function checkLogUser() {

            $user = ModelFactory::getModel('Users')->readData($this->post['email'], 'email');

            if(password_verify($this->post['pass'], $user['pass'])) {

                $this->setSession(
                    $user['id'],
                    $user['pseudo'],
                    $user['email'],
                    $user['status']
                );

                $this->redirect('home');
            }

        }

        /**
         * Action: remplit la session avec les données de l'utilisateur.
         * @param int $id
         * @param string $pseudo
         * @param string $email
         * @param string $status
         */
        private function setSession(int $id, string $pseudo, string $email, string $status) {
            $_SESSION['user'] = [
                'id'     => $id,
                'pseudo' => $pseudo,
                'email'  => $email,
                'status' => $status
            ];
        }

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
        */

        public function createMethod() {
            $validation = [];

            if(!empty($this->post)) {
                $validation = $this->setCheckForms();
            }

            if(!$validation) {
                $erreurs = isset($this->session["erreurs"]) ? $this->session["erreurs"] : NULL;
                // si $this->session["erreurs"] existe $erreurs vaudra $this->session["erreurs"] sinon vaudra "NULL"

                return $this->render("create_account.twig", ["erreurs" => $erreurs]);
            }

            return $this->render("create_account.twig");
        }

        /**
         * @return bool
         */
        private function setCheckForms() {

            $this->checkForms();

            if(empty($this->session["erreurs"]) || $this->session == null ) {
                $this->session["erreurs"] = [];
                $this->setDataUser();
            } 

            return false;
        }

        /**
         * Récupération des données du nouvel utilisateur. Puis redirection sur le formulaire de connection.
         */
        private function setDataUser() {
                $this->user["pseudo"] = $this->post["pseudo"];
                $this->user["email"]  =  $this->post["email"];
                $this->user["pass"]   = password_hash( $this->post["pass"], PASSWORD_DEFAULT);
                $this->user["status"] = 1;

                ModelFactory::getModel("Users")->createData($this->user);

                $this->redirect("user");
        }

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function logoutMethod() {
            $this->unsetSession();
            $this->redirect('user');
        }

        /**
         * @return void
         */
        private function unsetSession() {
            $_SESSION['user'] = [];
        }

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
        */
        public function modifyMethod() {
            $newData    = [];
            $erreurs    = [];
            $validation = null;

            if(!empty($this->post)) {
                $validation = $this->setCheckChange();
            }

            if(!$validation) {

                $erreurs = isset($this->session["erreurs"]) ? $this->session["erreurs"] : NULL;
                // si $this->session["erreurs"] existe $erreurs vaudra $this->session["erreurs"] sinon vaudra "NULL"

                return $this->render("account.twig", ["erreurs" => $erreurs]);

            } elseif ($validation) {
                if(!$this->post["email"] === null || !$this->post["email"] === "" || !empty($this->post["email"])) {

                    $newData["email"] = $this->post["email"];

                    $_SESSION["user"]["email"] = $this->post["email"];

                } elseif(!$this->post["pass"] === null || !$this->post["pass"] === "" || !empty($this->post["pass"])) {

                    $newData["pass"] = password_hash( $this->post["pass"], PASSWORD_DEFAULT);

                }

                 ModelFactory::getModel("Users")->updateData($this->session["user"]["id"], $newData);

                 $this->redirect("user!modify");
            }

            $_Session["erreurs"] = [];
            return $this->render("account.twig");
        }

        /**
         * Vérification des données reçues par les formulaires pour modifier les données utilisateurs.
         * @return bool
         */
        private function setCheckChange() {
            if(!empty($this->post["email"])) {
                $this->checkForms($type = "email");

                if(empty($this->session["erreurs"]["email"])) {
                    return true;
                }
                return false;
            }
            if(!empty($this->post["pass"])) {
                $this->checkForms($type = "password");

                if(empty($this->session["erreurs"]["pass"])) {
                    return true;
                }
                return false;
            }
        }

         /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function deleteMethod()
        {
            $getCommentsUser = ModelFactory::getModel("Comments")->listData($this->session["user"]["id"], "user_id");

             if(!empty($getCommentsUser)) {
                ModelFactory::getModel("Comments")->deleteData($this->session["user"]["id"], "user_id");
            }

            ModelFactory::getModel("Users")->deleteData($this->session["user"]["id"], "id");

            $this->logoutMethod();
        }
    }