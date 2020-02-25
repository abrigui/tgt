<?php

namespace GestionPublicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GestionPublicationBundle:Default:index.html.twig');
    }
}
