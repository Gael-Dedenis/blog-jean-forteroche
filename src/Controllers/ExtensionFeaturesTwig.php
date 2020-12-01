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
        public function getFunctions()
        {
            return array(
                new TwigFunction('url', array($this, 'url')),
                new TwigFunction("redirect", array($this, "redirect"))
            );
        }

        /**
         * Retourne l'url pour une page.
         * @param string $page
         * @param array $params
         * @return string
         */
        public function url(string $page, array $params = [])
        {
            $params['access'] = $page;
            return 'index.php?' . http_build_query($params);
        }

        /**
         * @param string $page
         * @param array $params
         */
        public function redirect(string $page, array $params = [])
        {
            header("Location: " . $this->url($page, $params));

            exit;
        }

        public function checkIsAdmin()
        {
            $roles = false;

            if (isset($session["user"]["admin"]))
            {
                if ($this->session["admin"] === 1)
                {
                    $roles = admin;
                }
                elseif ($this->session["admin"] === 0)
                {
                    $roles = membre;
                }

                return $roles;
            }
        }
    }