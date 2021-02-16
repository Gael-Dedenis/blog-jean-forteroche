<?php

    namespace App\Controllers;

    use App\Models\Factory\ModelFactory;
    use Twig\Environment;
    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;

    /**
     * Class MainController
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
            $params['access'] = $page;
            return 'index.php?' . http_build_query($params);
        }

        /**
         * Retourne l'url de redirection.
         * @param string $page
         * @param array $params
         */
        public function redirect(string $page, array $params = []) {
            header('Location: ' . $this->url($page, $params));
            exit;
        }

        /**
         * Rafraîchit la page.
         * @param string $page
         * @return string;
         */
        public function refreshComments(string $chapter) {
            header('Location: index.php?id=' . $chapter . '&access=posts!read');
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

        /**
         * @param string $type type de valeur à check
         */
        public function checkForms(string $type = null) {
            switch($type) {
                case 'pseudo':
                    $this->checkPseudo();
                    break;

                case 'email':
                    $this->checkEmail();
                    break;

                case 'password':
                    $this->checkPass();
                    break;

                default:
                    $this->checkPseudo();
                    $this->checkEmail();
                    $this->checkPass();
            }
        }

        /**
         * @return array
         */
        private function checkPseudo() {
            if(empty($this->post['pseudo']) || !preg_match('/^[a-zA-Z0-9_]+$/', $this->post['pseudo'])) {
                $this->session['erreurs']['pseudo'] = 'Votre pseudo doit contenir que des caractères alphanumériques et underscores !';
                return $this->session['erreurs'];
            } else {
                $checkPseudo = ModelFactory::getModel('Users')->readData($this->post['pseudo'], 'pseudo');

                if($checkPseudo) {
                    $this->session['erreurs']['pseudo'] = 'Ce pseudo est déjà pris !';
                    return $this->session['erreurs'];
                }
            }
        }

        /**
         * @return array
         */
        private function checkEmail() {
            if(empty($this->post['email']) || !filter_var($this->post['email'], FILTER_VALIDATE_EMAIL)) {
                $this->session['erreurs']['email'] = 'Email invalide !';
                return $this->session['erreurs'];
            } else {
                $checkEmail = ModelFactory::getModel('Users')->readData($this->post['email'], 'email');
                if($checkEmail) {
                    $this->session['erreurs']['email'] = 'Cet email est déjà utilisé pour un autre compte !';
                    return $this->session['erreurs'];
                }
            }
        }

        /**
         * @return array
         */
        private function checkPass() {
            if(empty($this->post['pass']) || empty($this->post['pass_confirm']) || $this->post['pass'] !== $this->post['pass_confirm']) {
                $this->session['erreurs']['pass'] = 'Vous devez remplir un mot de passe valide et le confirmé !';
                return $this->session['erreurs'];
            }
        }

    }