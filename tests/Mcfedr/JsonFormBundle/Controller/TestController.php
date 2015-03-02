<?php

namespace Mcfedr\JsonFormBundle\Controller;

use Mcfedr\JsonFormBundle\Exception\InvalidJsonHttpException;
use Mcfedr\JsonFormBundle\Type\TestType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @param Request $request
     * @return Response
     */
    public function formAction(Request $request)
    {
        $form = $this->createForm(new TestType());
        $this->handleJsonForm($form, $request);

        return new Response();
    }
}
