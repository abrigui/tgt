<?php

namespace GestionEvenementBundle\Controller;

use AppBundle\Entity\Agence;
use AppBundle\Entity\Categorie;
use AppBundle\Entity\Evenement;
use AppBundle\Entity\Participation;
use AppBundle\Entity\SousCategorie;
use AppBundle\Form\CategorieType;
use AppBundle\Form\EvenementType;
use AppBundle\Form\SousCategorieType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EvenementController extends Controller
{
    function AjoutCategorieAction(Request $request){
        $categorie=new Categorie();
        $form=$this->createForm(CategorieType::class,$categorie);
        $form->handleRequest($request);//cree un copie du formulaire dans le cash
        $em=$this->getDoctrine()->getManager();
        if($form->isSubmitted())
        {
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('AfficheCategorie');
        }
        return $this->render('@GestionEvenement/Evenement/AjouterCategorie.html.twig',array('f'=>$form->createView()));
    }

    public function AfficheCategorieAction(){
        $categorie=$this->getDoctrine()->getRepository(Categorie::class)->findAll();
        return $this->render('@GestionEvenement/Evenement/AfficheCategorie.html.twig',array('c'=>$categorie));
    }

    function SuppressionCategorieAction($id){
        $categorie=$this->getDoctrine()->getRepository(Categorie::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();
        return $this->redirectToRoute('AfficheCategorie');
    }

    function UpdateCategorieAction(Request $request,$id){
        $categorie=$this->getDoctrine()->getRepository(Categorie::class)->find($id);
        $form=$this->createForm(CategorieType::class,$categorie);
        $form->handleRequest($request);
        $em=$this->getDoctrine()->getManager();
        if($form->isSubmitted())
        {
            $em->flush();
            return $this->redirectToRoute('AfficheCategorie');
        }
        return $this->render('@GestionEvenement/Evenement/ModifierCategorie.html.twig',array('f'=>$form->createView()));
    }

    function AjoutSousCategorieAction(Request $request){
        $sousCategorie=new SousCategorie();
        $form=$this->createForm(SousCategorieType::class,$sousCategorie);
        $form->handleRequest($request);//cree un copie du formulaire dans le cash
        $em=$this->getDoctrine()->getManager();
        if($form->isSubmitted())
        {
            $em->persist($sousCategorie);
            $em->flush();
            return $this->redirectToRoute('AfficheSousCategorie');
        }
        return $this->render('@GestionEvenement/Evenement/AjouterSousCategorie.html.twig',array('f'=>$form->createView()));
    }

    public function AfficheSousCategorieAction(){
        $sousCategorie=$this->getDoctrine()->getRepository(SousCategorie::class)->findAll();
        return $this->render('@GestionEvenement/Evenement/AfficherSousCategorie.html.twig',array('c'=>$sousCategorie));
    }

    function SuppressionSousCategorieAction($id){
        $sousCategorie=$this->getDoctrine()->getRepository(SousCategorie::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($sousCategorie);
        $em->flush();
        return $this->redirectToRoute('AfficheSousCategorie');
    }

    function UpdateSousCategorieAction(Request $request,$id){
        $sousCategorie=$this->getDoctrine()->getRepository(SousCategorie::class)->find($id);
        $form=$this->createForm(SousCategorieType::class,$sousCategorie);
        $form->handleRequest($request);
        $em=$this->getDoctrine()->getManager();
        if($form->isSubmitted())
        {
            $em->flush();
            return $this->redirectToRoute('AfficheSousCategorie');
        }
        return $this->render('@GestionEvenement/Evenement/ModifierSousCategorie.html.twig',array('f'=>$form->createView()));
    }

    function AjoutEvenementAction(Request $request,$idAgence){
        $evenement=new Evenement();
        $form=$this->createForm(EvenementType::class,$evenement);
        $agence=$this->getDoctrine()->getRepository(Agence::class)->find($idAgence);
        $form->handleRequest($request);//cree un copie du formulaire dans le cash
        $em=$this->getDoctrine()->getManager();
        if($form->isSubmitted())
        {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('evenement_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

                $evenement->setImage($newFilename);
            }
            $evenement->setAgence($agence);
            $em->persist($evenement);
            $em->flush();
            $agence=$evenement->getAgence();
            $msg=$agence->getNom()." votre évènement intitulé ".$evenement->getNom()." a bien été créé il se tiendra lieu du ".$evenement->getDateDebut()->format(('d-m-Y')).
                " au ".$evenement->getDateFin()->format(('d-m-Y'))." à ".$evenement->getLieu()." nous vous remercions d'avoir choisi notre platforme";
            $tel='+216'.$agence->getTelephone();
            $twilio = $this->get('twilio.api');

            $message = $twilio->account->messages->sendMessage(
                '+12568575881', // From a Twilio number in your account
                $tel, // Text any number
                $msg
            );

            //get an instance of \Service_Twilio
            $otherInstance = $twilio->createInstance('BBBB', 'CCCCC');

            //return new Response($message->sid);
            return $this->redirectToRoute('AfficherEvenement');
        }
        return $this->render('@GestionEvenement/Evenement/AjouterEvenement.html.twig',array('f'=>$form->createView()));
    }

    public function AfficherEvenementAction(){
        $evenement=$this->getDoctrine()->getRepository(Evenement::class)->findAll();
        return $this->render('@GestionEvenement/Evenement/AfficherEvenement.html.twig',array('e'=>$evenement));
    }

    public function AfficherEvenementBackAction(){
        $evenement=$this->getDoctrine()->getRepository(Evenement::class)->findAll();
        return $this->render('@GestionEvenement/Evenement/AfficheEvenementBack.html.twig',array('e'=>$evenement));
    }

    public function AfficherEvenementFrontAction(Request $request){
        //$evenement=$this->getDoctrine()->getRepository(Evenement::class)->findAll();
        //return $this->render('@GestionEvenement/Evenement/AfficheEvenementFront.html.twig',array('e'=>$evenement));
        $evenement= new Evenement();
        $Form=$this->createFormBuilder($evenement)
            ->add('SousCategorie',EntityType::class,array(
                'class'=>'AppBundle:SousCategorie',//sur quelle class pointer
                'choice_label'=>'libelle',//je veux afficher la liste des noms
                'multiple'=>false// desactiver le choix multiple

            ))
            ->add('Filtrer',SubmitType::class)
            ->getForm();
        $Form->handleRequest($request);
        if($Form->isSubmitted()){
            $evenement=$this->getDoctrine()->getRepository(Evenement::class)->findBy(array('sousCategorie'=>$evenement->getSousCategorie()));
        }
        else{
            $evenement=$this->getDoctrine()->getRepository(Evenement::class)->findAll();
        }
        return $this->render('@GestionEvenement/Evenement/AfficheEvenementFront.html.twig',array('f'=>$Form->createView(),'e'=>$evenement));
    }

    public function DetailEvenementAction($id){
        $user=$this->getUser();
        $evenement=$this->getDoctrine()->getRepository(Evenement::class)->find($id);
        $participant=$this->getDoctrine()->getRepository(Participation::class)->findParticipant($user,$id);
        $nbrParticipant=$this->getDoctrine()->getRepository(Participation::class)->findNombreParticipantParEvenement($id);
        return $this->render('@GestionEvenement/Evenement/DetailEvenement.html.twig',array('e'=>$evenement,'participant'=>$participant,'nbParActuel'=>$nbrParticipant));
    }

    function SuppressionEvenementAction($id){
        $evenement=$this->getDoctrine()->getRepository(Evenement::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($evenement);
        $em->flush();
        return $this->redirectToRoute('AfficherEvenement');
    }

    function UpdateEvenementAction(Request $request,$id){
        $evenement=$this->getDoctrine()->getRepository(Evenement::class)->find($id);
        $form=$this->createForm(EvenementType::class,$evenement);
        $form->handleRequest($request);
        $em=$this->getDoctrine()->getManager();
        if($form->isSubmitted())
        {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('evenement_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

                $evenement->setImage($newFilename);
            }
            $em->flush();
            return $this->redirectToRoute('AfficherEvenement');
        }
        return $this->render('@GestionEvenement/Evenement/ModifierEvenement.html.twig',array('f'=>$form->createView()));
    }


    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $posts =  $em->getRepository(Evenement::class)->findEntitiesByString($requestString);
        if(!$posts) {
            $result['posts']['error'] = "Aucun résultat !";
        } else {
            $result['posts'] = $this->getRealEntities($posts);
        }
        return new Response(json_encode($result));
    }
    public function getRealEntities($posts){
        foreach ($posts as $posts){
            $realEntities[$posts->getId()] = [$posts->getImage(),$posts->getNom()];

        }
        return $realEntities;
    }

    public function participationAction($idEvenement,$iduser){
        $participation=new Participation();
        $participation->setEvenement($idEvenement);
        $participation->setUtilisateur($iduser);
        $em=$this->getDoctrine()->getManager();
        $em->persist($participation);
        $em->flush();
        return $this->redirectToRoute('DetailEvenement',array('id'=>$idEvenement));
    }
    public function supprimerParticipationAction($idEvenement,$iduser){
        $participation=new Participation();
        $res=$this->getDoctrine()->getRepository(Participation::class)->findParticipant($iduser,$idEvenement);
        $participation=$res[0];
        $em=$this->getDoctrine()->getManager();
        $em->remove($participation);
        $em->flush();
        return $this->redirectToRoute('DetailEvenement',array('id'=>$idEvenement));
    }
}
