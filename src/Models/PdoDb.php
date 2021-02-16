<?php

    namespace App\Models;

    use PDO;

    /**
     * Class PdoDb
     * Actions: préparation des Queries avant de les éxécutés et les retournés.
     * @package App\Models
     */
    class PdoDb
    {

        /**
         * PDO connection.
         * @var pdo
        */
        private $pdo = null;

        /**
         * PdoDB constructeur
         * Actions: reçoit la connection PDO et la stock.
         * @param PDO $pdo
        */
        public function __construct(PDO $pdo)
        {
            $this->pdo = $pdo;
        }

        /**
         * Actions: Retourne un unique résultat depuis la BD.
         * @param string $query Correspond à la requête.
         * @param array $params Les différents paramètres qu'on lui donne.
         * @return mixed
        */
        public function getData(string $query, array $params = [])
        {
            $PDOStatement = $this->pdo->prepare($query);
            $PDOStatement->execute($params);

            return $PDOStatement->fetch();
        }

        /**
        * Actions: Retourne plusieurs résultats depuis la BD.
        * @param string $query
        * @param array $params
        * @return array|mixed Peut retourner les résultats sous différentes formes.(tableaux, phrases, nombres, etc...)
        */
        public function getAllData(string $query, array $params = [])
        {
            $PDOStatement = $this->pdo->prepare($query);
            $PDOStatement->execute($params);

            return $PDOStatement->fetchAll();
        }

        /**
         * Actions: Execute une action vis à vis de la BD.
         * @param string $query
         * @param array $params
         * @return bool|mixed
         */
        public function setData(string $query, array $params = [])
        {
            $PDOStatement = $this->pdo->prepare($query);
            return $PDOStatement->execute($params);
        }

    }