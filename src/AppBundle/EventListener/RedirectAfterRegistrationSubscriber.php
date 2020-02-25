<?php


namespace AppBundle\EventListener;


use Composer\EventDispatcher\EventSubscriberInterface;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;

class RedirectAfterRegistrationSubscriber implements EventSubscriberInterface
{
    private $route;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;

    }

    public function onRegistrationSucces(GetResponseEvent $event)
    {
        $url=$this->router->generate('accueil');
        $response = new RedirectResponse($url);
        $event->setResponse($response);

    }

    public static function getSubscribedEvents()
    {
        return [
                FOSUserEvents::REGISTRATION_SUCCESS =>'onRegistrationSucces'
                ];
    }


}