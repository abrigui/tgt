<?php

namespace ArticleBundle\Controller;

use AppBundle\Entity\CommentaireArticle;
use AppBundle\Entity\RatingArticle;
use AppBundle\Form\CommentaireArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Article;					 //A MODIFIER SELON TRAVAIL
use ArticleBundle\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    /**
     * @Route("/addArticle")
     */

    public function addArticleAction(Request $request)
    {
        $article =new Article();
        $formArticle = $this->createForm('ArticleBundle\Form\ArticleType',$article);
        $formArticle->handleRequest($request);
        if($formArticle->isSubmitted() && $formArticle->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $article->setDate(new \DateTime('now'));
            $article->setUser($this->getUser());
            $em->persist($article);
            $em->flush();
            $this->redirect($this->generateUrl('Display_article_front', array('theme' => 'all')));

        }


        return $this->render('@Article/Article/add_article.html.twig', array(
            'formArticle' => $formArticle->createView() // ...
        ));
    }


    public function addArticleBackAction(Request $request)
    {
        $article =new Article();
        $formArticle = $this->createForm('ArticleBundle\Form\ArticleType',$article);
        $formArticle->handleRequest($request);
        if($formArticle->isSubmitted() && $formArticle->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $article->setDate(new \DateTime('now'));
            $article->setUser($this->getUser());
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('List_Article');

        }


        return $this->render('@Article/Article/add_article_back.html.twig', array(
            'formArticle' => $formArticle->createView() // ...
        ));
    }
    /**
     * @Route("/deleteArticle")
     */
    public function deleteArticleAction($id)
    {

        $article =new Article();
        $doctrine= $this->getDoctrine();
        $repository = $doctrine->getRepository(  'AppBundle:Article');
        $salle = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($salle);
        $em->flush();


        return $this->forward('ArticleBundle:Article:displayArticleFront', array('theme' => 'all'));


    }


    public function deleteArticlebackAction($id)
    {

        $article =new Article();
        $doctrine= $this->getDoctrine();
        $repository = $doctrine->getRepository(  'AppBundle:Article');
        $salle = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($salle);
        $em->flush();
        return $this->forward('ArticleBundle:Article:displayArticle');
    }

    /**
     * @Route("/modifyArticle")
     */
    public function ModifiyArticleBackAction(Request $request , Article $article)
    {
        $editFormArticle = $this->createForm('ArticleBundle\Form\ArticleType',$article);
        $editFormArticle->handleRequest($request);
        if ($editFormArticle->isSubmitted() && $editFormArticle->isValid())
        {
            //$events->setDateUpdate(new \DateTime('now'));
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('List_Article');
        }

        return $this->render('@Article/Article/modify_article_back.html.twig', array(
            'theme'    => $article,
            'formthemes'    => $editFormArticle->createView(),

        ));
    }

    /**
     * @Route("/displayArticle")
     */
    public function displayArticleAction()
    {
        $doctrine= $this->getDoctrine();
        $repository = $doctrine->getRepository('AppBundle:Article');
        $Articles = $repository->findAll();
        return $this->render('@Article/Article/display_article_back.html.twig', array(
            'Articles'=>$Articles,
        ));
    }


    public function displayArticleSoloAction(Article $article,Request $request)
    {

        $doctrine= $this->getDoctrine();
        $repository = $doctrine->getRepository('AppBundle:CommentaireArticle');
        $commentaires = $repository->findAll();
        $commentaire=new CommentaireArticle();
        $form=$this->createForm(CommentaireArticleType::class,$commentaire);
        $doctrine= $this->getDoctrine();
        $repository = $doctrine->getRepository('AppBundle:Article');
        $Articles = $repository->getarticle($this->getUser(),$article);
        $nbcoment = $repository->getcoments($article->getId());
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $commentaire->setUtilisateur($this->getUser());
            $commentaire->setArticle($article);
            $commentaire->setDate(new \DateTime('now'));
            $em=$this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
            $doctrine= $this->getDoctrine();
            $repository = $doctrine->getRepository('AppBundle:CommentaireArticle');
            $commentaires = $repository->findAll();
            $repository = $doctrine->getRepository('AppBundle:Article');
            $nbcoment = $repository->getcoments($article->getId());
            return $this->render('@Article/Article/Display_Article_Solo.html.twig', array(
                'Articles'=>$Articles[0],'f'=>$form->createView(),'Commentaires'=>$commentaires,'nbcomment'=>$nbcoment[0]
            ));

        }
        $form=$this->createForm(CommentaireArticleType::class,$commentaire);
        return $this->render('@Article/Article/Display_Article_Solo.html.twig', array(
            'Articles'=>$Articles[0],'f'=>$form->createView(),'Commentaires'=>$commentaires,'nbcomment'=>$nbcoment[0]
        ));



    }

    public function deleteCommentAction(CommentaireArticle $comment,$idarticle)
    {

        $em= $this->getDoctrine()->getManager();

        $em->remove($comment);
        $em->flush();
        return $this->redirect($this->generateUrl('Display_article_solo',array('id' => $idarticle)));
    }

    public function displayArticleFrontAction($theme)
    {

        $doctrine= $this->getDoctrine();

        $repository = $doctrine->getRepository('AppBundle:Theme');

        $themes = $repository->findAll();

        if($theme == 'all')
        {
            $doctrine= $this->getDoctrine();

            $repository = $doctrine->getRepository('AppBundle:Article');

            $Articles = $repository->findAll();

        }
        else
        {

            $doctrine= $this->getDoctrine();

            $repository = $doctrine->getRepository('AppBundle:Article');
            $themee = $repository->getthemebyname($theme);
            $Articles = $repository->getarticlebytheme($themee);

        }

        return $this->render('@Article/Article/Display_article_front.html.twig', array(
            'Articles'=>$Articles,
            'Themes'=>$themes,
            'selectedTheme'=>$theme
        ));
    }


    public function rateAction(Article $article, $type)
    { $em = $this->getDoctrine()->getManager();
        $rating = new RatingArticle();

        $m=$this->getDoctrine()->getManager();
        $user=$this->getUser();


        $doctrine = $this->getDoctrine();
        $repository =  $doctrine->getRepository('AppBundle:RatingArticle');

        $ratings = $repository->getRating($this->getUser()->getId(),$article);

        if ( $ratings != null )
        {

            if($ratings[0]->getType()==$type){

                $em->remove($ratings[0]);

                $em->flush();

            }

            else {

                $ratings[0]->setType($type);
                $em->persist($ratings[0]);

                $em->flush();
            }
        }
        else
        {



            $rating->setUser($user);
            $rating->setArticle($article);
            $rating->setType($type);
            $em->persist($rating);

            $em->flush();



        }

        return $this->redirect($this->generateUrl('Display_article_solo', array('id' => $article->getId())));



    }

}
