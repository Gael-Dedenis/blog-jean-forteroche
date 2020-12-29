<?php

    namespace App\Controllers;

    /**
     * Class GlobalController
     * @package App\Controller
     */
    abstract class GlobalController
    {
        /**
         * @var mixed
         */
        protected $get = null;

        /**
         * @var mixed
         */
        protected $post = null;

        /**
         * @var mixed|null
         */
        private $session = [];

        /**
         * @var mixed
         */
        private $user = null;

        /**
         * GlobalController constructor
         */
        public function __construct()
        {
            $this->get     = filter_input_array(INPUT_GET);
            $this->post    = filter_input_array(INPUT_POST);
            $this->session = filter_var_array($_SESSION);
        }

        /**
         * Vérification si l'utilisateur est connecté
         * @return string
         */
        public function checkConnection() :string
        {
            if (array_key_exists("user", $this->session) && !empty($this->session["user"]))
            {
                $this->checkRole();

                return $this->user;
            }
        }

        /**
         * Vérification du rôle de l'utilisateur
         * @return string
         */
        private function checkRole() :string
        {
            $this->user = $this->session["user"];

            switch ($this->user["status"])
            {
                case 1:
                    $this->user["role"] = "membre";
                    break;

                case 2:
                    $this->user["role"] = "admin";
                    break;

                default:
                    $this->user["role"] = "visiteur";
            }

            return $this->user;
        }
    }