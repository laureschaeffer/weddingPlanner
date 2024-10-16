<?php

namespace App\Security;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Redirect403
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
        //si c'est un erreur 403
        if ($exception instanceof AccessDeniedHttpException) {

            //redirige vers la page d'erreur
            $response = new RedirectResponse($this->router->generate('app_error_403'));
            $event->setResponse($response);
        }
    }
    
}