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
         * @var
        */
        private $post = [];

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function defaultMethod()
        {
            /* On récupère touts les posts dans la base de données. Puis on envoie le résultat à la Vue. */

            $allPosts = (ModelFactory::getModel("Posts")->listData());

            return $this->render("allposts.twig", ["posts" => $allPosts]);
        }

        /**
         * @return array
         */
            /*
                On récupère les données d'un post sélectionné par son ID.
                Dans le tableau "post" on va inclure les commentaires lié à l'ID du post.
            */
        private function getData()
        {


            $this->post = ModelFactory::getModel("Posts")->readData($_GET["id"]);

            $this->post["comments"] = ModelFactory::getModel("Comments")->listData($_GET["id"],"post_id");
        }

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
            /* On appel la fonction getData puis on envoie le tableau obtenue à la Vue. */
        public function readMethod()
        {


            $this->getData();

            return $this->render("selectedpost.twig", ["post" => $this->post]);
        }

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
            /*
                On récupère les données écrites "title" et "content".
                On vérifie si il n'y a aucune donnée de récupérer. Si oui, on redirige sur la page principale "administration".
                On appel la méthode createData du ModelFactory et créer une nouvelle entrée dans la base de données pour les Posts.
                Enfin on redirige sur la page principale "administration".
            */
        public function createMethod()
        {

            $title   = $this->post["title"];
            $content = $this->post["content"];

            if (empty($title && $content)) {
                $this->redirect("administration");
            }

            $createPost = ModelFactory::getModel("Posts")->createData($title,$content);
            $this->redirect("administration");
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

            $id_post = $this->get["id"];

            $post_comments = ModelFactory::getModel("Comments")->listData($id_post, "post_id");

            if (!empty($post_comments))
            {
                ModelFactory::getModel("Comments")->deleteData($id_post, "post_id");
            }

            ModelFactory::getModel("Posts")->deleteData($id_post);

            $this->redirect("administration");
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
            $modifs = [];

            if (!empty($modif))
            {
                $modifs ["title"]   = post["title"];
                $modifs ["content"] = post["content"];

                $updateModifs = ModelFactory::getModel("Posts")->updateData($this->$_GET["id"], $this->$modifs['title'], $this->$modifs["content"]);

                $this->redirect("administration");
            }

            $this->post = ModelFactory::getModel("Posts")->readData($_GET["id"]);

            return $this->render("modifyPost.twig", ["post" => $this->post]);
        }

    }