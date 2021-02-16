<?php

    namespace App\Models;

    /**
     * Class MainModel
     * Création des Queries et service CRUD.
     * @package App\Models
    */
    abstract class MainModel
    {

        /**
         * Database
         * @var PdoDb
        */
        protected $database = null;

        /**
         * Database Table
         * @var string
         */
        protected $table = null;

        /**
         * Model constructeur
         * Action: Reçois l'objet de la BD & créer le Nom de Table.
         * @param PdoDb $database
         */
        public function __construct(PdoDb $database)
        {
            $this->database = $database;
            $model          = explode("\\", get_class($this));
            $this->table    = ucfirst(str_replace("Model", "", array_pop($model)));
        }

        /**
         * Action: Liste toutes les données à partir d'une Id ou d'un autre type de Clé.
         * @param string $value
         * @param string $key
         * @return array|mixed
         */
        public function listData(string $value = null, string $key = null)
        {
            if (isset($key))
            {
                $query = "SELECT * FROM " . $this->table . " WHERE " . $key . " = ?";

                return $this->database->getAllData($query, [$value]);
            }

            $query = "SELECT * FROM " . $this->table;

            return $this->database->getAllData($query);
        }

        /**
         * Action: Création d'une nouvelle entrée de données.
         * @param array $data
         */
        public function createData(array $data)
        {
            $keys   = implode(", ", array_keys($data));
            $values = implode("', '", $data);
            $query  = "INSERT INTO " . $this->table . " (" . $keys .") VALUES ('" . $values . "')";

            $this->database->setData($query);
        }

        /**
         * Action: Lecture de données à partir d'une Id ou toutes autres clés.
         * @param string $value
         * @param string|null $key
         * @return mixed
         */
        public function readData(string $value, string $key = null)
        {
            if (isset($key)) {
                $query = "SELECT * FROM " . $this->table . " WHERE " . $key . " = ?";
            } else {
                $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
            }
            return $this->database->getData($query, [$value]);
        }

        /**
         * Actions: Mise à jours des données sur l'entrée d'une ID ou de toutes autres clés.
         * @param string $value
         * @param array $data
         * @param string|null $key
         */
        public function updateData(string $value, array $data, string $key = null)
        {
            $set = null;

            foreach ($data as $dataKey => $dataValue)
            {
                $set .= $dataKey . " = '" . $dataValue . "', ";
            }

            $set = substr_replace($set, "", -2);

            if (isset($key))
            {
                $query = "UPDATE " . $this->table . " SET " . $set . " WHERE " . $key . " = ?";
            } else {
                $query = "UPDATE " . $this->table . " SET " . $set . " WHERE id = ?";
            }

            $this->database->setData($query, [$value]);
        }

        /**
         * Actions: Suppression des données sur l'entrée d'une ID ou toutes autres clés.
         * @param string $value
         * @param string|null $key
         */
        public function deleteData(string $value, string $key = null)
        {
            if (isset($key)) {
                $query = "DELETE FROM " . $this->table . " WHERE " . $key . " = ?";
            } else {
                $query = "DELETE FROM " . $this->table . " WHERE id = ?";
            }

            $this->database->setData($query, [$value]);
        }

    }
