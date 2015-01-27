<?php

namespace Mcfedr\JsonForm\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class JsonHttpException extends HttpException
{
    public function __construct($statusCode, $data)
    {
        parent::__construct(
            $statusCode,
            json_encode($data),
            null,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }
}
