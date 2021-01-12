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
         * Formulaire de connection.
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
        */
        public function defaultMethod() {
            echo '<pre> '. var_dump($_POST) .' </pre>';

            if (isset($_POST['email']) && isset($_POST['pass'])) {
                $this->checkLogUser($this->post['email'], $this->post['pass']);
            }

            echo 'fail logUser <br>';

            return $this->render('connexion.twig');
        }

        /**
         * Action: méthode vérifiant les logins de l'utilisateur grâce à son email et mdp.
         * @param string $email
         * @param string $pass
         * @return array
        */
        private function checkLogUser($mail, $pass) {

            echo 'coucou avant de récup donnée utilisateur';

            $this->user = ModelFactory::getModel('Users')->readData($email , 'email');

            if (password_verify($pass, $this->user["pass"])) {
                $this->setSession(
                    $this->user['id'],
                    $this->user['pseudo'],
                    $this->user['email'],
                    $this->user['pass'],
                    $this->user['status']
                );
            }

            echo 'coucou session créer !';
            //$this->redirect('home');
        }

        /**
         * Action: remplit la session avec les données de l'utilisateur.
         * @param int $id
         * @param string $pseudo
         * @param string $email
         * @param string $pass
         * @param string $status
         */
        private function setSession(int $id, string $pseudo, string $email, string $pass, string $status) {
            $_SESSION['user'] = [
                'id'     => $id,
                'pseudo' => $pseudo,
                'email'  => $email,
                'pass'   => $pass,
                'status' => $status
            ];
        }

    }