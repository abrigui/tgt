<?php

namespace GestionPublicationBundle\Controller;

use AppBundle\Entity\Agence;
use AppBundle\Entity\Publication;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    // afficher le profile d'utilisateur talent connecter
    public function ProfilTalentAction($page)
    {
        $p = new Publication();
        $user = $this->getUser();
        if( $user->hasRole('ROLE_USERT')) {
        $nbPublicationParPage = $this->container->getParameter('nb_publication_par_page');
        $user = $this->getUser();
        $publication=$this->getDoctrine()->getRepository(Publication::class)->findAllPagineEtTrie($page, $nbPublicationParPage);
        $pagination = array(
            'page' => $page,
            'nbPages' => ceil(count($publication) / $nbPublicationParPage),
            'nomRoute' => 'profile',
            'paramsRoute' => array()
        );
        return $this->render('@GestionPublication/profile.html.twig',array(
            'publication'=>$publication,
            'p' =>$p,
            'pagination' => $pagination,
            'user' =>$user

        ));
        }

    }
    // afficher le profile d'utilisateur pro connecter

    public function ProfilProAction()
    {
        $user = new User();
        $user = $this->getUser();
        if ($user->getRoles('ROLE_USERP')){
            $agence=$this->getDoctrine()->getRepository(Agence::class)->agenceByUtilisateur($user);
            return $this->render('@GestionPublication/profilePro.html.twig', array(
                'agence' => $agence

            ));
        }

    }

// redirection selon le role

    public function loginRedirectAction(){
        if($this->getUser()->hasRole('ROLE_USERT') || $this->getUser()->hasRole('ROLE_USERP'))
        {
            return $this->redirect($this->generateUrl('accueil'));
        }
        elseif($this->getUser()->hasRole('ROLE_ADMIN'))
                return $this->redirect($this->generateUrl('admin'));
    }

    // redirection apres inscription

    public function RegisterRedirectAction(){
        if($this->getUser()->hasRole('ROLE_USERT') || $this->getUser()->hasRole('ROLE_USERP'))
        {
            return $this->redirect($this->generateUrl('accueil'));
        }
    }

    // afficher la partie back des utilisateurs

    public function frontBackAction()
    {
        return $this->render('@GestionPublication/frontBack.html.twig');
    }

    // afficher la liste des utilisateurs talents

    public function afficheTalentAction(){
        $user=$this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('@GestionPublication/afficheTalent.html.twig',array('user'=>$user));

    }

    // afficher profile  utilisateur talent avec id

    public function afficheProfileAction($id){
        $user=$this->getDoctrine()->getRepository(User::class)->find($id);
        $publication = $this->getDoctrine()->getRepository(Publication::class)->publicationByUtilisateur($user);
        return $this->render('@GestionPublication/ConsulterProfile.html.twig',array('user'=>$user,'publication'=>$publication));
    }


    // afficher le profile d'utilisateur pro avec id

    public function afficheProfileProAction($id){
        $user=$this->getDoctrine()->getRepository(User::class)->find($id);
        $agence = $this->getDoctrine()->getRepository(Agence::class)->agenceByUtilisateur($user);
        return $this->render('@GestionPublication/ConsulterProfilePro.html.twig',array('user'=>$user,'agence'=>$agence));
    }



    // afficher la liste  des utilisateurs back

    public function dashboardUtilisateurAction(){
        $user=$this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('@GestionPublication/dashboardUtilisateur.html.twig',array('user'=>$user));

    }

    // supprimer utilisateur back


    public function SupprimerUtilisateurAction($id){
        $user=$this->getDoctrine()->getRepository(User::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('dashboard_utilisateur');
    }


}
