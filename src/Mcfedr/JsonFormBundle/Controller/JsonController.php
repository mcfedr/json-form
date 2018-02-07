<?php

namespace Mcfedr\JsonFormBundle\Controller;

use Mcfedr\JsonFormBundle\Exception\InvalidFormHttpException;
use Mcfedr\JsonFormBundle\Exception\InvalidJsonHttpException;
use Mcfedr\JsonFormBundle\Exception\MissingFormHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

abstract class JsonController extends Controller
{
    /**
     * @param Form     $form
     * @param Request  $request
     * @param callable $preValidation callback to be called before the form is validated
     *
     * @throws \Mcfedr\JsonFormBundle\Exception\InvalidFormHttpException
     * @throws \Mcfedr\JsonFormBundle\Exception\MissingFormHttpException
     * @throws \Mcfedr\JsonFormBundle\Exception\InvalidJsonHttpException
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

    public function createJsonForm($type, $data = null, array $options = [])
    {
        $this->checkCsrf($options);

        return parent::createForm($type, $data, $options);
    }

    public function createJsonFormBuilder($data = null, array $options = [])
    {
        $this->checkCsrf($options);

        return parent::createFormBuilder($data, $options);
    }

    private function checkCsrf(&$options)
    {
        if (!array_key_exists('csrf_protection', $options) && $this->container->getParameter('form.type_extension.csrf.enabled')) {
            $options['csrf_protection'] = false;
        }
    }
}
