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
                try
                {
                    self::$pdo = new PDO(DB_HOST, DB_USER, DB_PASS, array(PDO::ATTR_MODE => PDO::ERRMODE_WARNING));
                    self::$pdo->exec("SET NAMES UTF8");
                }
                catch (PDOException $e)
                {
                    echo "Échec de la connexion : " . $e->getMessage();
                    exit;
                }
            }

            return self::$pdo;
        }

    }