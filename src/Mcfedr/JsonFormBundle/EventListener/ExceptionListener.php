<?php

namespace Mcfedr\JsonFormBundle\EventListener;

use Mcfedr\JsonFormBundle\Exception\JsonHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof JsonHttpException) {
            $data = $exception->getData();
            $response = new JsonResponse(['error' => array_merge(['code'=> $exception->getStatusCode(), 'message' => $exception->getMessage()], $data ? ['info' => $exception->getData()] : [])]);
            $event->setResponse($response);
        }
    }
}
