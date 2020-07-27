<?php

declare(strict_types=1);

namespace Mcfedr\JsonFormBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class JsonController extends AbstractController
{
    use JsonControllerTrait;
}
