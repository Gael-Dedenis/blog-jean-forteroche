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
            $_POST["email"] = "gaeldedenis01@laposte.net";
            $_POST["pass"]  = "1234";
            $_SESSION["user"] = [
                "id"     => "0",
                "pseudo" => "Admin",
                "email"  => "gaeldedenis01@laposte.net",
                "pass"   => "1234",
                "status" => "2"
            ];

            echo '<pre> '. var_dump($_POST) .' </pre>'; // Trouve bien Mail et Pass
            echo '<pre> '. var_dump($this->post) .' </pre>'; // = NULL
            echo '<pre> '. var_dump($this->allValues) .' </pre>'; // filtre GET = ok, POST = nok
            echo '<pre> '. var_dump($_SESSION) .' </pre>'; // Trouve bien la Session
            echo '<pre> '. var_dump($this->session) .' </pre>'; // Trouve bien la Session

         /* if (isset($_POST['email']) && isset($_POST['pass'])) {
                $this->checkLogUser($this->post['email'], $this->post['pass']);
            } */

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