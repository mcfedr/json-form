<?php

declare(strict_types=1);

namespace Mcfedr\JsonFormBundle\Controller;

use Mcfedr\JsonFormBundle\Exception\InvalidJsonHttpException;
use Mcfedr\JsonFormBundle\Type\TestType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends JsonController
{
    use JsonControllerTrait;

    /**
     * @Route("/invalid", methods={"GET"})
     */
    public function jsonAction(): void
    {
        throw new InvalidJsonHttpException();
    }

    /**
     * @Route("/form", methods={"POST"})
     */
    public function formAction(Request $request): Response
    {
        $form = $this->createJsonForm(TestType::class);
        $this->handleJsonForm($form, $request);

        return new JsonResponse($form->getData());
    }
}
