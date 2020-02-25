<?php

namespace ArticleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Article;					 //A MODIFIER SELON TRAVAIL
use ArticleBundle\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;
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
            return $this->redirectToRoute('Display_article_front');

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
        return $this->forward('ArticleBundle:Article:displayArticleFront');
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

    public function displayArticleFrontAction()
    {

        $doctrine= $this->getDoctrine();
        $repository = $doctrine->getRepository('AppBundle:Article');
        $Articles = $repository->findAll();
        return $this->render('@Article/Article/Display_article_front.html.twig', array(
            'Articles'=>$Articles,
        ));
    }

}
