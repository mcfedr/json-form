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
            $response = new JsonResponse(array_merge(['error' => $exception->getMessage()], $data ? ['errorInfo' => $exception->getData()] : []));
            $event->setResponse($response);
        }
    }
}
