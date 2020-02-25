<?php

namespace GestionAgenceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DemandeAgenceController extends Controller
{

    public function approuverAction(Request $request, $id){
        $agence = $this -> getDoctrine()->getRepository('AppBundle:Agence')->find($id);
        $em = $this->getDoctrine()->getManager();
        if($agence->getEtat()==0)
        {
            $agence->setEtat(1);
        }
        elseif($agence->getEtat()==1)
        {
            $agence->setEtat(0);
        }
        $em->persist($agence);
        $em->flush();
        return $this->redirectToRoute('admin');
    }

}
