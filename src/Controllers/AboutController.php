<?php

    namespace App\Controllers;

    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;

    /**
     * Class AboutController
     * @package App\Controllers
     */
    class AboutController extends MainController
    {
        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function defaultMethod() {
            return $this->render("aboutme.twig");
        }
    }