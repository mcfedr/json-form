<?php

declare(strict_types=1);

namespace Mcfedr\JsonFormBundle\Exception;

class InvalidJsonHttpException extends JsonHttpException
{
    public function __construct()
    {
        parent::__construct(400, 'Invalid JSON');
    }
}
