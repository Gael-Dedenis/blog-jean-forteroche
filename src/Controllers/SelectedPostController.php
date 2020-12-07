<?php

    namespace App\Controller;

    use App\Model\Factory\ModelFactory;

    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;

    /**
     * Classe SelectedPostController
     * @package App\Controller
     */
    class SelectedPostController extends MainController
    {
        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function defaultMethod()
        {
            $posts = ModelFactory::getModel('Posts')->listData();
            $comments = ModelFactory::getModel('Comments')->listData();

            return $this->render("selectedpost.twig", [
                'posts' => $posts,
                'comments' => $comments
            ]);
        }

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
        */
        public function readMethod()
        {
            $posts = ModelFactory::getModel('Posts')->readData($this->get['id']);
            $comments = ModelFactory::getModel('Comments')->listData($this->get['id'], 'post_id');

            return $this->render('selectedpost.twig', [
                'post' => $posts,
                'comments' => $comments
            ]);
        }
    }