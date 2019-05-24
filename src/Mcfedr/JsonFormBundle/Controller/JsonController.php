<?php

declare(strict_types=1);

namespace Mcfedr\JsonFormBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @deprecated since 4.5.0, use "Mcfedr\JsonFormBundle\Controller\JsonControllerTrait" instead.
 */
abstract class JsonController extends Controller
{
    use JsonControllerTrait;
}
