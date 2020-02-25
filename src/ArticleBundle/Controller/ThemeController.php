<?php

namespace ArticleBundle\Controller;


use AppBundle\Entity\Theme;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ThemeController extends Controller
{
    /**
     * @Route("/AddTheme")
     */
    public function AddAction(Request $request)
    {
        $theme =new Theme();
        $formTheme = $this->createForm('ArticleBundle\Form\ThemeType',$theme);
        $formTheme->handleRequest($request);
        if($formTheme->isSubmitted() && $formTheme->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->persist($theme);
            $em->flush();
            return $this->redirectToRoute('List_theme');

        }


        return $this->render('@Article/Theme/add.html.twig', array(
            'formTheme' => $formTheme->createView() // ...
        ));
    }

    /**
     * @Route("/ModifiyTheme")
     */
    public function ModifiyThemeAction(Request $request , Theme $themes)
    {
        $editFormevent = $this->createForm('ArticleBundle\Form\ThemeType',$themes);
        $editFormevent->handleRequest($request);
        if ($editFormevent->isSubmitted() && $editFormevent->isValid())
        {
            //$events->setDateUpdate(new \DateTime('now'));
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('List_theme');
        }

        return $this->render('ArticleBundle:Theme:modifiy_theme.html.twig', array(
            'themes'    => $themes,
            'formthemes'    => $editFormevent->createView(),

        ));
    }

    /**
     * @Route("/DeleteTheme")
     */
    public function DeleteThemeAction($id)
    {
        $themes =new Theme();
        $doctrine= $this->getDoctrine();
        $repository = $doctrine->getRepository(  'AppBundle:Theme');
        $themes = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($themes);
        $em->flush();

       // return $this->forward('ArticleBundle:Theme:ListTheme');
        return $this->redirectToRoute("List_theme");
    }

    /**
     * @Route("/ListTheme")
     */
    public function ListThemeAction()
    {
        $doctrine= $this->getDoctrine();
        $repository = $doctrine->getRepository('AppBundle:Theme');
        $Themes = $repository->findAll();
        return $this->render('@Article/Theme/list_theme.html.twig',
            array(
                'themes'=>$Themes,

            ));
    }

}
