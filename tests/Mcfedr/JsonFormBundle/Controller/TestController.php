<?php

namespace Mcfedr\JsonFormBundle\Controller;

use Mcfedr\JsonFormBundle\Exception\InvalidJsonHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
}
