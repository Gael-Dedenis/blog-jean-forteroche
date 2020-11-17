<?php

    namespace App\Controller;

    /**
     * Class LogController
     * @package App\Controller
     */
    abstract class LogController
    {
        /**
         * @data mixed
         */
        protected $get = null;

        /**
         * @data mixed
         */
        protected $post = null;

        /**
         * @data mixed|null
         */
        private $session = null;

        /**
         * @data mixed
         */
        private $user = null;

        /**
         * LogController constructor
         */
        public function __construct()
        {
            $this->get     = filter_input_array(INPUT_GET);
            $this->post    = filter_input_array(INPUT_POST);

            $this->session = filter_data_array($_SESSION);
            if (isset($this->session['user_data']))
            {
                $this->user = $this->session['user_data'];
            }
        }

        /**
         * @param int $id
         * @param string $pseudo
         * @param string $email
         * @param string $password
         * @param string $status
         */
        public function sessionCreate(int $id, string $pseudo, string $email, string $password,string $status)
        {
            $_SESSION['user_data'] = [
                'id'       => $id,
                'pseudo'   => $pseudo,
                'email'    => $email,
                'pass'     => $password,
                'status'   => $status
            ];
        }

        /**
         * @return void
         */
        public function sessionDestroy()
        {
            $_SESSION['user_data'] = [];
        }

        /**
         * @return bool
         */
        public function getLog()
        {
            if (array_key_exists('user_data', $this->session)) {
                if (!empty($this->user)) {
                    return true;
                }
            }
            return false;
        }

        /**
         * @param $data
         * @return mixed
         */
        public function getUser($data)
        {
            if ($this->getLog() === false) {
                $this->user[$data] = null;
            }
            return $this->user[$data];
        }
    }