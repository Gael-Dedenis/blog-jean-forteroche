<?php

    namespace App\Controllers;

    use App\Models\Factory\ModelFactory;
    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;
    
    /**
     * Class CommentsController
     * @package App\Controllers
     */
    class CommentsController extends MainController
    {
        /**
         * @var array
         */
        private $comments = [];

        /**
         * @var mixed
         */
        private $chapter = null;

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
        public function createMethod()
        {
            if (!empty($this->post)) {
                $this->setNewComment();
            }

            $this->chapter = $this->get["id"];

            $this->refreshComments($this->chapter);
        }

        /**
         * Créer le nouveau commentaire.
         */
        private function setNewComment() {
            $this->comments["content"]      = addslashes($this->post["comment"]);
            $this->comments["created_date"] = date("Y-m-d H-i-s");
            $this->comments["post_id"]      = $this->get["id"];
            $this->comments["user_id"]      = $this->session["user"]["id"];

            ModelFactory::getModel("Comments")->createData([
                "content"      => $this->comments["content"],
                "created_date" => $this->comments["created_date"],
                "post_id"      => $this->comments["post_id"],
                "user_id"      => $this->comments["user_id"]
            ]);
        }

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function deleteMethod()
        {
            if (!empty($this->get["id"])) {
                ModelFactory::getModel("Comments")->deleteData($this->get["id"]);
                $this->chapter = $this->get["chapter"];
                $this->refreshComments($this->chapter);
            }
            $this->redirect("home");
        }

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function reportMethod()
        {
            if (!empty($this->get["id"])) {
                ModelFactory::getModel("Comments")->updateData($this->get["id"], ["reported" => 1]);
                $this->chapter = $this->get["chapter"];
                $this->refreshComments($this->chapter);
            }
            $this->redirect("home");
        }

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function adminMethod() {
            if($this->checkAdmin()) {
            $this->comments["reported"]  = ModelFactory::getModel("Comments")->listData("1", "reported");
            $this->comments["authors"]   = ModelFactory::getModel("Users")->listData();

            return $this->render("backend/admin_reported.twig", ["comments" => $this->comments]);
            }
            $this->redirect("user");

        }

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function unreportMethod() {
            if($this->checkAdmin()) {
                ModelFactory::getModel("Comments")->updateData($this->get["id"], ["reported" => 0]);
                $this->redirect("comments!admin");
            }
            $this->redirect("user");
            }

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function cleanMethod() {
            if($this->checkAdmin()) {
                ModelFactory::getModel("Comments")->deleteData($this->get["id"]);
                $this->redirect("comments!admin");
            }
            $this->redirect("user");
        }
    }