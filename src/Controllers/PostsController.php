<?php

    namespace App\Controllers;

    use App\Models\Factory\ModelFactory;
    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;

    /**
     * Class PostsController
     * @package App\Controllers
     */
    class PostsController extends MainController
    {
        /**
         * @var
        */
        private $chapter = [];

        /**
         * @var
        */
        private $type = null;

        /**
         * On récupère touts les posts dans la base de données. Puis on envoie le résultat à la Vue.
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function defaultMethod() {
            $allPosts = ModelFactory::getModel("Posts")->listData();

            return $this->render("allposts.twig", ["posts" => $allPosts]);
        }

        /**
         * On appel la fonction getData puis on envoie le tableau obtenue à la Vue. 
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function readMethod() {
            if (!empty($this->get["id"])) {
                $this->getData($this->get["id"]);
                return $this->render("selectedpost.twig", ["chapter" => $this->chapter]);
            }
            $this->redirect("posts");
        }

        /**
         * On récupère les données d'un post sélectionné par son ID.
         * Dans le tableau "chapter" on va inclure les commentaires lié à l'ID du post.
         * @param string $id_chapter
         * @return array
         */
        private function getData(string $id_chapter) {
            $this->chapter                     = ModelFactory::getModel("Posts")->readData($id_chapter);
            $this->chapter["chapter_content"]  = $this->chapter["content"];
            $this->chapter["comments"]         = ModelFactory::getModel("Comments")->listData($id_chapter,"post_id");
            $this->chapter["comments_authors"] = ModelFactory::getModel("Users")->listData();

            return $this->chapter;
        }

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function createMethod() {
            if ($this->checkAdmin()) {
                if (!empty($this->post)) {
                    $this->getNewData($this->type = "");

                    ModelFactory::getModel("Posts")->createData($this->chapter);
                    $this->redirect("admin");
                }

                return $this->render("backend/admin_createChapter.twig");
            }
            $this->redirect("user");
        }

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function modifyMethod()
        {
            if ($this->checkAdmin()) {
                if(!empty($this->post)) {
                    $this->getNewData($this->type = "modify");

                    ModelFactory::getModel('Posts')->updateData($this->post["chapter_id"], $this->chapter);

                    $this->redirect("admin");
                }

                $this->chapter["selectedPost"]    = ModelFactory::getModel("Posts")->readData($this->get["id"]);
                $this->chapter["chapter_content"] = $this->chapter["selectedPost"]["content"];

                return $this->render("backend/admin_modifyPost.twig", ["chapterToModify" => $this->chapter["selectedPost"], "chapter_content" => $this->chapter["chapter_content"]]);
            }
            $this->redirect("user");
        }

        /**
         * On récupère les données du nouveau chapitre.
         * @param string type
         * @return array
         */
        private function getNewData(string $type) {

            switch($type) {
                case "modify":
                    $this->chapter["title"]         = addslashes($this->post["chapter_title"]);
                    $this->chapter["content"]       = addslashes($this->post["chapter_content"]);
                    $this->chapter["modified_date"] = date("d-m-y h:i:s");
                    return $this->chapter;
                    break;

                default:
                    $this->chapter["title"]        = addslashes($this->post["chapter_title"]);
                    $this->chapter["content"]      = addslashes($this->post["chapter_content"]);
                    $this->chapter["created_date"] = date("d-m-y h:i:s");
                    return $this->chapter;
            }

        }

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */

        public function deleteMethod()
        {
            if ($this->checkAdmin()) {
                $this->chapter["allPost"]     = ModelFactory::getModel("Posts")->readData($this->post["chapterSelect"], "title");
                $this->chapter["allComments"] = ModelFactory::getModel("Comments")->listData($this->chapter["allPost"]["id"], "post_id");

                if(!empty($this->chapter["allComments"])) {
                    ModelFactory::getModel("Comments")->deleteData($this->chapter["allPost"]["id"], "post_id");
                    }

                    ModelFactory::getModel("Posts")->deleteData($this->chapter["allPost"]["id"]);

                $this->redirect("admin");
            }

            $this->redirect("user");
        }
    }