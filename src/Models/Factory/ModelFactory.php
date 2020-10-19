<?php

    namespace App\Models\Factory;

    use App\Models\PdoDb;

    /**
     * Classe ModelFactory.
     * Actions: Création de model si il n'existe pas.
     * @package App\Model
     */

    class ModelFactory
    {

        /**
         * Models
         * @var array
         */
        private static $models = [];

        /**
         * Actions: On retourne le model si il existe sinon on le créer avant de retourner celui-ci.
         * @param $table
         * @return mixed
         */
        public static function getModel(string $table)
        {
            if (array_key_exists($table, self::$models))
            {
                return self::$models[$table];
            }

            $class                = "App\Models\\" . \ucfirst($table) . "Model";
            self::$models[$table] = new $class(new PdoDb(PdoFactory::getPDO));

            return self::$models[$table];
        }

    }