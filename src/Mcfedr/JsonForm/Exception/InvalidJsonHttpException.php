<?php

namespace Mcfedr\JsonForm\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidJsonHttpException extends HttpException
{
    public function __construct()
    {
        parent::__construct(
            400,
            json_encode(['error' => 'Invalid JSON']),
            null,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }
}
