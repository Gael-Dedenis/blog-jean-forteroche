<?php

    namespace App\Models\Factory;

    use PDO;

    /**
     * Classe PDOFactory.
     * Actions: Créer une connection à la BD si aucune existe.
     * @package App\Model
     */

    class PDOFactory
    {

        /**
         * Actions: Stock la connection
         * @var null
         */
        private static $pdo = null;

        /**
         * Actions: On retourne la connection si elle existe,
         * Sinon on la créer puis on la retourne.
         */
        public static function getPDO()
        {
            require_once "../config/config_dev.php";

            if (self::$pdo === null)
            {
                self::$pdo = new PDO(DB_HOST, DB_USER, DB_PASS);
                self::$pdo->exec("SET NAMES UTF8");
            }

            return self::$pdo;
        }

    }