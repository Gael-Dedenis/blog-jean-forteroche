<?php

    namespace App\Controllers;

    use App\Models\Factory\ModelFactory;
    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;
    
    /**
     * Classe CommentsController
     * @package App\Controllers
     */
    class CommentsController extends MainController
    {
        /**
         * @var array
         */
        private $comments = [];

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function defaultMethod()
        {
            $this->comments = ModelFactory::getModel("Comments")->listData($this->get["id"],"post_id");

            return $this->redirect("selectedpost", ["Comments" => $this->comments]);
        }

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function deleteMethod()
        {
            ModelFactory::getModel('Comments')->deleteData($this->get['id']);

            $this->redirect('administration');
        }

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function reportMethod()
        {
            $comment_id = $this->get["id"];

            ModelFactory::getModel('Comments')->updateData($comment_id, ['reported' => 1]);

            $this->redirect("selectedpost");
        }

        /**
         * @return array
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError 
         */
        public function getreportedMethod()
        {
            $this->comments = ModelFactory::getModel("Comments")->readData(["reported" => 1]);

            return $this->redirect("administration", ["reported" => $this->comments]);
        }
    }