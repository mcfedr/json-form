<?php

namespace Mcfedr\JsonForm\Exception;

class InvalidJsonHttpException extends JsonHttpException
{
    public function __construct()
    {
        parent::__construct(400, [
            'error' => 'Invalid JSON'
        ]);
    }
}
