<?php

    namespace App\Controllers;

    use App\Models\Factory\ModelFactory;
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
         * @var
        */
        protected $post_id;

        /**
         * @var
         */
        protected $posts;

        /**
         * @var
         */
        protected $comments;

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function defaultMethod()
        {
            $this->post_id = $_GET['id'];

            $this->getData();

            return $this->render("selectedpost.twig", [
                'posts'    => $posts,
                'comments' => $comments
            ]);
        }

        public function getData()
        {
            $this->posts    = ModelFactory::getModel('Posts')->listData();
            $this->posts    = ModelFactory::getModel('Posts')->readData($this->post_id);

            $this->comments = ModelFactory::getModel('Comments')->listData();
        }

    }