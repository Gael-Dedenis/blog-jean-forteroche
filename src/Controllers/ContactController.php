<?php

    namespace App\Controllers;

    use App\Models\Factory\ModelFactory;
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
            return $this->render("contact.twig");
        }

        /**
         * 
         */
        public function sendMessage()
        {
            // Création du transport
            $transport = (new Swift_SmtpTransport())
                ->setHost(smtp.laposte.net,25)
                ->setUsername(MAIL_TO)
                ->setPassword(MAIL_PASS)
                ;

            // Création du mailer utilisant le transport
            $mailer = new Swift_Mailer($transport);

            // Création du message
            $message = (new Swift_Message())
                ->setSubject($this->post["subject"])
                ->setFrom([$this->post["email"] => $this->post["pseudo"]])
                ->setTo([MAIL_TO => ])
                ->setBody($this-post["message"])
                ;

            // envoie du mail
            return $mailer->send($message);
        }
    }