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

                $nbrReportedComms = count($this->countReportedComms());

                return $this->render("backend/admin.twig", ["chapters" => $allChapters, "nbrReported" => $nbrReportedComms]);
            }

            $this->redirect("home");
        }

        /**
         * @return string|int
         */
        private function countReportedComms() {
            return ModelFactory::getModel("Comments")->listdata("1", "reported");
        }
    }