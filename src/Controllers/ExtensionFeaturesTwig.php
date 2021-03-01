<?php

    namespace App\Controllers;

    use Twig\Extension\AbstractExtension;
    use Twig\TwigFunction;

    /**
     * Class ExtensionFeaturesTwig
     * Ajouts de functions pour les Vues Twig
     */
    class ExtensionFeaturesTwig extends AbstractExtension
    {
        /**
         * Ajouts de fonctions pour les Vues Twig.
         * @return array|TwigFunction[]
         */
        public function getFunctions() {
            return array(
                new TwigFunction("url", array($this, "url")),
            );
        }

        /**
         * Retourne l'url pour une page.
         * @param string $page
         * @param array $params
         * @return string
         */
        public function url(string $page, array $params = []) {
            $params["access"] = $page;
            return "index.php?" . http_build_query($params);
        }

    }