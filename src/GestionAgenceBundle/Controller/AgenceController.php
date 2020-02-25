<?php

namespace GestionAgenceBundle\Controller;

use AppBundle\Entity\Agence;
use AppBundle\Entity\Evenement;
use AppBundle\Entity\Sponsor;
use AppBundle\Form\AgenceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AgenceController extends Controller
{
    function AfficheAgenceAction(){
        $agence=$this->getDoctrine()->getRepository(Agence::class)->findAll();
        return $this->render('@GestionAgence/Agence/AfficheAgence.html.twig',array('c'=>$agence));
    }

    public function DetailAgenceAction($id){
        $agence=$this->getDoctrine()->getRepository(Agence::class)->find($id);
        $evenement=$this->getDoctrine()->getRepository(Evenement::class)->findBy(array('agence'=>$agence));
        return $this->render('@GestionAgence/Agence/DetailAgence.html.twig',array('form'=>$agence,'event'=>$evenement));
    }

    //affiche agence admin
    function AfficheAgenceBackAction(){
        $agence=$this->getDoctrine()->getRepository(Agence::class)->findAll();
        return $this->render('@GestionPublication/afficheAgenceBack.html.twig',array('c'=>$agence));
    }

    function AjoutAgenceAction(Request $request){

        $agence=new Agence();

        $form=$this->createForm(AgenceType::class,$agence);
        $form->handleRequest($request);//cree un copie du formulaire dans le cash
        $em=$this->getDoctrine()->getManager();
        if($form->isSubmitted())
        {
            if((($agence->getTelephone()>=11111111) && ($agence->getTelephone()<=99999999))&&(($agence->getFax()>=11111111) && ($agence->getFax()<=99999999)))
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

                    $agence->setLogo($newFilename);
                }
                $user=$this->getUser();
                $agence->setUtilisateurProfessionnel($user);
                $agence->setEtat(0);
                $em->persist($agence);
                $em->flush();
                return $this->redirectToRoute('AfficheAgence');}
            elseif(!(($agence->getTelephone()>=11111111) && ($agence->getTelephone()<=99999999))||!(($agence->getFax()>=11111111) && ($agence->getFax()<=99999999)))
            {
                echo"<script>alert(\"Le numéro du télephone ou du fax doit être composé de 8 chiffre\")</script>";
            }
        }

        return $this->render('@GestionAgence/Agence/AjoutAgence.html.twig',array('f'=>$form->createView()));
    }



    function ModifAgenceAction(Request $request,$id){
        $agence=$this->getDoctrine()->getRepository(Agence::class)->find($id);
        $form=$this->createForm(AgenceType::class,$agence);
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

                $agence->setLogo($newFilename);
            }
            $em->flush();
            return $this->redirectToRoute('AfficheAgence');
        }
        return $this->render('@GestionAgence/Agence/ModifAgence.html.twig',array('f'=>$form->createView()));
    }



    function SuppressionAgenceAction($id){
        $agence=$this->getDoctrine()->getRepository(Agence::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($agence);
        $em->flush();
        return $this->redirectToRoute('AfficheAgence');
    }

    //supprimer agence adiministrateur
    function SuppressionAgenceBackAction($id){
        $agence=$this->getDoctrine()->getRepository(Agence::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($agence);
        $em->flush();
        return $this->redirectToRoute('AfficheAgenceBack');
    }

    public  function AgencePdfAction(){

        $listeAgence=$this->getDoctrine()->getRepository(Agence::class)->findAll();
        $snappy=$this->get('knp_snappy.pdf');
        $file_name="Liste Agence";
        return new Response(
            $snappy->getOutputFromHtml($this->renderView('@GestionAgence/Agence/AfficherAgencePdf.html.twig',array("c"=>$listeAgence))),
            200,
            array(
                'Content-Type'=>'application/pdf',
                'Content-Disposition'=>'attachement; filename="'.$file_name.'.pdf'
            )
        );

    }


    function AfficheDemandeBackAction(){
        $agence=$this->getDoctrine()->getRepository(Agence::class)->findAll();
        return $this->render('@GestionPublication/admin.html.twig',array('c'=>$agence));
    }

    public function appajxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $op = $request->get('op');
        $posts =  $em->getRepository(Sponsor::class)->findEntitiesByString($op);
        $agence = $em->getRepository(Agence::class)->find($op);
        $etat = $agence->getEtat();
        if($etat == 1){
            $agence->setEtat(0);
            $em->flush();
            return new Response('approuver');
        }else{
            $agence->setEtat(1);
            $em->flush();
            return new Response('desapprouver');
        }
    }


}

