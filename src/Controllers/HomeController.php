<?php

namespace App\Controllers;

use App\Models\Factory\ModelFactory;
use App\Controllers\MainController;
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
        $lastPost = array_reverse(array(ModelFactory::getModel('Posts')->listData()));

    return $this->render('home.twig', ['posts' => $lastPost]);
    }

}