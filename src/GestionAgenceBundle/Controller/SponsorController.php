<?php

namespace GestionAgenceBundle\Controller;

use AppBundle\Entity\Sponsor;
use AppBundle\Form\SponsorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SponsorController extends Controller
{

//  *****************Fonction Afficher Les Sponsor*****************
    function AfficheSponsorAction(){
        $sponsor=$this->getDoctrine()->getRepository(Sponsor::class)->findAll();
        return $this->render('@GestionAgence/Sponsor/AfficheSponsor.html.twig',array('sponsor'=>$sponsor));
    }





//  *****************Fonction Ajouter Un Sponsor*****************
    function AjoutSponsorAction(Request $request){
        $sponsor = new Sponsor();
        $form=$this->createForm(SponsorType::class,$sponsor);
        $form->handleRequest($request);//cree un copie du formulaire dans le cash
        $em=$this->getDoctrine()->getManager();
        if($form->isSubmitted())
        {
            $imageFile = $form->get('logo')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('logo_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

                $sponsor->setLogo($newFilename);
            }
            $em->persist($sponsor);
            $em->flush();
            return $this->redirectToRoute('AfficheSponsor');
        }
        return $this->render('@GestionAgence/Sponsor/AjoutSponsor.html.twig',array('f'=>$form->createView()));
    }

// ********************Fonction Modifier Un Sponsor*******************
    function ModifSponsorAction(Request $request,$id){
        $sponsor=$this->getDoctrine()->getRepository(Sponsor::class)->find($id);
        $form=$this->createForm(SponsorType::class,$sponsor);
        $form->handleRequest($request);
        $em=$this->getDoctrine()->getManager();
        if($form->isSubmitted())
        {
            $imageFile = $form->get('logo')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('logo_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

                $sponsor->setLogo($newFilename);
            }
            $em->flush();
            return $this->redirectToRoute('AfficheSponsor');
        }
        return $this->render('@GestionAgence/Sponsor/ModifSponsor.html.twig',array('f'=>$form->createView()));
    }





    function SuppressionSponsorAction($id){
        $sponsor=$this->getDoctrine()->getRepository(Sponsor::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($sponsor);
        $em->flush();
        return $this->redirectToRoute('AfficheSponsor');
    }


    public  function SponsorPdfAction(){

        $listeSponsor=$this->getDoctrine()->getRepository(Sponsor::class)->findAll();
        $snappy=$this->get('knp_snappy.pdf');
        $file_name="Liste Sponsor";
        return new Response(
            $snappy->getOutputFromHtml($this->renderView('@GestionAgence/Sponsor/AfficherSponsorPdf.html.twig',array("sponsor"=>$listeSponsor))),
            200,
            array(
                'Content-Type'=>'application/pdf',
                'Content-Disposition'=>'attachement; filename="'.$file_name.'.pdf'
            )
        );

    }









    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $posts =  $em->getRepository(Sponsor::class)->findEntitiesByString($requestString);
        if(!$posts) {
            $result['posts']['error'] = "Aucun résultat ";
        } else {
            $result['posts'] = $this->getRealEntities($posts);
        }
        return new Response(json_encode($result));
    }
    public function getRealEntities($posts){
        foreach ($posts as $posts){
            $realEntities[$posts->getId()] = [$posts->getLogo(),$posts->getNom()];

        }
        return $realEntities;
    }




}

