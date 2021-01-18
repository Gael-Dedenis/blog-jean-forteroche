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
         * On récupère les données d'un post sélectionné par son ID.
         * Dans le tableau "chapter" on va inclure les commentaires lié à l'ID du post.
         * @param string $id_chapter
         * @return array
         */
        private function getData(string $id_chapter) {
            $this->chapter                     = ModelFactory::getModel("Posts")->readData($id_chapter);
            $this->chapter["comments"]         = ModelFactory::getModel("Comments")->listData($id_chapter,"post_id");
            $this->chapter["comments_authors"] = ModelFactory::getModel("Users")->listData();

            return $this->chapter;
        }

        /**
         * On appel la fonction getData puis on envoie le tableau obtenue à la Vue. 
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function readMethod() {
            $this->getData($this->get["id"]);

            return $this->render("selectedpost.twig", ["chapter" => $this->chapter]);
        }

        /**
         * On récupère les données du nouveau chapitre.
         * @return array
         */
        private function getNewData() {
            $this->chapter["newTitle"]   = $this->post("newTitle");
            $this->chapter["newContent"] = $this->post("newContent");
            $this->chapter["date"]       = $this->date("y-m-d h:i:s");

            return $this->chapter;
        }

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function createMethod() {

            if (!empty($this->chapter)) {
                $this->getNewData();

                ModelFactory::getModel("Posts")->createData($this->chapter);
                $this->redirect("posts");
            }

            return $this->render("backend/admin_createChapter.twig");
        }

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
            /*
                On créer une variable propre à cette méthode avec un tableau vide pour stocker les modifs.
                Si il y a des modifications, on les récupères puis on passe le tableau des mofids à updateModifs.
                Ensuite on redirige sur la page "administration".
                Sinon on affiche la Vue permettant la modification avec le chapitre sélectionné (avec son ID).
            */
        public function modifyMethod()
        {
            $this->getNewData();

            if (!empty($this->chapter))
            {
                ModelFactory::getModel("Posts")->updateData($this->get["id"], $this->chapter);

                $this->redirect("posts");
            }

            $this->chapter = ModelFactory::getModel("Posts")->readData($this->get["id"]);

            return $this->render("backend/admin_modifyPost.twig", ["post" => $this->chapter]);
        }

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
            /*
                On récupère l'ID du post sélectionner.
                Puis on va aller chercher touts les commentaires liés à ce post.
                On vérifie si il y a des commentaires liés au post, si oui on les supprimes.
                Puis on supprime le post.
                Enfin on redirige sur la page "administration".
            */
        public function deleteMethod()
        {
            // ajouter un appel méthode suppression comments.
            ModelFactory::getModel("Posts")->deleteData($this->get["id"]);

            $this->redirect("administration");
        }
    }