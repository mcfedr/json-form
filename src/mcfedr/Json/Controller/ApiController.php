<?php

namespace mcfedr\Json\Controller;

use mcfedr\Json\Exception\InvalidJsonHttpException;
use mcfedr\Json\Exception\MissingFormHttpException;
use mcfedr\Json\Exception\InvalidFormHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

abstract class ApiController extends Controller
{
    /**
     * @param Form $form
     * @param Request $request
     * @param callable $preValidation callback to be called before the form is validated
     * @throws \mcfedr\Json\Exception\InvalidFormHttpException
     * @throws \mcfedr\Json\Exception\MissingFormHttpException
     * @throws \mcfedr\Json\Exception\InvalidJsonHttpException
     */
    protected function handleJsonForm(Form $form, Request $request, callable $preValidation = null)
    {
        $bodyJson = $request->getContent();
        if (!($body = json_decode($bodyJson, true))) {
            throw new InvalidJsonHttpException();
        }

        if (!isset($body[$form->getName()])) {
            throw new MissingFormHttpException($form);
        }

        $form->submit($body[$form->getName()]);

        if ($preValidation) {
            $preValidation();
        }

        if (!$form->isValid()) {
            throw new InvalidFormHttpException($form);
        }
    }

    public function createForm($type, $data = null, array $options = array())
    {
        if (!isset($options['csrf_protection'])) {
            $options['csrf_protection'] = false;
        }
        return parent::createForm($type, $data, $options);
    }
} 