<?php

namespace App\Security;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Redirect404
{
    private $router;

    // service de routage pour les url
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    // lorsqu'une exception se produit
    public function onKernelException(ExceptionEvent $event)
    {
        // récupère l'exception
        $exception = $event->getThrowable();

        // vérifie que c'est une erreur 404
        if (!$exception instanceof NotFoundHttpException) {
            return; 
        }

        // redirige vers la page d'accueil ; redirectToRoute n'existe qu'avec l'abstractController
        $response = new RedirectResponse($this->router->generate('app_home'));

        // réponse à l'événement
        $event->setResponse($response);
    }
}