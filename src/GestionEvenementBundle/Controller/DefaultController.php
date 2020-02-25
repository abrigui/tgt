<?php

namespace GestionEvenementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GestionEvenementBundle:Default:index.html.twig');
    }
}
