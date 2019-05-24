<?php

declare(strict_types=1);

namespace Mcfedr\JsonFormBundle\Exception;

use Symfony\Component\Form\FormInterface;

class MissingFormHttpException extends JsonHttpException
{
    public function __construct(FormInterface $form)
    {
        parent::__construct(400, 'Missing '.$form->getName());
    }
}
