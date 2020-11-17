<?php

    namespace App\Controllers;

    use App\Models\Factory\ModelFactory;
    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;

    /**
     * Class PostsController
     * Page avec touts les posts.
     * @package App\Controller
     */
    class PostsController extends MainController
    {
        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function defaultMethod()
        {
            $allPosts = array(ModelFactory::getModel('Posts')->listData());

            return $this->render('home.twig', ['posts' => $allPosts]);
        }

    }