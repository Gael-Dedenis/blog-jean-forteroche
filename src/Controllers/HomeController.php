<?php

namespace App\Controllers;

use App\Models\Factory\ModelFactory;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class HomeController
 * appel la page Home
 * @package App\Controller
 */
class HomeController extends MainController
{
    /**
     * Rendu de la vue Home
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function defaultMethod()
    {
        $lastPost = array_reverse(ModelFactory::getModel('Posts')->listData());
        // utilisation de "ModelFactory::" grace à la définition de la méhtode en "static".

        return $this->render('home.twig', ['posts' => $lastPost]);
    }

}