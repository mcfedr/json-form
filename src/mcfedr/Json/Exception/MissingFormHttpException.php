<?php

namespace mcfedr\Json\Exception;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MissingFormHttpException extends HttpException
{
    public function __construct(Form $form)
    {
        parent::__construct(
            400,
            json_encode(['error' => 'Missing ' . $form->getName()]),
            null,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }
}
