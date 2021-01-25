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
         * @var mixed
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

            $user = [];
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
            if(!empty($this->post)) {
                $this->checkForms();
            }

            return $this->render("create_account.twig");
            }

        /**
         * @return bool
         */
        private function checkForms() {
            $errors = [];

            if(empty($this->post["pseudo"]) || !preg_match('/^[a-zA-Z0-9_]+$/', $this->post["pseudo"])) {
                $errors["pseudo"] = "Votre pseudo doit contenir que des caractères alphanumériques et underscores !";
            } else {
                $checkPseudo = ModelFactory::getModel("Users")->readData($this->post["pseudo"], "pseudo");

                if($checkPseudo) {
                    $errors["pseudo"] = "Ce pseudo est déjà pris !";
                }
            }

            if(empty($this->post["email"]) || !filter_var($this->post["email"], FILTER_VALIDATE_EMAIL)) {
                $errors["email"] = "Email invalide !";
            } else {
                $checkEmail = ModelFactory::getModel("Users")->readData($this->post["email"], "email");

                if($checkEmail) {
                    $errors["email"] = "Cet email est déjà utilisé pour un autre compte !";
                }
            }

            if(empty($this->post["pass"]) || empty($this->post["pass_confirm"]) || $this->post["pass"] !== $this->post["pass_confirm"]) {
                $errors["pass"] = "Vous devez remplir un mot de passe valide et le confirmé !";
            }

            if(empty($errors)) {
                $this->setDataUser();
            } else {
                $this->user["dataErrors"] = $errors;
                return $this->render("create_account.twig", ["errors" => $this->user]);
            }
        }

        /**
         * Récupération des données du nouvel utilisateur. Puis redirection sur le formulaire de connection.
         */
        private function setDataUser() {
                $this->user["pseudo"] = $_POST["pseudo"];
                $this->user["email"]  =  $_POST["email"];
                $this->user["pass"]   = password_hash( $_POST["pass"], PASSWORD_DEFAULT);
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

    }