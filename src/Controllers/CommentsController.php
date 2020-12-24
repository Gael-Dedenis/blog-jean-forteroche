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
            $this->comments["chapter"]  = ModelFactory::getModel("Posts")->readData($this->get["id"]);
            $this->comments["comments"] = ModelFactory::getModel("Comments")->listData($this->get["id"],"post_id");

            return $this->render("selectedpost", ["Comments" => $this->comments]);
        }

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function CreateMethod()
        {
            $this->comments["author"]  = $this->session["user_data"["pseudo"]];
            $this->comments["content"] = $this->post["comment_content"];
            $this->comments["post_id"] = $this->get["id"];
            $this->comments["user_id"] = $this->session["user_data"["id"]];

            if (empty($this->comments["content"]))
            {
                $this->redirect("selectedpost");
            }
            ModelFactory::getModel("Comments")->createData($this->comments);

            $this->refresh($this->comments["post_id"], $posts, "!read");
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
            ModelFactory::getModel('Comments')->updateData($this->get["id"], ['reported' => 1]);

            $selectedChapter = $this->get["chapter_id"];

            $this->refreshChapter($selectedChapter, "!read");
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

            $this->redirect("administration", ["reported" => $this->comments]);
        }
    }