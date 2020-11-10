<?php

    namespace App\Controllers;

    use App\Models\Factory\ModelFactory;

    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;

    /**
     * Classe HomeController
     * @package App\Controllers
     */
    class HomeController extends MainController
    {
        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function launchMethod()
        {
            $lastPost = array_reverse(ModelFactory::getModel('Posts')->listData());

            return $this->render('home.twig', ['posts' => $lastPost]);
        }
    }