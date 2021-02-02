<?php

    namespace App\Controllers;

    use App\Models\Factory\ModelFactory;
    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;

    /**
     * Class AdminController
     * @package App\Controller
     */
    class AdminController extends MainController
    {

        /**
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function defaultMethod()
        {
            if ($this->session["user"]["status"] === "2")
            {
                $allChapters = ModelFactory::getModel("Posts")->listData();
                return $this->render("backend/admin.twig", ["chapters" => $allChapters]);
            }

            $this->redirect("home");
        }


    }