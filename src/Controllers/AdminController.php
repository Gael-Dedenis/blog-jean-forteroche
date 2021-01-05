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
            if ($this->checkRole() === "admin")
            {
                return $this->render("backend/admin.twig");
            }

            $this->redirect("home");
        }


    }