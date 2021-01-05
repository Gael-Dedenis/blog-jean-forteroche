<?php

    namespace App\Controllers;

    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;

    /**
     * Class ContactController
     * @package App\Controllers
     */
    class ContactController extends MainController
    {
        /**
         * @var
         */
        private $mail = [];

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function defaultMethod()
        {
            if (empty($this->post["message"]))
            {
                return $this->render("contact.twig");
            } else {
                $this->sendMethod();

                $this->redirect("contact");
            }

        }

        /**
         * méthode pour l'envoie d'un mail par swiftmailer
         * @return mixed
         */
        private function sendMethod()
        {
            // Création du transport
            $transport = (new Swift_SmtpTransport())
                ->setHost(MAIL_HOST)
                ->setPort(MAIL_PORT)
                ->setUsername(MAIL_FROM)
                ->setPassword(MAIL_PASS)
                ;

            // Création du mailer utilisant le transport
            $mailer = new Swift_Mailer($transport);

            // Création du message
            $message = (new Swift_Message())
                ->setSubject($this->post["subject"])
                ->setFrom([MAIL_FROM => "Blog JeanForteroche"])
                ->setTo([MAIL_TO, $this->post["email"] => $this->post["pseudo"]])
                ->setBody($this->post["message"])
                ;

            // envoie du mail
            return $mailer->send($message);
        }
    }