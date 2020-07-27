<?php

declare(strict_types=1);

namespace Mcfedr\JsonFormBundle\EventListener;

use Mcfedr\JsonFormBundle\Exception\JsonHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof JsonHttpException) {
            $errorData = [
                'error' => [
                    'code' => $exception->getStatusCode(),
                    'message' => $exception->getMessage(),
                ],
            ];
            if (($data = $exception->getData())) {
                $errorData['error']['info'] = $data;
            }
            $response = new JsonResponse($errorData);
            $event->setResponse($response);
        }
    }
}
