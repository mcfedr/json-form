<?php

namespace Mcfedr\JsonFormBundle\Controller;

use Mcfedr\JsonFormBundle\Exception\InvalidJsonHttpException;
use Mcfedr\JsonFormBundle\Type\TestType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class TestController extends JsonController
{
    /**
     * @Route("/invalid")
     * @Method("GET")
     */
    public function jsonAction()
    {
        throw new InvalidJsonHttpException();
    }

    /**
     * @Route("/form")
     * @Method("POST")
     */
    public function formAction(Request $request)
    {
        $form = $this->createForm(TestType::class);
        $this->handleJsonForm($form, $request);

        return new JsonResponse($form->getData());
    }
}
