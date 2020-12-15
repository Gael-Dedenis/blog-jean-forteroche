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
        private $session = null;

        /**
         * GlobalController constructor
         */
        public function __construct()
        {
            $this->get     = filter_input_array(INPUT_GET);
            $this->post    = filter_input_array(INPUT_POST);
            $this->session = filter_var($_Session);
        }
    }