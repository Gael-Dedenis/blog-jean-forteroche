<?php

    namespace App\Controllers;

    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;

    /**
     * Class AboutMeController
     * appel la page Qui suis-je ?
     * @package App\Controller
     */
    class AboutMeController extends MainController
    {
        /**
         * Rendu de la vue Qui suis-je
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function defaultMethod()
        {
            return $this->render('aboutme.twig');
        }
    }