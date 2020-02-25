<?php

namespace GestionPublicationBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\CommentairePublication;
use AppBundle\Entity\Evenement;
use AppBundle\Entity\Publication;
use AppBundle\Entity\PublicationLike;
use AppBundle\Form\CommentairePublicationType;
use AppBundle\Form\PublicationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PublicationController extends Controller
{

    // afficher la page d"accueil

    public function AccueilAction()
    {
        $evenement=$this->getDoctrine()->getRepository(Evenement::class)->findAll();
        $article=$this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('@GestionPublication/Accueil.html.twig',array('e'=>$evenement,'art'=>$article));

    }

    // ajouter une publication

    public function AjoutPublicationAction(Request $request)
    {
        $publication = new Publication();
        $form= $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);
        $em=$this->getDoctrine()->getManager();
        if($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('photo')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('photo_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $publication->setPhoto($newFilename);
            }
            $publication->setUtilisateur($this->getUser());
            $publication->setDate(new \DateTime('now'));
            $em->persist($publication);
            $em->flush();
            return $this->redirectToRoute('accueil');

        }
        return $this->render('@GestionPublication/Publication/ajout.html.twig', array(
            "f"=> $form->createView()
        ));
    }

    // afficher la liste des publication

    public function AffichePublicationAction($page)
    {
        $publication = new Publication();
        $nbPublicationParPage = $this->container->getParameter('nb_publication_par_page');
        $user = $this->getUser();
        $publication=$this->getDoctrine()->getRepository(Publication::class)->findAllPagineEtTrie($page, $nbPublicationParPage);
        $pagination = array(
            'page' => $page,
            'nbPages' => ceil(count($publication) / $nbPublicationParPage),
            'nomRoute' => 'affiche_publication',
            'paramsRoute' => array()
        );
        return $this->render('@GestionPublication/Publication/affiche.html.twig',array(
            'publication'=>$publication,
            'articles' => $publication,
            'pagination' => $pagination,
            'user' =>$user

        ));
    }

    // afficher les details d'une publication + l'ajout d'un commentaire

    public function AfficheDetailPublicationAction($id,Request $request)
    {
        $publication=$this->getDoctrine()->getRepository(Publication::class)->find($id);
        $commentaire=new CommentairePublication();
        $form=$this->createForm(CommentairePublicationType::class,$commentaire);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $commentaire->setUtilisateur($this->getUser());
            $commentaire->setPublication($publication);
            $commentaire->setDate(new \DateTime('now'));
            $em=$this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
            return $this->redirectToRoute('affiche_detail_publication',array('id'=>$id));
        }
        return $this->render('@GestionPublication/Publication/detail.html.twig', array(
            "publication"=>$publication,
            'f'=>$form->createView()
        ));
    }

    // supprimer une publication

    public function SupprimePublicationAction($id){
        $publication=$this->getDoctrine()->getRepository(Publication::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($publication);
        $em->flush();
        return $this->redirectToRoute('profile', array('page'=>'1'));
    }

    // modifier une publication

    public function ModifiePublicationAction(Request $request,$id)
    {
        $publication=$this->getDoctrine()->getRepository(Publication::class)->find($id);
        $form=$this->createForm(PublicationType::class,$publication);
        $form->handleRequest($request);
        $em=$this->getDoctrine()->getManager();
        if($form->isSubmitted())
        {
            $publication->setDate(new \DateTime('now'));
            $em->flush();
            return $this->redirectToRoute('affiche_detail_publication',array('id'=>$id));

        }
        return $this->render('@GestionPublication/Publication/modifier.html.twig', array(
            'f'=>$form->createView(),
            "publication"=>$publication
        ));

    }

    // supprimer un commentaire

    public function deleteCommentAction(Request $request)
    {
        $id = $request->get('id');
        $em= $this->getDoctrine()->getManager();
        $comment=$em->getRepository('AppBundle:CommentairePublication')->find($id);
        $em->remove($comment);
        $em->flush();
        return $this->redirectToRoute('affiche_publication');
    }

    // recherche une publication par contenu

    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $posts =  $em->getRepository(Publication::class)->findEntitiesByString($requestString);
        if(!$posts) {
            $result['posts']['error'] = "Aucun résultat ";
        } else {
            $result['posts'] = $this->getRealEntities($posts);
        }
        return new Response(json_encode($result));
    }
    public function getRealEntities($posts){
        foreach ($posts as $posts){
            $realEntities[$posts->getId()] = [$posts->getPhoto(),$posts->getContenu()];

        }
        return $realEntities;
    }

    // aimer une publication

    public function likeAction($id){


        $publication = new Publication();
        $like = $this->getDoctrine()->getRepository(PublicationLike::class);
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        if(!$user) return $this->json([
            'code' => '403',
            'message' => 'vous devez se connecter pour liker'
        ],403);
        if($publication->isLikedByUser($user)){
            $like->findOneBy(
             array(   'publication' => $publication,
                 'user' => $user
            ));
            $em->remove($like);
            $em->flush();

            return $this->json([
                'code' => '200',
                'message' => 'like supprimer'
            ],200);
        }
        $like = new PublicationLike();
        $like->setUtilisateur($user);
        $like->setPublication($publication);
        $em->persist($like);
        $em->flush();
        return $this->json([
            'code' => '200',
            'message' => 'like ajouter'
        ],200);

    }

}
