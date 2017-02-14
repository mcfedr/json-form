<?php

namespace Mcfedr\JsonFormBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class JsonHttpException extends HttpException
{
    private $data;

    /**
     * @param int    $statusCode
     * @param string $message
     * @param mixed  $data
     */
    public function __construct($statusCode, $message = null, $data = null)
    {
        parent::__construct($statusCode, $message);

        $this->setData($data);
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}
