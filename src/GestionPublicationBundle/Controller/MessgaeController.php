<?php

namespace GestionPublicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MessgaeController extends Controller
{
    public function accueilAction($id)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $thread = $this->container->get('fos_message.provider')->getThread($id);
        $thread->getLastMessage()->SetIsReadByParticipant($user, 1);
        $form = $this->container->get('fos_message.reply_form.factory')->create($thread);
        $formHandler = $this->container->get('fos_message.reply_form.handler');

        if ($message = $formHandler->process($form)) {
            return new RedirectResponse($this->container->get('router')->generate('accueil_message', array(
                'id' => $message->getThread()->getId(),
            )));

        }

        $provider = $this->container->get('fos_message.provider');
        $threadsInbox = $provider->getInboxThreads();
        $threads = $provider->getSentThreads();
        return $this->render('@GestionPublication/message/chat.html.twig', array(
            'form' => $form->createView(),
            'thread' => $thread,
            'threads' => $threads,
            'user' =>$user,
            'threadsInbox' => $threadsInbox
        ));
    }
}
