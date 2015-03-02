<?php

namespace Mcfedr\JsonFormBundle\Exception;

use Symfony\Component\Form\Form;

class MissingFormHttpException extends JsonHttpException
{
    public function __construct(Form $form)
    {
        parent::__construct(400, 'Missing ' . $form->getName());
    }
}
