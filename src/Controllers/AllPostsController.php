<?php

    namespace App\Controllers;

    use App\Models\Factory\ModelFactory;
    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;

    /**
     * Class AllPostsController
     * Page avec touts les posts.
     * @package App\Controller
     */
    class AllPostsController extends MainController
    {
        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function defaultMethod()
        {
            $allPosts = (ModelFactory::getModel('Posts')->listData());

            return $this->render('allposts.twig', ['posts' => $allPosts]);
        }

    }